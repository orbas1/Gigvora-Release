<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Controllers\Headhunters;

use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterMandate;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterProfile;
use Gigvora\TalentAi\Domain\Headhunters\Services\HeadhunterService;
use Gigvora\TalentAi\Http\Requests\Headhunters\MandateRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MandateController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private HeadhunterService $service)
    {
    }

    public function index(Request $request): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.headhunters.enabled'), 403);

        $mandates = HeadhunterMandate::query()
            ->with(['profile'])
            ->withCount(['pipelineItems'])
            ->whereHas('profile', fn ($query) => $query->where('user_id', $request->user()->id))
            ->latest()
            ->paginate(15);

        return response()->json($mandates);
    }

    public function show(HeadhunterMandate $mandate): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.headhunters.enabled'), 403);
        $this->authorize('view', $mandate);

        return response()->json(['mandate' => $mandate->load(['profile', 'pipelineItems.candidate'])]);
    }

    public function store(MandateRequest $request, HeadhunterProfile $profile): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.headhunters.enabled'), 403);
        $this->authorize('view', $profile);

        $mandate = $this->service->createMandate($profile, $request->validated());

        return response()->json($mandate);
    }

    public function update(MandateRequest $request, HeadhunterMandate $mandate): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.headhunters.enabled'), 403);
        $this->authorize('update', $mandate);

        $mandate = $this->service->updateMandate($mandate, $request->validated());

        return response()->json($mandate);
    }
}
