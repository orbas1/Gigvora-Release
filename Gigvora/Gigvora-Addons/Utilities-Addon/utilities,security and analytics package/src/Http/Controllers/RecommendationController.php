<?php

namespace ProNetwork\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ProNetwork\Services\RecommendationService;

class RecommendationController extends Controller
{
    public function __construct(protected RecommendationService $service)
    {
    }

    public function index(Request $request)
    {
        return response()->json($this->service->suggestConnections($request->user()->id));
    }
}
