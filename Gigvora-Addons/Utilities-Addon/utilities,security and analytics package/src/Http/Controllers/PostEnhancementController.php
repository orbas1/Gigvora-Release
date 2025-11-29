<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ProNetwork\Models\PostEnhancement;
use ProNetwork\Services\AnalyticsService;
use ProNetwork\Services\PostEnhancementService;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\CreateCelebratePostRequest;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\CreatePollRequest;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\CreateThreadRequest;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\ResharePostRequest;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\VotePollRequest;

class PostEnhancementController extends Controller
{
    public function __construct(
        protected PostEnhancementService $postEnhancementService,
        protected AnalyticsService $analyticsService
    ) {
    }

    public function createPoll(Request $request)
    {
        $this->analyticsService->track('post.poll.create.viewed', [], $request->user());

        return view('pro_network::posts.polls.create');
    }

    public function storePoll(CreatePollRequest $request)
    {
        $data = $request->validated();

        $enhancement = $this->postEnhancementService->saveEnhancement((int) $data['post_id'], 'poll', [
            'question' => $data['question'],
            'options' => $data['options'],
            'expires_at' => $data['expires_at'] ?? null,
        ]);

        $this->analyticsService->track('post.poll.created', ['post_id' => $enhancement->post_id], $request->user());

        return response()->json([
            'post_id' => $enhancement->post_id,
            'enhancement' => $enhancement,
        ], 201);
    }

    public function votePoll(VotePollRequest $request, int $pollId)
    {
        $data = $request->validated();
        $enhancement = PostEnhancement::where('post_id', $pollId)->where('type', 'poll')->firstOrFail();

        $payload = $enhancement->payload ?? [];
        $options = collect($payload['options'] ?? []);
        $selected = $data['option'];

        if ($options->isNotEmpty() && ! $options->contains($selected)) {
            abort(422, 'Invalid poll option.');
        }

        $votes = collect($payload['votes'] ?? []);
        $votes[$selected] = ($votes[$selected] ?? 0) + 1;
        $payload['votes'] = $votes->all();

        $updated = $this->postEnhancementService->saveEnhancement($pollId, 'poll', $payload);

        $this->analyticsService->track('post.poll.voted', [
            'post_id' => $pollId,
            'option' => $selected,
        ], $request->user());

        return response()->json([
            'post_id' => $updated->post_id,
            'votes' => $updated->payload['votes'] ?? [],
        ]);
    }

    public function createThread(Request $request)
    {
        $this->analyticsService->track('post.thread.create.viewed', [], $request->user());

        return view('pro_network::posts.threads.create');
    }

    public function storeThread(CreateThreadRequest $request)
    {
        $data = $request->validated();

        $enhancement = $this->postEnhancementService->saveEnhancement((int) $data['post_id'], 'thread', [
            'title' => $data['title'],
            'content' => $data['content'],
        ]);

        $this->analyticsService->track('post.thread.created', ['post_id' => $enhancement->post_id], $request->user());

        return response()->json([
            'post_id' => $enhancement->post_id,
            'enhancement' => $enhancement,
        ], 201);
    }

    public function reshare(ResharePostRequest $request)
    {
        $data = $request->validated();

        $enhancement = $this->postEnhancementService->saveEnhancement((int) $data['post_id'], 'reshare', [
            'source_post_id' => $data['source_post_id'],
            'comment' => $data['comment'] ?? null,
            'audience' => $data['audience'] ?? null,
        ]);

        $this->analyticsService->track('post.reshared', [
            'post_id' => $enhancement->post_id,
            'source_post_id' => $data['source_post_id'],
        ], $request->user());

        return response()->json([
            'post_id' => $enhancement->post_id,
            'enhancement' => $enhancement,
        ]);
    }

    public function createCelebrate(Request $request)
    {
        $this->analyticsService->track('post.celebrate.create.viewed', [], $request->user());

        return view('pro_network::posts.celebrate.create');
    }

    public function storeCelebrate(CreateCelebratePostRequest $request)
    {
        $data = $request->validated();

        $enhancement = $this->postEnhancementService->saveEnhancement((int) $data['post_id'], 'celebrate', [
            'title' => $data['title'],
            'message' => $data['message'] ?? null,
            'date' => $data['date'] ?? null,
        ]);

        $this->analyticsService->track('post.celebrate.created', ['post_id' => $enhancement->post_id], $request->user());

        return response()->json([
            'post_id' => $enhancement->post_id,
            'enhancement' => $enhancement,
        ], 201);
    }
}
