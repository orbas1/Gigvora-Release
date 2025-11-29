<?php

namespace ProNetwork\Services;

use ProNetwork\Models\LiveSession;
use ProNetwork\Models\LiveSessionParticipant;
use ProNetwork\Models\StoryMetadata;

class LiveStreamingWrapper
{
    public function schedule(int $hostId, string $title, array $payload = []): LiveSession
    {
        return LiveSession::create([
            'host_id' => $hostId,
            'title' => $title,
            'description' => $payload['description'] ?? null,
            'status' => 'scheduled',
            'guest_user_ids' => $payload['guest_user_ids'] ?? [],
            'chat_channel' => $payload['chat_channel'] ?? null,
        ]);
    }

    public function start(LiveSession $session): LiveSession
    {
        $session->update([
            'status' => 'live',
            'started_at' => now(),
        ]);

        return $session->fresh();
    }

    public function end(LiveSession $session, ?string $recordingPath = null): LiveSession
    {
        $session->update([
            'status' => 'ended',
            'ended_at' => now(),
            'recording_path' => $recordingPath,
        ]);

        return $session->fresh();
    }

    public function registerGuest(LiveSession $session, int $userId, string $role = 'guest'): LiveSessionParticipant
    {
        $session->increment('likes_count', 0); // ensure touch

        return LiveSessionParticipant::updateOrCreate([
            'live_session_id' => $session->id,
            'user_id' => $userId,
        ], [
            'role' => $role,
            'joined_at' => now(),
        ]);
    }

    public function recordLike(LiveSession $session): void
    {
        $session->increment('likes_count');
    }

    public function recordDonation(LiveSession $session, float $amount): void
    {
        $session->increment('donations_total', $amount);
    }

    public function linkStoryReplay(LiveSession $session, int $storyId): StoryMetadata
    {
        return StoryMetadata::updateOrCreate(
            ['story_id' => $storyId],
            ['live_session_id' => $session->id]
        );
    }
}
