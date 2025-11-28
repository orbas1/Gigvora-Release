<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Jobi\WebinarNetworkingInterviewPodcast\Models\PodcastSeries;

class PodcastPageController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $series = PodcastSeries::query()
            ->with(['episodes' => fn ($query) => $query->latest('published_at')])
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
        $podcastSeries->load(['episodes' => fn ($query) => $query->orderByDesc('published_at')]);

        return view('wnip::podcasts.show', ['series' => $podcastSeries]);
    }
}
