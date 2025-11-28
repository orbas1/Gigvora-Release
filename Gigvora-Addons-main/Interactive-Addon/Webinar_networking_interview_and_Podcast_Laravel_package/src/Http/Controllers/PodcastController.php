<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jobi\WebinarNetworkingInterviewPodcast\Models\PodcastEpisode;
use Jobi\WebinarNetworkingInterviewPodcast\Models\PodcastSeries;
use Jobi\WebinarNetworkingInterviewPodcast\Support\Analytics\Analytics;

class PodcastController extends Controller
{
    use AuthorizesRequests;

    public function index(): JsonResponse
    {
        $series = PodcastSeries::query()->with(['episodes', 'recordings'])->paginate();
        return response()->json($series);
    }

    public function storeSeries(Request $request): JsonResponse
    {
        $this->authorize('create', PodcastSeries::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'cover_art_path' => 'nullable|string',
            'is_public' => 'boolean',
            'metadata' => 'array',
        ]);

        $series = PodcastSeries::create(array_merge($validated, [
            'host_id' => $request->user()->getAuthIdentifier(),
        ]));

        Analytics::track('podcast_series_created', ['series_id' => $series->id, 'host_id' => $series->host_id]);

        return response()->json($series, 201);
    }

    public function showSeries(PodcastSeries $podcastSeries): JsonResponse
    {
        $this->authorize('view', $podcastSeries);
        return response()->json($podcastSeries->load('episodes'));
    }

    public function updateSeries(Request $request, PodcastSeries $podcastSeries): JsonResponse
    {
        $this->authorize('update', $podcastSeries);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'cover_art_path' => 'nullable|string',
            'is_public' => 'boolean',
            'metadata' => 'array',
        ]);

        $podcastSeries->update($validated);

        return response()->json($podcastSeries);
    }

    public function storeEpisode(Request $request, PodcastSeries $podcastSeries): JsonResponse
    {
        $this->authorize('update', $podcastSeries);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_for' => 'nullable|date',
            'published_at' => 'nullable|date',
            'audio_path' => 'nullable|string',
            'duration' => 'nullable|integer|min:1',
            'metadata' => 'array',
            'is_public' => 'boolean',
        ]);

        $episode = $podcastSeries->episodes()->create($validated);

        Analytics::track('podcast_episode_created', [
            'series_id' => $podcastSeries->id,
            'episode_id' => $episode->id,
        ]);

        return response()->json($episode, 201);
    }

    public function publishEpisode(PodcastSeries $podcastSeries, PodcastEpisode $episode): JsonResponse
    {
        $this->authorize('update', $podcastSeries);
        $episode->update(['published_at' => now(), 'is_public' => true]);

        Analytics::track('podcast_episode_published', [
            'series_id' => $podcastSeries->id,
            'episode_id' => $episode->id,
        ]);

        return response()->json($episode);
    }
}

