<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Controllers\Launchpad;

use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadApplication;
use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadInterview;
use Gigvora\TalentAi\Domain\Launchpad\Services\LaunchpadApplicationService;
use Gigvora\TalentAi\Http\Requests\Launchpad\InterviewRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class InterviewController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private LaunchpadApplicationService $service)
    {
    }

    public function store(InterviewRequest $request, LaunchpadApplication $application): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.launchpad.enabled'), 403);
        $this->authorize('update', $application);

        $interview = $application->interviews()->create([
            'scheduled_by' => $request->user()->id,
            'scheduled_at' => $request->input('scheduled_at'),
            'status' => 'scheduled',
            'notes' => $request->input('notes'),
        ]);

        return response()->json($interview);
    }

    public function update(InterviewRequest $request, LaunchpadInterview $interview): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.launchpad.enabled'), 403);
        $this->authorize('update', $interview->application);

        $interview->fill($request->validated());
        $interview->save();

        return response()->json($interview);
    }
}
