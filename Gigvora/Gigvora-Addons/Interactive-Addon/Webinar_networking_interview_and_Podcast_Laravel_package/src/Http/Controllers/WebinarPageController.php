<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Ticket;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Webinar;
use Jobi\WebinarNetworkingInterviewPodcast\Models\WebinarRegistration;
use Jobi\WebinarNetworkingInterviewPodcast\Support\Analytics\Analytics;

class WebinarPageController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): View
    {
        $query = Webinar::query()->with(['host'])->withCount('registrations');

        if ($request->boolean('upcoming')) {
            $query->where('starts_at', '>=', now());
        }

        if ($request->boolean('past')) {
            $query->where('ends_at', '<', now());
        }

        if ($search = $request->string('q')->toString()) {
            $query->where(function ($inner) use ($search) {
                $inner->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('paid')) {
            $paid = $request->string('paid')->toString() === '1';
            $query->where('is_paid', $paid);
        }

        $webinars = $query->orderBy('starts_at')->paginate()->withQueryString();

        return view('wnip::webinars.index', [
            'webinars' => $webinars,
            'filters' => $request->only(['q', 'upcoming', 'past', 'paid']),
        ]);
    }

    public function show(Request $request, Webinar $webinar): View
    {
        $this->authorize('view', $webinar);

        $webinar->load(['registrations', 'host', 'recordings']);

        $registration = null;
        if ($request->user()) {
            $registration = WebinarRegistration::where('webinar_id', $webinar->id)
                ->where('user_id', $request->user()->getAuthIdentifier())
                ->first();
        }

        return view('wnip::webinars.show', compact('webinar', 'registration'));
    }

    public function register(Request $request, Webinar $webinar): RedirectResponse
    {
        $this->authorize('view', $webinar);

        $validated = $request->validate([
            'ticket_id' => 'nullable|exists:' . (new Ticket())->getTable() . ',id',
        ]);

        WebinarRegistration::firstOrCreate([
            'webinar_id' => $webinar->id,
            'user_id' => $request->user()->getAuthIdentifier(),
        ], [
            'status' => 'registered',
            'ticket_id' => $validated['ticket_id'] ?? null,
        ]);

        Analytics::track('webinar_registered', ['webinar_id' => $webinar->id, 'user_id' => $request->user()->getAuthIdentifier()]);

        return back()->with('status', 'Registered for webinar.');
    }

    public function waitingRoom(Webinar $webinar): View
    {
        $this->authorize('view', $webinar);
        $webinar->load('host');

        return view('wnip::webinars.waiting_room', ['webinar' => $webinar]);
    }

    public function live(Webinar $webinar): View
    {
        $this->authorize('view', $webinar);
        return view('wnip::webinars.live', ['webinar' => $webinar]);
    }
}
