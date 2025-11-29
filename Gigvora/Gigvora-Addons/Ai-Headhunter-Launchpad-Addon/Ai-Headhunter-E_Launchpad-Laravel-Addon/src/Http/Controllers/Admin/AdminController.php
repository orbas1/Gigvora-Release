<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Controllers\Admin;

use Gigvora\TalentAi\Domain\AiWorkspace\Models\AiSubscriptionPlan;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): JsonResponse
    {
        $this->authorize('manage_talent_ai');
        $config = config('gigvora_talent_ai');

        return response()->json([
            'config' => $config,
            'plans' => AiSubscriptionPlan::all(),
        ]);
    }

    public function storePlan(Request $request): JsonResponse
    {
        $this->authorize('manage_talent_ai');
        $data = $request->validate([
            'name' => ['required', 'string'],
            'slug' => ['required', 'string'],
            'limits' => ['array'],
            'price' => ['numeric'],
        ]);

        $plan = AiSubscriptionPlan::updateOrCreate(['slug' => $data['slug']], $data);

        return response()->json($plan);
    }
}
