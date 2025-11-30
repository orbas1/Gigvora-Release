<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UtilitiesQuickToolsService;
use Illuminate\Http\Request;

class UtilitiesQuickToolsController extends Controller
{
    public function __invoke(Request $request, UtilitiesQuickToolsService $service)
    {
        abort_unless($request->user(), 401);

        if (! (bool) config('pro_network_utilities_security_analytics.features.utilities_quick_tools')) {
            abort(404);
        }

        $context = $request->string('context')->toString() ?: null;
        $contextKey = $context ?: $service->detectContext($request);
        $actions = $service->actionsFor($contextKey);

        return response()->json([
            'context' => $contextKey,
            'label' => $service->labelFor($contextKey),
            'description' => $service->descriptionFor($contextKey),
            'actions' => collect($actions)->map(function (array $action) {
                return [
                    'id' => $action['id'],
                    'label' => $action['label'],
                    'description' => $action['description'],
                    'href' => $action['href'],
                    'icon' => $action['icon'],
                    'icon_key' => $action['icon_key'],
                ];
            })->values(),
        ]);
    }
}


