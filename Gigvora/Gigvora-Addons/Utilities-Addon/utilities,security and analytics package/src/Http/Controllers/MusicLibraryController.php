<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Controllers;

use Illuminate\Routing\Controller;
use ProNetwork\Services\MusicLibraryService;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\MusicSearchRequest;

class MusicLibraryController extends Controller
{
    public function __construct(protected MusicLibraryService $musicLibraryService)
    {
    }

    public function index()
    {
        $tracks = $this->musicLibraryService->list();

        return response()->json([
            'tracks' => $tracks,
        ]);
    }

    public function search(MusicSearchRequest $request)
    {
        $data = $request->validated();
        $tracks = $this->musicLibraryService->search($data['query']);

        return response()->json([
            'query' => $data['query'],
            'tracks' => $tracks,
        ]);
    }
}
