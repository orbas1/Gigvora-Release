<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Controllers\AiWorkspace;

use Gigvora\TalentAi\Domain\AiWorkspace\Services\AiWorkspaceService;
use Gigvora\TalentAi\Http\Requests\AiWorkspace\AiToolRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class ToolController extends Controller
{
    public function __construct(private AiWorkspaceService $service)
    {
    }

    protected function ensureEnabled(): void
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.ai_workspace.enabled'), 403);
    }

    public function cvWriter(AiToolRequest $request): JsonResponse
    {
        $this->ensureEnabled();
        return response()->json($this->service->cvWriter($request->user(), $request->validated()));
    }

    public function outreach(AiToolRequest $request): JsonResponse
    {
        $this->ensureEnabled();
        return response()->json($this->service->outreachComposer($request->user(), $request->validated()));
    }

    public function socialCalendar(AiToolRequest $request): JsonResponse
    {
        $this->ensureEnabled();
        return response()->json($this->service->socialCalendar($request->user(), $request->validated()));
    }

    public function coach(AiToolRequest $request): JsonResponse
    {
        $this->ensureEnabled();
        return response()->json($this->service->careerCoach($request->user(), $request->validated()));
    }

    public function repurpose(AiToolRequest $request): JsonResponse
    {
        $this->ensureEnabled();
        return response()->json($this->service->repurpose($request->user(), $request->validated()));
    }

    public function interviewPrep(AiToolRequest $request): JsonResponse
    {
        $this->ensureEnabled();
        return response()->json($this->service->interviewPrep($request->user(), $request->validated()));
    }

    public function imageCanvas(AiToolRequest $request): JsonResponse
    {
        $this->ensureEnabled();
        return response()->json($this->service->imageCanvas($request->user(), $request->validated()));
    }

    public function writer(AiToolRequest $request): JsonResponse
    {
        $this->ensureEnabled();
        return response()->json($this->service->writer($request->user(), $request->validated()));
    }

    public function marketingBot(AiToolRequest $request): JsonResponse
    {
        $this->ensureEnabled();
        return response()->json($this->service->marketingBot($request->user(), $request->validated()));
    }
}
