<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Controllers\Launchpad;

use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadApplication;
use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadProgramme;
use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadTask;
use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadApplicationTaskProgress;
use Gigvora\TalentAi\Domain\Launchpad\Services\LaunchpadApplicationService;
use Gigvora\TalentAi\Domain\Shared\Enums\LaunchpadApplicationStatus;
use Gigvora\TalentAi\Http\Requests\Launchpad\ApplicationRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ApplicationController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private LaunchpadApplicationService $service)
    {
    }

    public function show(LaunchpadApplication $application): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.launchpad.enabled'), 403);
        $this->authorize('view', $application);

        return response()->json(['application' => $application->load(['programme.tasks', 'interviews', 'taskProgress'])]);
    }

    public function store(ApplicationRequest $request, LaunchpadProgramme $programme): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.launchpad.enabled'), 403);

        $application = $this->service->apply($programme, $request->user()->id, $request->validated());
        $this->authorize('view', $application);

        return response()->json($application);
    }

    public function updateStatus(ApplicationRequest $request, LaunchpadApplication $application): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.launchpad.enabled'), 403);
        $this->authorize('update', $application);

        $status = LaunchpadApplicationStatus::from($request->input('status'));
        $application = $this->service->updateStatus($application, $status);

        return response()->json($application);
    }

    public function updateTask(Request $request, LaunchpadApplication $application, LaunchpadTask $task): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.launchpad.enabled'), 403);
        $this->authorize('view', $application);

        $payload = $request->validate(['completed' => 'required|boolean']);

        $progress = LaunchpadApplicationTaskProgress::updateOrCreate(
            [
                'launchpad_application_id' => $application->id,
                'launchpad_task_id' => $task->id,
            ],
            [
                'completed_at' => $payload['completed'] ? now() : null,
            ]
        );

        if (!$payload['completed']) {
            $progress->delete();
        }

        return response()->json([
            'application' => $application->load(['programme.tasks', 'taskProgress']),
            'task' => $task,
        ]);
    }
}
