<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Controllers\Headhunters;

use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterCandidate;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterProfile;
use Gigvora\TalentAi\Domain\Headhunters\Services\HeadhunterService;
use Gigvora\TalentAi\Http\Requests\Headhunters\CandidateRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class CandidateController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private HeadhunterService $service)
    {
    }

    public function store(CandidateRequest $request, HeadhunterProfile $profile): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.headhunters.enabled'), 403);
        $this->authorize('view', $profile);

        $candidate = $this->service->createCandidate($profile, $request->validated());

        return response()->json($candidate);
    }

    public function update(CandidateRequest $request, HeadhunterCandidate $candidate): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.headhunters.enabled'), 403);
        $this->authorize('update', $candidate);

        $candidate = $this->service->updateCandidate($candidate, $request->validated());

        return response()->json($candidate);
    }
}
