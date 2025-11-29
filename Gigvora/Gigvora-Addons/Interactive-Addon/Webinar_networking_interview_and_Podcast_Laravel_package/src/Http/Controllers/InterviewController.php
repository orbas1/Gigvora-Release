<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Interview;
use Jobi\WebinarNetworkingInterviewPodcast\Models\InterviewScore;
use Jobi\WebinarNetworkingInterviewPodcast\Models\InterviewSlot;
use Jobi\WebinarNetworkingInterviewPodcast\Support\Analytics\Analytics;

class InterviewController extends Controller
{
    use AuthorizesRequests;

    public function index(): JsonResponse
    {
        return response()->json(Interview::query()->with(['slots', 'scores', 'host'])->paginate());
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Interview::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:10',
            'is_panel' => 'boolean',
            'location' => 'nullable|string',
            'metadata' => 'array',
        ]);

        $interview = Interview::create(array_merge($validated, [
            'host_id' => $request->user()->getAuthIdentifier(),
        ]));

        Analytics::track('interview_scheduled', ['interview_id' => $interview->id, 'host_id' => $interview->host_id]);

        return response()->json($interview, 201);
    }

    public function show(Interview $interview): JsonResponse
    {
        $this->authorize('view', $interview);
        return response()->json($interview->load(['slots', 'scores', 'host']));
    }

    public function update(Request $request, Interview $interview): JsonResponse
    {
        $this->authorize('update', $interview);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'scheduled_at' => 'sometimes|date',
            'duration_minutes' => 'sometimes|integer|min:10',
            'is_panel' => 'boolean',
            'location' => 'nullable|string',
            'metadata' => 'array',
        ]);

        $interview->update($validated);

        return response()->json($interview);
    }

    public function addSlot(Request $request, Interview $interview): JsonResponse
    {
        $this->authorize('update', $interview);

        $validated = $request->validate([
            'interviewer_id' => 'required|integer',
            'interviewee_id' => 'required|integer',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'meeting_link' => 'nullable|string',
            'metadata' => 'array',
        ]);

        $slot = $interview->slots()->create($validated);

        Analytics::track('interview_joined', [
            'interview_id' => $interview->id,
            'interview_slot_id' => $slot->id,
        ]);

        return response()->json($slot, 201);
    }

    public function score(Request $request, Interview $interview, InterviewSlot $interviewSlot): JsonResponse
    {
        $this->authorize('score', $interview);

        $validated = $request->validate([
            'criteria' => 'required|array',
            'scores' => 'required|array',
            'comments' => 'nullable|string',
        ]);

        $score = InterviewScore::create([
            'interview_id' => $interview->id,
            'interview_slot_id' => $interviewSlot->id,
            'interviewer_id' => $request->user()->getAuthIdentifier(),
            'criteria' => $validated['criteria'],
            'scores' => $validated['scores'],
            'comments' => $validated['comments'] ?? null,
        ]);

        Analytics::track('interview_scored', [
            'interview_id' => $interview->id,
            'interview_slot_id' => $interviewSlot->id,
            'interviewer_id' => $request->user()->getAuthIdentifier(),
        ]);

        return response()->json($score, 201);
    }
}

