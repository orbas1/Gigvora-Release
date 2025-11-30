<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\Navigation\NavigationBuilder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function __invoke(Request $request, NavigationBuilder $builder): JsonResponse
    {
        return response()->json([
            'data' => $builder->build($request->user()),
            'meta' => [
                'generated_at' => now()->toIso8601String(),
            ],
        ]);
    }
}


