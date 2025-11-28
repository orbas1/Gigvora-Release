<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Controllers\Headhunters;

use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterInterview;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterPipelineItem;
use Gigvora\TalentAi\Domain\Headhunters\Services\HeadhunterInterviewService;
use Gigvora\TalentAi\Http\Requests\Headhunters\InterviewRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class InterviewController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private HeadhunterInterviewService $service)
    {
    }

    public function store(InterviewRequest $request, HeadhunterPipelineItem $pipelineItem): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.headhunters.enabled'), 403);
        $this->authorize('update', $pipelineItem);

        $interview = $this->service->scheduleInterview($pipelineItem, $request->user()->id, $request->input('scheduled_at'), $request->input('summary'));

        return response()->json($interview);
    }

    public function update(InterviewRequest $request, HeadhunterInterview $interview): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.headhunters.enabled'), 403);
        $this->authorize('update', $interview->pipelineItem);

        $interview = $this->service->updateInterview($interview, $request->validated());

        return response()->json($interview);
    }
}
