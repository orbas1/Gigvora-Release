<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingParticipant;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingSession;
use Jobi\WebinarNetworkingInterviewPodcast\Support\Analytics\Analytics;

class NetworkingPageController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $sessions = NetworkingSession::query()
            ->with(['participants', 'host'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $search = $request->string('q')->toString();
                $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('starts_at')
            ->paginate()
            ->withQueryString();

        return view('wnip::networking.index', ['sessions' => $sessions, 'filters' => $request->only('q')]);
    }

    public function show(Request $request, NetworkingSession $networkingSession): View
    {
        $this->authorize('view', $networkingSession);
        $networkingSession->load(['participants', 'host']);

        $participant = null;
        if ($request->user()) {
            $participant = NetworkingParticipant::where('networking_session_id', $networkingSession->id)
                ->where('user_id', $request->user()->getAuthIdentifier())
                ->first();
        }

        return view('wnip::networking.show', [
            'session' => $networkingSession,
            'participant' => $participant,
        ]);
    }

    public function register(Request $request, NetworkingSession $networkingSession)
    {
        $this->authorize('view', $networkingSession);

        NetworkingParticipant::firstOrCreate([
            'networking_session_id' => $networkingSession->id,
            'user_id' => $request->user()->getAuthIdentifier(),
        ], [
            'status' => 'registered',
            'rotation_position' => $networkingSession->participants()->count() + 1,
            'joined_at' => now(),
        ]);

        Analytics::track('networking_session_joined', [
            'session_id' => $networkingSession->id,
            'user_id' => $request->user()->getAuthIdentifier(),
        ]);

        return back()->with('status', 'Registered for networking session.');
    }

    public function waitingRoom(NetworkingSession $networkingSession): View
    {
        $this->authorize('view', $networkingSession);
        $networkingSession->load('host');

        return view('wnip::networking.waiting_room', ['session' => $networkingSession]);
    }

    public function live(NetworkingSession $networkingSession): View
    {
        $this->authorize('view', $networkingSession);
        $networkingSession->load('participants');

        return view('wnip::networking.live', ['session' => $networkingSession]);
    }
}
