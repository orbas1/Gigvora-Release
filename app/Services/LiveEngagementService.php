<?php

namespace App\Services;

use App\Models\Live_streamings;
use App\Models\LiveStreamingEngagement;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LiveEngagementService
{
    public function summary(Live_streamings $stream): array
    {
        $goal = Arr::get($stream->engagement_config, 'donation_goal', 0);

        $donations = LiveStreamingEngagement::query()
            ->where('live_streaming_id', $stream->streaming_id)
            ->where('type', 'donation')
            ->select('user_id', 'amount', 'payload', 'created_at')
            ->orderByDesc('created_at')
            ->limit(25)
            ->get();

        $total = (float) LiveStreamingEngagement::query()
            ->where('live_streaming_id', $stream->streaming_id)
            ->where('type', 'donation')
            ->sum('amount');

        $leaderboard = LiveStreamingEngagement::query()
            ->where('live_streaming_id', $stream->streaming_id)
            ->where('type', 'donation')
            ->select('user_id', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('user_id')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get()
            ->map(function ($row) {
                $user = $row->user_id ? User::find($row->user_id) : null;
                return [
                    'name' => $user?->name ?? get_phrase('Anonymous supporter'),
                    'amount' => (float) $row->total_amount,
                    'avatar' => $user ? get_user_image($user->photo, 'optimized') : null,
                ];
            })
            ->toArray();

        $viewerKey = $this->viewerCacheKey($stream);
        $viewers = Cache::get($viewerKey, 0);

        return [
            'goal' => (float) $goal,
            'total' => $total,
            'progress' => $goal > 0 ? min(100, round(($total / $goal) * 100, 2)) : null,
            'recent_donations' => $donations->map(function (LiveStreamingEngagement $engagement) {
                $payload = $engagement->payload ?? [];
                return [
                    'amount' => (float) $engagement->amount,
                    'message' => Arr::get($payload, 'message'),
                    'sticker' => Arr::get($payload, 'sticker'),
                    'user' => $engagement->user?->only(['id', 'name']) ?? [
                        'id' => null,
                        'name' => Arr::get($payload, 'display_name', get_phrase('Anonymous')),
                    ],
                    'created_at' => $engagement->created_at?->toIso8601String(),
                ];
            }),
            'leaderboard' => $leaderboard,
            'viewer_goal' => Arr::get($stream->engagement_config, 'viewer_goal', 0),
            'viewer_count' => $viewers,
            'viewer_peak' => $stream->viewer_peak,
        ];
    }

    public function recordViewer(Live_streamings $stream): void
    {
        $viewerKey = $this->viewerCacheKey($stream);
        $count = Cache::increment($viewerKey);
        Cache::put($viewerKey, $count, now()->addMinutes(30));

        if ($count > (int) $stream->viewer_peak) {
            $stream->update(['viewer_peak' => $count]);
        }
    }

    public function recordDonation(Live_streamings $stream, User $user, float $amount, array $payload = []): LiveStreamingEngagement
    {
        $engagement = LiveStreamingEngagement::create([
            'live_streaming_id' => $stream->streaming_id,
            'user_id' => $user->id,
            'type' => 'donation',
            'amount' => $amount,
            'payload' => [
                'message' => Arr::get($payload, 'message'),
                'sticker' => Arr::get($payload, 'sticker'),
                'display_name' => $user->name,
            ],
        ]);

        return $engagement;
    }

    public function recordReaction(Live_streamings $stream, User $user, string $reaction): LiveStreamingEngagement
    {
        return LiveStreamingEngagement::create([
            'live_streaming_id' => $stream->streaming_id,
            'user_id' => $user->id,
            'type' => 'reaction',
            'payload' => [
                'reaction' => $reaction,
            ],
        ]);
    }

    public function submitQuestion(Live_streamings $stream, User $user, string $question): LiveStreamingEngagement
    {
        return LiveStreamingEngagement::create([
            'live_streaming_id' => $stream->streaming_id,
            'user_id' => $user->id,
            'type' => 'question',
            'payload' => [
                'question' => $question,
            ],
        ]);
    }

    protected function viewerCacheKey(Live_streamings $stream): string
    {
        return sprintf('live_stream:%s:viewers', $stream->streaming_id);
    }
}

