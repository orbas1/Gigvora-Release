<?php

namespace App\Http\Controllers;

use App\Models\Live_streamings;
use App\Models\Posts;
use App\Services\LiveEngagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LiveEngagementController extends Controller
{
    protected LiveEngagementService $service;

    public function __construct(LiveEngagementService $service)
    {
        $this->middleware(['auth', 'verified', 'user', 'activity'])->except('summary');
        $this->service = $service;
    }

    public function summary(int $postId): JsonResponse
    {
        $stream = $this->resolveStream($postId);
        return response()->json($this->service->summary($stream));
    }

    public function donate(Request $request, int $postId): JsonResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'message' => ['nullable', 'string', 'max:160'],
            'sticker' => ['nullable', 'string', 'max:50'],
        ]);

        $stream = $this->resolveStream($postId);
        $this->service->recordDonation($stream, $request->user(), (float) $request->input('amount'), [
            'message' => $request->input('message'),
            'sticker' => $request->input('sticker'),
        ]);

        return response()->json([
            'status' => 'ok',
            'summary' => $this->service->summary($stream),
        ]);
    }

    public function react(Request $request, int $postId): JsonResponse
    {
        $request->validate([
            'reaction' => ['required', 'string', 'max:32'],
        ]);

        $stream = $this->resolveStream($postId);
        $this->service->recordReaction($stream, $request->user(), $request->input('reaction'));

        return response()->json(['status' => 'ok']);
    }

    public function question(Request $request, int $postId): JsonResponse
    {
        $request->validate([
            'question' => ['required', 'string', 'max:280'],
        ]);

        $stream = $this->resolveStream($postId);
        $this->service->submitQuestion($stream, $request->user(), $request->input('question'));

        return response()->json(['status' => 'ok']);
    }

    protected function resolveStream(int $postId): Live_streamings
    {
        $post = Posts::where('post_id', $postId)->firstOrFail();

        $stream = Live_streamings::where('publisher', $post->publisher ?? 'post')
            ->where('publisher_id', $post->post_id)
            ->first();

        if (!$stream) {
            abort(404, 'Live session not found');
        }

        return $stream;
    }
}

