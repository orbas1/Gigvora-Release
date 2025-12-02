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
use Jobi\WebinarNetworkingInterviewPodcast\Services\WebinarRegistrationService;
use Jobi\WebinarNetworkingInterviewPodcast\Support\Ads\AdsBridge;
use Jobi\WebinarNetworkingInterviewPodcast\Support\Analytics\Analytics;

class WebinarPageController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected WebinarRegistrationService $registrations)
    {
    }

    public function index(Request $request): View
    {
        $query = Webinar::query()->with(['host'])->withCount(['registrations', 'recordings']);

        if ($request->boolean('upcoming')) {
            $query->where('starts_at', '>=', now());
        }

        if ($request->boolean('past')) {
            $query->where('ends_at', '<', now());
        }

        if ($request->boolean('mine') && $request->user()) {
            $query->where('host_id', $request->user()->getAuthIdentifier());
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

        if ($request->boolean('replays')) {
            $query->whereHas('recordings');
        }

        if ($request->boolean('reminders')) {
            $query->whereNotNull('metadata->reminder_offsets');
        }

        if ($request->date('starts_after')) {
            $query->whereDate('starts_at', '>=', $request->date('starts_after'));
        }

        if ($request->date('starts_before')) {
            $query->whereDate('starts_at', '<=', $request->date('starts_before'));
        }

        $webinars = $query->orderBy('starts_at')->paginate()->withQueryString();

        return view('wnip::webinars.index', [
            'webinars' => $webinars,
            'filters' => $request->only(['q', 'upcoming', 'past', 'paid', 'reminders', 'replays', 'mine', 'starts_after', 'starts_before']),
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

        $canViewRecordings = $request->user()?->can('accessReplay', $webinar) ?? ! $webinar->is_paid;

        return view('wnip::webinars.show', [
            'webinar' => $webinar,
            'registration' => $registration,
            'canViewRecordings' => $canViewRecordings,
            'sponsorships' => AdsBridge::placementsFor('live_overlay', $webinar->id),
        ]);
    }

    public function register(Request $request, Webinar $webinar): RedirectResponse
    {
        $this->authorize('view', $webinar);

        $validated = $request->validate([
            'ticket_id' => 'nullable|exists:' . (new Ticket())->getTable() . ',id',
        ]);

        $this->registrations->register(
            $webinar,
            $request->user()->getAuthIdentifier(),
            $validated['ticket_id'] ?? null,
        );

        Analytics::track('webinar_registered', ['webinar_id' => $webinar->id, 'user_id' => $request->user()->getAuthIdentifier()]);

        return back()->with('status', 'Registered for webinar.');
    }

    public function waitingRoom(Webinar $webinar): View
    {
        $this->authorize('view', $webinar);
        $webinar->load(['host', 'registrations']);

        return view('wnip::webinars.waiting_room', ['webinar' => $webinar]);
    }

    public function live(Webinar $webinar): View
    {
        $this->authorize('view', $webinar);
        if ($userId = optional(request()->user())->getAuthIdentifier()) {
            $this->registrations->markAttendance($webinar, $userId);
        }
        return view('wnip::webinars.live', ['webinar' => $webinar]);
    }
}
