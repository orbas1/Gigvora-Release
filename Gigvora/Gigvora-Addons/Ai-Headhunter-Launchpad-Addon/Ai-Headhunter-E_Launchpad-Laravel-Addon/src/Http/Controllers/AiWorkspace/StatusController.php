<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Controllers\AiWorkspace;

use Gigvora\TalentAi\Domain\AiWorkspace\Models\AiSession;
use Gigvora\TalentAi\Domain\AiWorkspace\Models\AiSubscriptionPlan;
use Gigvora\TalentAi\Domain\AiWorkspace\Models\AiUsageAggregate;
use Gigvora\TalentAi\Domain\AiWorkspace\Services\AiBillingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class StatusController extends Controller
{
    public function __construct(private AiBillingService $billing)
    {
    }

    protected function ensureEnabled(): void
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.ai_workspace.enabled'), 403);
    }

    public function sessions(Request $request): JsonResponse
    {
        $this->ensureEnabled();

        $sessions = AiSession::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->limit(50)
            ->get();

        return response()->json(['sessions' => $sessions]);
    }

    public function usage(Request $request): JsonResponse
    {
        $this->ensureEnabled();

        $usage = AiUsageAggregate::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('period_start')
            ->limit(30)
            ->get();

        return response()->json(['usage' => $usage]);
    }

    public function plans(): JsonResponse
    {
        $this->ensureEnabled();

        return response()->json(['plans' => AiSubscriptionPlan::all()]);
    }

    public function subscription(Request $request): JsonResponse
    {
        $this->ensureEnabled();

        return response()->json(['subscription' => $this->billing->getSubscription($request->user())]);
    }
}
