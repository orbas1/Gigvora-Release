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

    public function index(Request $request): JsonResponse
    {
        $series = PodcastSeries::query()
            ->withCount(['episodes', 'followers'])
            ->with(['episodes' => function ($query) {
                $query->where('is_public', true)
                    ->whereNotNull('published_at')
                    ->latest('published_at')
                    ->limit(3);
            }])
            ->where(function ($query) use ($request) {
                $query->where('is_public', true);

                if ($request->user()) {
                    $query->orWhere('host_id', $request->user()->getAuthIdentifier());
                }
            })
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->paginate();

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

    public function showSeries(Request $request, PodcastSeries $podcastSeries): JsonResponse
    {
        $this->authorize('view', $podcastSeries);

        $canManage = $request->user()?->can('update', $podcastSeries) ?? false;

        $podcastSeries
            ->loadCount(['episodes', 'followers'])
            ->load(['episodes' => function ($query) use ($canManage) {
                $query->orderByDesc('published_at');

                if (!$canManage) {
                    $query->where('is_public', true)->whereNotNull('published_at');
                }
            }]);

        $isFollowed = $request->user()
            ? $podcastSeries->followers()->where('user_id', $request->user()->getAuthIdentifier())->exists()
            : false;

        return response()->json(
            array_merge($podcastSeries->toArray(), ['is_followed' => $isFollowed])
        );
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

        if ($episode->podcast_series_id !== $podcastSeries->getKey()) {
            abort(404);
        }

        $episode->update(['published_at' => now(), 'is_public' => true]);

        Analytics::track('podcast_episode_published', [
            'series_id' => $podcastSeries->id,
            'episode_id' => $episode->id,
        ]);

        return response()->json($episode);
    }

    public function showEpisode(PodcastSeries $podcastSeries, PodcastEpisode $episode): JsonResponse
    {
        if ($episode->podcast_series_id !== $podcastSeries->getKey()) {
            abort(404);
        }

        $this->authorize('view', $podcastSeries);

        return response()->json($episode->load('series'));
    }

    public function toggleFollow(Request $request, PodcastSeries $podcastSeries): JsonResponse
    {
        $this->authorize('view', $podcastSeries);

        $request->validate([
            'state' => 'nullable|in:follow,unfollow,toggle',
        ]);

        $userId = $request->user()?->getAuthIdentifier();
        if (!$userId) {
            abort(403, 'Authentication required');
        }

        $isFollowing = $podcastSeries->followers()->where('user_id', $userId)->exists();
        $shouldFollow = match ($request->input('state')) {
            'follow' => true,
            'unfollow' => false,
            default => !$isFollowing,
        };

        if ($shouldFollow && !$isFollowing) {
            $podcastSeries->followers()->attach($userId);
            Analytics::track('podcast_series_followed', ['series_id' => $podcastSeries->id, 'user_id' => $userId]);
        }

        if (!$shouldFollow && $isFollowing) {
            $podcastSeries->followers()->detach($userId);
            Analytics::track('podcast_series_unfollowed', ['series_id' => $podcastSeries->id, 'user_id' => $userId]);
        }

        return response()->json([
            'followed' => $shouldFollow,
            'followers_count' => $podcastSeries->followers()->count(),
        ]);
    }

    public function recordPlayback(Request $request, PodcastSeries $podcastSeries, PodcastEpisode $episode): JsonResponse
    {
        if ($episode->podcast_series_id !== $podcastSeries->getKey()) {
            abort(404);
        }

        $this->authorize('view', $podcastSeries);

        $validated = $request->validate([
            'progress_seconds' => 'nullable|integer|min:0',
            'completed' => 'boolean',
        ]);

        Analytics::track('podcast_episode_played', [
            'series_id' => $podcastSeries->id,
            'episode_id' => $episode->id,
            'user_id' => $request->user()?->getAuthIdentifier(),
            'progress_seconds' => $validated['progress_seconds'] ?? null,
            'completed' => $validated['completed'] ?? false,
        ]);

        return response()->json(['status' => 'ok']);
    }
}

