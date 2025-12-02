<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Ticket;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Webinar;
use Jobi\WebinarNetworkingInterviewPodcast\Services\WebinarRegistrationService;
use Jobi\WebinarNetworkingInterviewPodcast\Support\Ads\AdsBridge;
use Jobi\WebinarNetworkingInterviewPodcast\Support\Analytics\Analytics;

class WebinarController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected WebinarRegistrationService $registrations)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $query = Webinar::query()
            ->with(['host'])
            ->withCount(['registrations', 'recordings'])
            ->latest('starts_at');

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

        if ($request->boolean('replays')) {
            $query->whereHas('recordings');
        }

        if ($request->boolean('reminders')) {
            $query->whereNotNull('metadata->reminder_offsets');
        }

        if ($request->boolean('mine') && $request->user()) {
            $query->where('host_id', $request->user()->getAuthIdentifier());
        }

        if ($request->date('starts_after')) {
            $query->whereDate('starts_at', '>=', $request->date('starts_after'));
        }

        if ($request->date('starts_before')) {
            $query->whereDate('starts_at', '<=', $request->date('starts_before'));
        }

        return response()->json($query->paginate($request->integer('per_page', 15)));
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Webinar::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
            'is_paid' => 'boolean',
            'price' => 'nullable|numeric|min:0',
            'waiting_room_message' => 'nullable|string',
            'stream_provider' => 'nullable|string',
            'rtmp_endpoint' => 'nullable|string',
            'metadata' => 'array',
            'capacity' => 'nullable|integer|min:1',
            'waitlist_enabled' => 'boolean',
        ]);

        $metadata = $this->mergeMetadata($validated);

        $webinar = Webinar::create(array_merge(Arr::except($validated, ['capacity', 'waitlist_enabled']), [
            'host_id' => $request->user()->getAuthIdentifier(),
            'status' => 'scheduled',
            'metadata' => $metadata,
        ]));

        Analytics::track('webinar_created', ['webinar_id' => $webinar->id, 'host_id' => $webinar->host_id]);

        return response()->json($webinar, 201);
    }

    public function show(Webinar $webinar): JsonResponse
    {
        $this->authorize('view', $webinar);

        $webinar->load(['registrations', 'host', 'recordings']);
        $canReplay = auth()->user()?->can('accessReplay', $webinar) ?? false;

        if (! $canReplay) {
            $webinar->setRelation('recordings', collect());
            $webinar->makeHidden(['recording_path']);
        }

        return response()->json([
            'data' => $webinar,
            'can_replay' => $canReplay,
            'sponsorships' => AdsBridge::placementsFor('live_overlay', $webinar->id),
        ]);
    }

    public function update(Request $request, Webinar $webinar): JsonResponse
    {
        $this->authorize('update', $webinar);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'starts_at' => 'sometimes|date',
            'ends_at' => 'sometimes|date|after:starts_at',
            'is_paid' => 'boolean',
            'price' => 'nullable|numeric|min:0',
            'waiting_room_message' => 'nullable|string',
            'stream_provider' => 'nullable|string',
            'rtmp_endpoint' => 'nullable|string',
            'status' => 'nullable|string',
            'metadata' => 'array',
            'capacity' => 'nullable|integer|min:1',
            'waitlist_enabled' => 'boolean',
        ]);

        $metadata = $this->mergeMetadata($validated, $webinar);

        $webinar->update(array_merge(Arr::except($validated, ['capacity', 'waitlist_enabled']), [
            'metadata' => $metadata,
        ]));

        return response()->json($webinar);
    }

    public function destroy(Webinar $webinar): JsonResponse
    {
        $this->authorize('delete', $webinar);
        $webinar->delete();

        return response()->json(['message' => 'Webinar deleted']);
    }

    public function register(Request $request, Webinar $webinar): JsonResponse
    {
        $this->authorize('view', $webinar);

        $validated = $request->validate([
            'ticket_id' => 'nullable|exists:' . (new Ticket())->getTable() . ',id',
        ]);

        $registration = $this->registrations->register(
            $webinar,
            $request->user()->getAuthIdentifier(),
            $validated['ticket_id'] ?? null,
        );

        Analytics::track('webinar_registered', ['webinar_id' => $webinar->id, 'user_id' => $request->user()->getAuthIdentifier()]);

        return response()->json($registration, 201);
    }

    public function unregister(Request $request, Webinar $webinar): JsonResponse
    {
        $this->authorize('view', $webinar);

        $this->registrations->unregister($webinar, $request->user()->getAuthIdentifier());

        return response()->json(['message' => __('Registration cancelled')]);
    }

    public function attend(Request $request, Webinar $webinar): JsonResponse
    {
        $this->authorize('view', $webinar);

        $registration = $this->registrations->markAttendance($webinar, $request->user()->getAuthIdentifier());

        Analytics::track('webinar_attended', ['webinar_id' => $webinar->id, 'user_id' => $request->user()->getAuthIdentifier()]);

        return response()->json($registration);
    }

    protected function mergeMetadata(array $validated, ?Webinar $webinar = null): array
    {
        $metadata = $validated['metadata'] ?? ($webinar?->metadata ?? []);

        if (array_key_exists('capacity', $validated)) {
            $metadata['capacity'] = $validated['capacity'];
        }

        if (array_key_exists('waitlist_enabled', $validated)) {
            $metadata['waitlist_enabled'] = (bool) $validated['waitlist_enabled'];
        }

        return $metadata;
    }

    public function toggleLive(Webinar $webinar): JsonResponse
    {
        $this->authorize('update', $webinar);
        $webinar->update([
            'is_live' => !$webinar->is_live,
            'status' => $webinar->is_live ? 'ended' : 'live',
        ]);

        $event = $webinar->is_live ? 'webinar_ended' : 'webinar_started';
        Analytics::track($event, ['webinar_id' => $webinar->id, 'host_id' => $webinar->host_id]);

        return response()->json($webinar);
    }
}

