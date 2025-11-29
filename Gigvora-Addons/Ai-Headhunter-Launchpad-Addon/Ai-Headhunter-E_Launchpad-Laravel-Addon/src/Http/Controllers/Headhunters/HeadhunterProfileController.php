<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Http\Controllers\Headhunters;

use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterProfile;
use Gigvora\TalentAi\Domain\Headhunters\Services\HeadhunterService;
use Gigvora\TalentAi\Http\Requests\Headhunters\HeadhunterProfileRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HeadhunterProfileController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private HeadhunterService $service)
    {
    }

    public function show(Request $request): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.headhunters.enabled'), 403);

        $profile = HeadhunterProfile::query()
            ->withCount(['mandates', 'candidates'])
            ->with(['mandates' => fn ($query) => $query->withCount('pipelineItems')->latest()])
            ->where('user_id', $request->user()->id)
            ->first();

        return response()->json(['profile' => $profile]);
    }

    public function store(HeadhunterProfileRequest $request): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.headhunters.enabled'), 403);

        $profile = $this->service->applyForHeadhunter($request->user()->id, $request->validated());
        $this->authorize('view', $profile);

        return response()->json($profile);
    }

    public function update(HeadhunterProfileRequest $request, HeadhunterProfile $profile): JsonResponse
    {
        abort_unless(config('gigvora_talent_ai.enabled') && config('gigvora_talent_ai.modules.headhunters.enabled'), 403);
        $this->authorize('update', $profile);

        $profile->fill($request->validated());
        $profile->save();

        return response()->json($profile);
    }
}
