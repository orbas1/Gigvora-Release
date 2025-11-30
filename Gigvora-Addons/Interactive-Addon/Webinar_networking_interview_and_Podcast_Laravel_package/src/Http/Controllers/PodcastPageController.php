<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Jobi\WebinarNetworkingInterviewPodcast\Models\PodcastEpisode;
use Jobi\WebinarNetworkingInterviewPodcast\Models\PodcastSeries;
use Jobi\WebinarNetworkingInterviewPodcast\Support\Analytics\Analytics;

class PodcastPageController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $series = PodcastSeries::query()
            ->with(['episodes' => fn ($query) => $query->latest('published_at')])
            ->withCount(['episodes', 'followers'])
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
            ->paginate()
            ->withQueryString();

        return view('wnip::podcasts.index', ['series' => $series, 'filters' => $request->only('q')]);
    }

    public function show(PodcastSeries $podcastSeries): View
    {
        $this->authorize('view', $podcastSeries);

        $canManage = request()->user()?->can('update', $podcastSeries) ?? false;

        $podcastSeries->load([
            'episodes' => function ($query) use ($canManage) {
                $query->orderByDesc('published_at');

                if (!$canManage) {
                    $query->where('is_public', true)->whereNotNull('published_at');
                }
            },
            'recordings',
        ])->loadCount('followers');

        $isFollowed = request()->user()
            ? $podcastSeries->followers()->where('user_id', request()->user()->getAuthIdentifier())->exists()
            : false;

        Analytics::track('podcast_series_viewed', [
            'series_id' => $podcastSeries->id,
            'user_id' => request()->user()?->getAuthIdentifier(),
        ]);

        return view('wnip::podcasts.series_show', [
            'series' => $podcastSeries,
            'isFollowed' => $isFollowed,
        ]);
    }

    public function episode(PodcastSeries $podcastSeries, PodcastEpisode $podcastEpisode): View
    {
        if ($podcastEpisode->podcast_series_id !== $podcastSeries->getKey()) {
            abort(404);
        }

        $this->authorize('view', $podcastSeries);

        $podcastEpisode->load(['series', 'recordings']);

        $relatedEpisodes = $podcastSeries->episodes()
            ->where('id', '!=', $podcastEpisode->id)
            ->orderByDesc('published_at')
            ->limit(5)
            ->get();

        Analytics::track('podcast_episode_viewed', [
            'series_id' => $podcastSeries->id,
            'episode_id' => $podcastEpisode->id,
            'user_id' => request()->user()?->getAuthIdentifier(),
        ]);

        return view('wnip::podcasts.episode_show', [
            'episode' => $podcastEpisode,
            'series' => $podcastSeries,
            'relatedEpisodes' => $relatedEpisodes,
        ]);
    }

    public function live(PodcastSeries $podcastSeries): View
    {
        $this->authorize('update', $podcastSeries);

        $upcoming = $podcastSeries->episodes()
            ->whereNull('published_at')
            ->orderBy('scheduled_for')
            ->first();

        $session = [
            'id' => $upcoming?->getKey() ?? $podcastSeries->getKey(),
            'title' => $upcoming?->title ?? $podcastSeries->title,
            'scheduled_for' => $upcoming?->scheduled_for,
            'guests' => data_get($upcoming?->metadata, 'guests', []),
        ];

        Analytics::track('podcast_live_shell_opened', [
            'series_id' => $podcastSeries->id,
            'episode_id' => $upcoming?->id,
            'user_id' => request()->user()?->getAuthIdentifier(),
        ]);

        return view('wnip::podcasts.live', [
            'series' => $podcastSeries,
            'session' => $session,
        ]);
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
        } elseif (!$shouldFollow && $isFollowing) {
            $podcastSeries->followers()->detach($userId);
            Analytics::track('podcast_series_unfollowed', ['series_id' => $podcastSeries->id, 'user_id' => $userId]);
        }

        return response()->json([
            'followed' => $shouldFollow,
            'followers_count' => $podcastSeries->followers()->count(),
        ]);
    }
}
