<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Interview;
use Jobi\WebinarNetworkingInterviewPodcast\Models\InterviewSlot;
use Jobi\WebinarNetworkingInterviewPodcast\Support\Analytics\Analytics;

class InterviewPageController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $interviews = Interview::query()
            ->with(['slots', 'scores', 'host'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('scheduled_at')
            ->paginate()
            ->withQueryString();

        return view('wnip::interviews.index', ['interviews' => $interviews, 'filters' => $request->only('q')]);
    }

    public function show(Interview $interview): View
    {
        $this->authorize('view', $interview);
        $interview->load(['slots', 'scores', 'host']);

        return view('wnip::interviews.show', ['interview' => $interview]);
    }

    public function waitingRoom(Interview $interview): View
    {
        $this->authorize('view', $interview);
        $interview->load('host');

        return view('wnip::interviews.waiting_room', ['interview' => $interview]);
    }

    public function score(Request $request, Interview $interview, InterviewSlot $interviewSlot)
    {
        $this->authorize('score', $interview);

        $validated = $request->validate([
            'criteria' => 'required|array',
            'scores' => 'required|array',
            'comments' => 'nullable|string',
        ]);

        $interview->scores()->create([
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

        return back()->with('status', 'Score submitted');
    }
}
