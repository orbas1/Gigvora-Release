<?php

namespace Advertisement\Http\Controllers;

use Advertisement\Services\KeywordPlannerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KeywordPlannerController
{
    public function __invoke(Request $request, KeywordPlannerService $service): JsonResponse
    {
        $data = $request->validate([
            'keywords' => 'required|array',
            'keywords.*' => 'required|string'
        ]);

        $prices = $service->priceKeywords($data['keywords']);

        return response()->json($prices);
    }
}
