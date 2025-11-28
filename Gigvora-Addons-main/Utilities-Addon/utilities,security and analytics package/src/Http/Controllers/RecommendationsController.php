<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ProNetwork\Models\CompanyProfile;
use ProNetwork\Services\RecommendationService;

class RecommendationsController extends Controller
{
    public function __construct(protected RecommendationService $recommendationService)
    {
    }

    public function people(Request $request)
    {
        $result = $this->recommendationService->recommendConnections($request->user()->id);

        return response()->json([
            'type' => $result->type,
            'items' => $result->items,
        ]);
    }

    public function companies(Request $request)
    {
        $result = $this->recommendationService->recommendByTags(CompanyProfile::class);

        return response()->json([
            'type' => $result->type,
            'items' => $result->items,
        ]);
    }

    public function groups(Request $request)
    {
        $result = $this->recommendationService->recommendByTags('group');

        return response()->json([
            'type' => $result->type,
            'items' => $result->items,
        ]);
    }

    public function content(Request $request)
    {
        $result = $this->recommendationService->trending('content');

        return response()->json([
            'type' => $result->type,
            'items' => $result->items,
        ]);
    }
}
