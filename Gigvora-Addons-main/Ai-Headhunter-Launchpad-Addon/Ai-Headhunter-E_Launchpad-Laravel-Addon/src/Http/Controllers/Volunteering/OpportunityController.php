<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Controllers\Volunteering;

use Gigvora\TalentAi\Domain\Volunteering\Models\VolunteeringOpportunity;
use Gigvora\TalentAi\Domain\Volunteering\Services\VolunteeringService;
use Gigvora\TalentAi\Http\Requests\Volunteering\OpportunityRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class OpportunityController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private VolunteeringService $service)
    {
    }

    protected function ensureEnabled(): void
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.volunteering.enabled'), 403);
    }

    public function store(OpportunityRequest $request): JsonResponse
    {
        $this->ensureEnabled();
        $payload = $request->validated();
        $payload['creator_id'] = $request->user()->id;
        $opportunity = $this->service->createOpportunity($payload);

        return response()->json($opportunity);
    }

    public function update(OpportunityRequest $request, VolunteeringOpportunity $opportunity): JsonResponse
    {
        $this->ensureEnabled();
        $this->authorize('update', $opportunity);

        $opportunity = $this->service->updateOpportunity($opportunity, $request->validated());

        return response()->json($opportunity);
    }

    public function publish(VolunteeringOpportunity $opportunity): JsonResponse
    {
        $this->ensureEnabled();
        $this->authorize('update', $opportunity);

        return response()->json($this->service->publishOpportunity($opportunity));
    }

    public function close(VolunteeringOpportunity $opportunity): JsonResponse
    {
        $this->ensureEnabled();
        $this->authorize('update', $opportunity);

        return response()->json($this->service->closeOpportunity($opportunity));
    }
}
