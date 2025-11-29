<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Controllers\Volunteering;

use Gigvora\TalentAi\Domain\Shared\Enums\VolunteeringApplicationStatus;
use Gigvora\TalentAi\Domain\Volunteering\Models\VolunteeringApplication;
use Gigvora\TalentAi\Domain\Volunteering\Models\VolunteeringOpportunity;
use Gigvora\TalentAi\Domain\Volunteering\Services\VolunteeringService;
use Gigvora\TalentAi\Http\Requests\Volunteering\ApplicationRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ApplicationController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private VolunteeringService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $this->ensureEnabled();

        $applications = VolunteeringApplication::query()
            ->where('user_id', $request->user()->id)
            ->with(['opportunity'])
            ->latest()
            ->get();

        return response()->json(['applications' => $applications]);
    }

    public function show(VolunteeringApplication $application): JsonResponse
    {
        $this->ensureEnabled();
        $this->authorize('view', $application);

        return response()->json(['application' => $application->load('opportunity')]);
    }

    protected function ensureEnabled(): void
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.volunteering.enabled'), 403);
    }

    public function store(ApplicationRequest $request, VolunteeringOpportunity $opportunity): JsonResponse
    {
        $this->ensureEnabled();
        $application = $this->service->apply($opportunity, $request->user()->id, $request->validated());

        return response()->json($application);
    }

    public function updateStatus(ApplicationRequest $request, VolunteeringApplication $application): JsonResponse
    {
        $this->ensureEnabled();
        $this->authorize('update', $application);

        $status = VolunteeringApplicationStatus::from($request->input('status'));
        $application = $this->service->updateApplicationStatus($application, $status);

        return response()->json($application);
    }
}
