<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AdsPlacementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdsPlacementController extends Controller
{
    public function __invoke(Request $request, AdsPlacementService $placements): JsonResponse
    {
        $data = $request->validate([
            'surface' => 'required|string',
            'keywords' => 'nullable|array',
            'keywords.*' => 'string',
            'query' => 'nullable|string',
            'country' => 'nullable|string',
        ]);

        $ads = $placements->placementsForContext($data, $request->user());

        return response()->json([
            'surface' => $data['surface'],
            'placements' => $ads,
        ]);
    }
}
