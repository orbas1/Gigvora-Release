<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Controllers;

use Illuminate\Routing\Controller;
use ProNetwork\Models\ProfessionalProfile;
use ProNetwork\Models\ProfileReactionScore;
use ProNetwork\Services\ReactionsService;
use ProNetwork\Support\Enums\ReactionType;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\DislikeRequest;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\ReactRequest;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\UndislikeRequest;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\UnreactRequest;

class ReactionsController extends Controller
{
    public function __construct(protected ReactionsService $reactionsService)
    {
    }

    public function react(ReactRequest $request)
    {
        $data = $request->validated();
        $modelClass = $data['reactable_type'];
        $reactable = $modelClass::findOrFail($data['reactable_id']);

        $reaction = $this->reactionsService->react(
            $reactable,
            $request->user()->id,
            ReactionType::from($data['type'])
        );

        return response()->json([
            'reaction' => $reaction,
        ], 201);
    }

    public function unreact(UnreactRequest $request)
    {
        $data = $request->validated();
        $modelClass = $data['reactable_type'];
        $reactable = $modelClass::findOrFail($data['reactable_id']);

        $this->reactionsService->remove($reactable, $request->user()->id);

        return response()->json([
            'message' => 'Reaction removed.',
        ]);
    }

    public function dislike(DislikeRequest $request)
    {
        $data = $request->validated();
        $modelClass = $data['reactable_type'];
        $reactable = $modelClass::findOrFail($data['reactable_id']);

        $reaction = $this->reactionsService->react($reactable, $request->user()->id, ReactionType::DISLIKE);

        return response()->json([
            'reaction' => $reaction,
        ], 201);
    }

    public function undislike(UndislikeRequest $request)
    {
        $data = $request->validated();
        $modelClass = $data['reactable_type'];
        $reactable = $modelClass::findOrFail($data['reactable_id']);

        $this->reactionsService->remove($reactable, $request->user()->id);

        return response()->json([
            'message' => 'Dislike removed.',
        ]);
    }

    public function profileScore(int $userId)
    {
        $profile = ProfessionalProfile::where('user_id', $userId)->firstOrFail();
        $this->reactionsService->aggregate($profile);

        $score = ProfileReactionScore::firstOrCreate(['user_id' => $userId]);

        return response()->json([
            'user_id' => $userId,
            'like_score' => $score->like_score,
            'dislike_count' => $score->dislike_count,
            'reaction_breakdown' => $score->reaction_breakdown,
        ]);
    }
}
