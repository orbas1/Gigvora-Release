<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\Analytics\AnalyticsEventPublisher;
use App\Support\Navigation\NavigationBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function __invoke(Request $request, NavigationBuilder $builder, AnalyticsEventPublisher $analytics): JsonResponse
    {
        $navigation = $builder->build($request->user());

        $analytics->publish('navigation', 'rendered', [
            'sections' => array_keys($navigation),
            'route' => $request->route()?->getName(),
        ], $request->user());

        return response()->json([
            'data' => $navigation,
            'meta' => [
                'generated_at' => now()->toIso8601String(),
            ],
        ]);
    }
}


