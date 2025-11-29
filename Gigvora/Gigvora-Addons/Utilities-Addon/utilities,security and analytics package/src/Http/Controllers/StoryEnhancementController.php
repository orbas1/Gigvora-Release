<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ProNetwork\Models\StoryMetadata;
use ProNetwork\Services\AnalyticsService;
use ProNetwork\Services\MusicLibraryService;
use ProNetwork\Services\StoryEnhancementService;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\CreateStoryRequest;

class StoryEnhancementController extends Controller
{
    public function __construct(
        protected StoryEnhancementService $storyService,
        protected AnalyticsService $analyticsService,
        protected MusicLibraryService $musicLibraryService
    ) {
    }

    public function viewer(Request $request)
    {
        $storyId = (int) $request->query('story_id', 0);
        $metadata = null;

        if ($storyId > 0) {
            $metadata = StoryMetadata::with(['musicTrack', 'liveSession.participants'])
                ->where('story_id', $storyId)
                ->first();

            if (! $metadata) {
                $metadata = $this->storyService->createOrUpdate($storyId, []);
            }
        }

        $this->analyticsService->track('story.viewer.opened', ['story_id' => $storyId ?: null], $request->user());

        $tracks = $this->musicLibraryService->list();

        return view('pro_network::stories.viewer', [
            'story_id' => $storyId ?: null,
            'metadata' => $metadata,
            'tracks' => $tracks,
        ]);
    }

    public function creator(Request $request)
    {
        $this->analyticsService->track('story.creator.opened', [], $request->user());

        $tracks = $this->musicLibraryService->list();

        return view('pro_network::stories.creator', [
            'tracks' => $tracks,
        ]);
    }

    public function store(CreateStoryRequest $request)
    {
        $data = $request->validated();

        $metadata = $this->storyService->createOrUpdate((int) $data['story_id'], [
            'overlays' => $data['overlays'] ?? null,
            'filters' => $data['filters'] ?? null,
            'stickers' => $data['stickers'] ?? null,
            'links' => $data['links'] ?? null,
            'music_track_id' => $data['music_track_id'] ?? null,
            'live_session_id' => $data['live_session_id'] ?? null,
        ]);

        $this->analyticsService->track('story.saved', ['story_id' => $metadata->story_id], $request->user());

        return response()->json([
            'story_id' => $metadata->story_id,
            'metadata' => $metadata->refresh(),
        ], 201);
    }

    public function viewers(Request $request, int $storyId)
    {
        $metadata = StoryMetadata::with('liveSession.participants.user')
            ->where('story_id', $storyId)
            ->first();

        if (! $metadata) {
            $metadata = $this->storyService->createOrUpdate($storyId, []);
        }

        $viewers = $metadata->liveSession?->participants ?? collect();

        $this->analyticsService->track('story.viewers.list', ['story_id' => $storyId], $request->user());

        return response()->json([
            'story_id' => $storyId,
            'viewers' => $viewers,
        ]);
    }
}
