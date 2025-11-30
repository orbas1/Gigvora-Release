<?php

namespace App\Observers;

use App\Services\UtilitiesCalendarService;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterInterview;

class TalentAiHeadhunterInterviewObserver
{
    public function created(HeadhunterInterview $interview): void
    {
        $this->syncCalendar($interview);
    }

    public function updated(HeadhunterInterview $interview): void
    {
        $this->syncCalendar($interview);
    }

    public function deleted(HeadhunterInterview $interview): void
    {
        $this->cancelCalendar($interview);
    }

    protected function syncCalendar(HeadhunterInterview $interview): void
    {
        $interview->loadMissing('pipelineItem.candidate', 'pipelineItem.mandate');

        $pipeline = $interview->pipelineItem;
        if (! $pipeline) {
            return;
        }

        $candidate = $pipeline->candidate;
        $mandate = $pipeline->mandate;

        $title = $mandate?->title ?? get_phrase('Headhunter interview');
        $subtitle = $candidate?->name ?? get_phrase('Candidate');
        $startsAt = $interview->scheduled_at ?? now();
        $status = $interview->status ?? 'scheduled';

        foreach ($this->participants($interview) as $userId) {
            app(UtilitiesCalendarService::class)->upsert([
                'user_id' => $userId,
                'source' => 'talent_ai_headhunter_interview',
                'source_id' => (string) $interview->id,
                'title' => $title,
                'subtitle' => $subtitle,
                'description' => $pipeline->stage?->value ?? null,
                'starts_at' => $startsAt,
                'location' => $mandate?->location,
                'status' => $status,
                'metadata' => [
                    'module' => 'talent_ai.headhunters',
                    'candidate_id' => $candidate?->id,
                    'pipeline_stage' => $pipeline->stage?->value,
                ],
            ]);
        }
    }

    protected function cancelCalendar(HeadhunterInterview $interview): void
    {
        foreach ($this->participants($interview) as $userId) {
            app(UtilitiesCalendarService::class)->cancel(
                $userId,
                'talent_ai_headhunter_interview',
                (string) $interview->id,
                'cancelled'
            );
        }
    }

    protected function participants(HeadhunterInterview $interview): array
    {
        $pipeline = $interview->pipelineItem;
        $candidateUserId = optional($pipeline?->candidate)->user_id;

        return collect([
            $interview->scheduled_by,
            $candidateUserId,
        ])
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}

