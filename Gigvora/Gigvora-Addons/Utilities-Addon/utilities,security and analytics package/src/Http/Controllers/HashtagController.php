<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Controllers;

use Illuminate\Routing\Controller;
use ProNetwork\Models\Hashtag;
use ProNetwork\Services\HashtagService;
use ProNetwork\Services\SearchTagsDomain;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\HashtagSearchRequest;

class HashtagController extends Controller
{
    public function __construct(
        protected HashtagService $hashtagService,
        protected SearchTagsDomain $searchTagsDomain
    ) {
    }

    public function show(string $hashtag)
    {
        $tag = $this->hashtagService->find($hashtag);
        $assignments = $tag ? $this->searchTagsDomain->byTag($tag->tag) : collect();

        return view('pro_network::hashtags.show', [
            'hashtag' => $tag,
            'assignments' => $assignments,
        ]);
    }

    public function index()
    {
        $trending = Hashtag::orderByDesc('usage_count')->limit(20)->get();
        $normalized = $this->searchTagsDomain->normalize($trending->pluck('tag')->all());

        return response()->json([
            'trending' => $trending,
            'normalized_tags' => $normalized,
        ]);
    }

    public function search(HashtagSearchRequest $request)
    {
        $data = $request->validated();
        $normalizedTags = $this->searchTagsDomain->normalize($data['query']);
        $needle = is_array($normalizedTags) ? ($normalizedTags[0] ?? '') : (string) $normalizedTags;

        $results = Hashtag::query()
            ->where('normalized', 'like', "%{$needle}%")
            ->orderByDesc('usage_count')
            ->limit(30)
            ->get();

        return response()->json([
            'query' => $data['query'],
            'normalized' => $needle,
            'results' => $results,
        ]);
    }
}
