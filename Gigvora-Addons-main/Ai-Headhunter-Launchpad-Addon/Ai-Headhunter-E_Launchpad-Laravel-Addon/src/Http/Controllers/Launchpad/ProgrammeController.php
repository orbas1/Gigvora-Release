<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Controllers\Launchpad;

use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadProgramme;
use Gigvora\TalentAi\Domain\Launchpad\Services\LaunchpadProgrammeService;
use Gigvora\TalentAi\Http\Requests\Launchpad\ProgrammeRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ProgrammeController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private LaunchpadProgrammeService $service)
    {
    }

    public function store(ProgrammeRequest $request): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.launchpad.enabled'), 403);
        $payload = $request->validated();
        $payload['creator_id'] = $request->user()->id;
        $programme = $this->service->createProgramme($payload);
        $this->service->attachTasks($programme, $payload['tasks'] ?? []);

        return response()->json($programme->load('tasks'));
    }

    public function update(ProgrammeRequest $request, LaunchpadProgramme $programme): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.launchpad.enabled'), 403);
        $this->authorize('update', $programme);
        $programme = $this->service->updateProgramme($programme, $request->validated());
        $this->service->attachTasks($programme, $request->input('tasks', []));

        return response()->json($programme->load('tasks'));
    }

    public function publish(LaunchpadProgramme $programme): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.launchpad.enabled'), 403);
        $this->authorize('update', $programme);

        return response()->json($this->service->publishProgramme($programme));
    }

    public function close(LaunchpadProgramme $programme): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.launchpad.enabled'), 403);
        $this->authorize('update', $programme);

        return response()->json($this->service->closeProgramme($programme));
    }
}
