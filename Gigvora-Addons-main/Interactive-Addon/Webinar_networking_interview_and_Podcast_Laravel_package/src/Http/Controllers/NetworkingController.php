<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingParticipant;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingSession;
use Jobi\WebinarNetworkingInterviewPodcast\Support\Analytics\Analytics;

class NetworkingController extends Controller
{
    use AuthorizesRequests;

    public function index(): JsonResponse
    {
        return response()->json(NetworkingSession::query()->with(['participants', 'host'])->paginate());
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', NetworkingSession::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_seconds' => 'required|integer|min:120',
            'rotation_interval' => 'required|integer|min:60',
            'starts_at' => 'required|date',
            'is_paid' => 'boolean',
            'price' => 'nullable|numeric|min:0',
            'metadata' => 'array',
        ]);

        $session = NetworkingSession::create(array_merge($validated, [
            'host_id' => $request->user()->getAuthIdentifier(),
            'status' => 'scheduled',
        ]));

        Analytics::track('networking_session_created', ['session_id' => $session->id, 'host_id' => $session->host_id]);

        return response()->json($session, 201);
    }

    public function show(NetworkingSession $networkingSession): JsonResponse
    {
        $this->authorize('view', $networkingSession);
        return response()->json($networkingSession->load(['participants', 'host']));
    }

    public function update(Request $request, NetworkingSession $networkingSession): JsonResponse
    {
        $this->authorize('update', $networkingSession);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'duration_seconds' => 'sometimes|integer|min:120',
            'rotation_interval' => 'sometimes|integer|min:60',
            'starts_at' => 'sometimes|date',
            'status' => 'nullable|string',
            'is_paid' => 'boolean',
            'price' => 'nullable|numeric|min:0',
            'metadata' => 'array',
        ]);

        $networkingSession->update($validated);

        return response()->json($networkingSession);
    }

    public function register(Request $request, NetworkingSession $networkingSession): JsonResponse
    {
        $this->authorize('view', $networkingSession);

        $participant = NetworkingParticipant::firstOrCreate([
            'networking_session_id' => $networkingSession->id,
            'user_id' => $request->user()->getAuthIdentifier(),
        ], [
            'rotation_position' => $networkingSession->participants()->count() + 1,
            'status' => 'registered',
            'joined_at' => now(),
        ]);

        Analytics::track('networking_session_joined', [
            'session_id' => $networkingSession->id,
            'user_id' => $request->user()->getAuthIdentifier(),
        ]);

        return response()->json($participant, 201);
    }

    public function rotate(NetworkingSession $networkingSession): JsonResponse
    {
        $this->authorize('update', $networkingSession);

        $participants = $networkingSession->participants()->orderBy('rotation_position')->get();
        $total = $participants->count();

        foreach ($participants as $index => $participant) {
            $partner = $participants[($index + 1) % $total] ?? null;
            $participant->update([
                'current_partner_id' => $partner?->user_id,
                'rotation_position' => $partner ? $partner->rotation_position : $participant->rotation_position,
            ]);
        }

        $networkingSession->update(['status' => 'in_rotation']);

        Analytics::track('networking_rotation_completed', ['session_id' => $networkingSession->id, 'count' => $total]);

        return response()->json(['message' => 'Rotation updated']);
    }
}

