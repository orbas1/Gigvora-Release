<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Controllers\Headhunters;

use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterMandate;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterPipelineItem;
use Gigvora\TalentAi\Domain\Headhunters\Services\HeadhunterPipelineService;
use Gigvora\TalentAi\Domain\Shared\Enums\HeadhunterPipelineStage;
use Gigvora\TalentAi\Http\Requests\Headhunters\PipelineMoveRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class PipelineController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private HeadhunterPipelineService $service)
    {
    }

    public function store(PipelineMoveRequest $request, HeadhunterMandate $mandate): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.headhunters.enabled'), 403);
        $this->authorize('view', $mandate);

        $item = $this->service->attachToPipeline($mandate, (int) $request->input('candidate_id'), $request->input('stage', HeadhunterPipelineStage::Sourced->value));

        return response()->json($item);
    }

    public function move(PipelineMoveRequest $request, HeadhunterPipelineItem $pipelineItem): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.headhunters.enabled'), 403);
        $this->authorize('update', $pipelineItem);

        $item = $this->service->moveStage($pipelineItem, HeadhunterPipelineStage::from($request->input('stage')), $request->input('notes'));

        return response()->json($item);
    }
}
