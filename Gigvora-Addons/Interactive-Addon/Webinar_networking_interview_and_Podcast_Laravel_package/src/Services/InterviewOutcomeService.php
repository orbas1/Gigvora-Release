<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Interview;
use Jobi\WebinarNetworkingInterviewPodcast\Models\InterviewSlot;
use Jobi\WebinarNetworkingInterviewPodcast\Support\Analytics\Analytics;

class InterviewOutcomeService
{
    public function recordScore(Interview $interview, InterviewSlot $slot, int $interviewerId, array $payload): void
    {
        DB::transaction(function () use ($interview, $slot, $interviewerId, $payload) {
            $score = $interview->scores()->create([
                'interview_slot_id' => $slot->id,
                'interviewer_id' => $interviewerId,
                'criteria' => $payload['criteria'],
                'scores' => $payload['scores'],
                'comments' => $payload['comments'] ?? null,
            ]);

            $aggregates = $this->aggregateScores($interview);
            $this->persistAggregates($interview, $aggregates, $payload);

            Analytics::track('interview_scored', [
                'interview_id' => $interview->id,
                'interview_slot_id' => $slot->id,
                'interviewer_id' => $interviewerId,
                'total_score' => $aggregates['total'] ?? null,
                'average_score' => $aggregates['average'] ?? null,
            ]);

            $this->syncAts($interview, $aggregates, $score->created_at);
            $this->syncUtilities($interview, $aggregates);
        });
    }

    public function captureConsent(Interview $interview, array $consent): void
    {
        $consents = $interview->metadata['consents'] ?? [];
        $consents[$consent['type']] = [
            'granted' => (bool) $consent['granted'],
            'source' => $consent['source'] ?? 'unknown',
            'timestamp' => Carbon::parse($consent['timestamp'] ?? now())->toIso8601String(),
            'region' => $consent['region'] ?? null,
        ];

        $interview->forceFill(['metadata' => array_merge($interview->metadata ?? [], ['consents' => $consents])])->save();
    }

    protected function aggregateScores(Interview $interview): array
    {
        $scores = $interview->scores;
        if ($scores->isEmpty()) {
            return ['total' => 0, 'average' => 0, 'count' => 0, 'pass' => null];
        }

        $flattened = $scores->flatMap(fn ($score) => $score->scores ?? []);
        $total = $flattened->sum();
        $count = $flattened->count();
        $average = $count ? round($total / $count, 2) : 0;

        return [
            'total' => $total,
            'average' => $average,
            'count' => $count,
            'pass' => $average >= 3,
        ];
    }

    protected function persistAggregates(Interview $interview, array $aggregates, array $payload): void
    {
        $metadata = $interview->metadata ?? [];
        $history = $metadata['score_history'] ?? [];
        $history[] = [
            'recorded_at' => Carbon::now()->toIso8601String(),
            'average' => $aggregates['average'],
            'total' => $aggregates['total'],
            'pass' => $aggregates['pass'],
            'recommendation' => Arr::get($payload, 'recommendation'),
        ];

        $metadata['aggregates'] = $aggregates;
        $metadata['latest_recommendation'] = Arr::get($payload, 'recommendation');
        $metadata['score_history'] = $history;

        $interview->forceFill(['metadata' => $metadata])->save();
    }

    protected function syncAts(Interview $interview, array $aggregates, $timestamp): void
    {
        $metadata = $interview->metadata ?? [];
        $atsLogs = $metadata['ats_logs'] ?? [];

        $atsLogs[] = [
            'state' => $aggregates['pass'] ? 'advance' : 'hold',
            'average' => $aggregates['average'],
            'recorded_at' => Carbon::parse($timestamp)->toIso8601String(),
        ];

        $metadata['ats_logs'] = $atsLogs;
        $interview->forceFill(['metadata' => $metadata])->save();
    }

    protected function syncUtilities(Interview $interview, array $aggregates): void
    {
        $metadata = $interview->metadata ?? [];
        $utilities = $metadata['utilities'] ?? [];
        $utilities['last_synced'] = Carbon::now()->toIso8601String();
        $utilities['reminder'] = $utilities['reminder'] ?? [
            'type' => 'follow_up',
            'status' => 'queued',
            'note' => 'Interview outcome ready for ATS sync.',
        ];

        $metadata['utilities'] = $utilities;
        $interview->forceFill(['metadata' => $metadata])->save();
    }
}
