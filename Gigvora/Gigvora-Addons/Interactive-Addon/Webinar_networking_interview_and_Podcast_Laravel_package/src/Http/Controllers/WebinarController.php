<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Ticket;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Webinar;
use Jobi\WebinarNetworkingInterviewPodcast\Models\WebinarRegistration;
use Jobi\WebinarNetworkingInterviewPodcast\Support\Analytics\Analytics;

class WebinarController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request): JsonResponse
    {
        $query = Webinar::query()->with('host')->withCount('registrations')->latest('starts_at');

        if ($request->boolean('upcoming')) {
            $query->where('starts_at', '>=', now());
        }

        return response()->json($query->paginate());
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
        ]);

        $webinar = Webinar::create(array_merge($validated, [
            'host_id' => $request->user()->getAuthIdentifier(),
            'status' => 'scheduled',
        ]));

        Analytics::track('webinar_created', ['webinar_id' => $webinar->id, 'host_id' => $webinar->host_id]);

        return response()->json($webinar, 201);
    }

    public function show(Webinar $webinar): JsonResponse
    {
        $this->authorize('view', $webinar);
        return response()->json($webinar->load(['registrations', 'host', 'recordings']));
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
        ]);

        $webinar->update($validated);

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

        $registration = WebinarRegistration::firstOrCreate([
            'webinar_id' => $webinar->id,
            'user_id' => $request->user()->getAuthIdentifier(),
        ], [
            'status' => 'registered',
            'ticket_id' => $validated['ticket_id'] ?? null,
        ]);

        Analytics::track('webinar_registered', ['webinar_id' => $webinar->id, 'user_id' => $request->user()->getAuthIdentifier()]);

        return response()->json($registration, 201);
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

