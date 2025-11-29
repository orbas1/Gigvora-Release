<?php

namespace ProNetwork\Services;

use ProNetwork\Models\StoryMetadata;

class StoryEnhancementService
{
    public function createOrUpdate(int $storyId, array $payload): StoryMetadata
    {
        return StoryMetadata::updateOrCreate(
            ['story_id' => $storyId],
            [
                'overlays' => $payload['overlays'] ?? null,
                'filters' => $payload['filters'] ?? null,
                'stickers' => $payload['stickers'] ?? null,
                'links' => $payload['links'] ?? null,
                'music_track_id' => $payload['music_track_id'] ?? null,
                'live_session_id' => $payload['live_session_id'] ?? null,
            ]
        );
    }

    public function linkLiveReplay(int $storyId, int $liveSessionId): StoryMetadata
    {
        return $this->createOrUpdate($storyId, ['live_session_id' => $liveSessionId]);
    }
}
