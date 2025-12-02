<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingParticipant;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingSession;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Ticket;
use Jobi\WebinarNetworkingInterviewPodcast\Support\Analytics\Analytics;
use Jobi\WebinarNetworkingInterviewPodcast\Services\NetworkingContactService;
use Jobi\WebinarNetworkingInterviewPodcast\Services\NetworkingPairingService;

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
            'metadata.template' => 'nullable|in:speed,group',
            'metadata.rounds' => 'nullable|integer|min:1',
            'metadata.round_duration' => 'nullable|integer|min:30',
            'metadata.capacity' => 'nullable|integer|min:1',
            'metadata.max_per_round' => 'nullable|integer|min:1',
            'metadata.topics' => 'array',
            'metadata.topics.*' => 'string',
            'metadata.waitlist_enabled' => 'boolean',
            'metadata.coupons' => 'array',
            'metadata.coupons.*.code' => 'required_with:metadata.coupons|string',
            'metadata.coupons.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'metadata.coupons.*.discount_amount' => 'nullable|numeric|min:0',
            'metadata.coupons.*.valid_from' => 'nullable|date',
            'metadata.coupons.*.valid_until' => 'nullable|date',
            'metadata.coupons.*.usage_limit' => 'nullable|integer|min:1',
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

        $previousCapacity = $networkingSession->capacity;

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
            'metadata.template' => 'nullable|in:speed,group',
            'metadata.rounds' => 'nullable|integer|min:1',
            'metadata.round_duration' => 'nullable|integer|min:30',
            'metadata.capacity' => 'nullable|integer|min:1',
            'metadata.max_per_round' => 'nullable|integer|min:1',
            'metadata.topics' => 'array',
            'metadata.topics.*' => 'string',
            'metadata.waitlist_enabled' => 'boolean',
            'metadata.coupons' => 'array',
            'metadata.coupons.*.code' => 'required_with:metadata.coupons|string',
            'metadata.coupons.*.discount_percent' => 'nullable|numeric|min:0|max:100',
            'metadata.coupons.*.discount_amount' => 'nullable|numeric|min:0',
            'metadata.coupons.*.valid_from' => 'nullable|date',
            'metadata.coupons.*.valid_until' => 'nullable|date',
            'metadata.coupons.*.usage_limit' => 'nullable|integer|min:1',
        ]);

        $networkingSession->update($validated);

        if ($networkingSession->capacity && $networkingSession->capacity > (int) ($previousCapacity ?? 0)) {
            $promoted = $networkingSession->promoteWaitlist();

            if ($promoted > 0) {
                Analytics::track('networking_waitlist_promoted', [
                    'session_id' => $networkingSession->id,
                    'count' => $promoted,
                ]);
            }
        }

        return response()->json($networkingSession);
    }

    public function register(Request $request, NetworkingSession $networkingSession): JsonResponse
    {
        $this->authorize('view', $networkingSession);

        $couponCode = $request->string('coupon')->trim();
        $userId = $request->user()->getAuthIdentifier();

        $participant = DB::transaction(function () use ($networkingSession, $couponCode, $userId) {
            $participant = NetworkingParticipant::where('networking_session_id', $networkingSession->id)
                ->where('user_id', $userId)
                ->first();

            $capacity = $networkingSession->capacity;
            $registeredCount = $networkingSession->confirmedParticipants()->count();
            $isFull = $capacity && $registeredCount >= $capacity;

            if ($isFull && ! $networkingSession->waitlist_enabled) {
                throw ValidationException::withMessages([
                    'capacity' => __('This networking session is full.'),
                ]);
            }

            $status = $isFull ? 'waitlisted' : 'registered';
            $rotationPosition = $participant?->rotation_position ?? ($registeredCount + 1);

            $ticketMeta = [];
            $finalPrice = $networkingSession->price ?? 0;

            if ($networkingSession->is_paid && ($networkingSession->price ?? 0) > 0) {
                $coupon = $this->resolveCoupon($networkingSession, $couponCode);
                if ($coupon) {
                    $ticketMeta['coupon_code'] = $coupon['code'];
                    $ticketMeta['discount'] = Arr::only($coupon, ['discount_percent', 'discount_amount']);

                    if ($coupon['discount_percent']) {
                        $finalPrice = $finalPrice * (1 - ($coupon['discount_percent'] / 100));
                    }

                    if ($coupon['discount_amount']) {
                        $finalPrice = max(0, $finalPrice - $coupon['discount_amount']);
                    }
                }

                $ticket = Ticket::firstOrCreate([
                    'ticketable_type' => NetworkingSession::class,
                    'ticketable_id' => $networkingSession->id,
                    'user_id' => $userId,
                ], [
                    'price' => $finalPrice,
                    'currency' => 'USD',
                    'status' => $status === 'waitlisted' ? 'pending' : 'confirmed',
                    'metadata' => $ticketMeta,
                ]);

                if ($ticket->status === 'pending' && $status !== 'waitlisted') {
                    $ticket->update(['status' => 'confirmed']);
                }
            }

            if (! $participant) {
                $participant = NetworkingParticipant::create([
                    'networking_session_id' => $networkingSession->id,
                    'user_id' => $userId,
                    'rotation_position' => $rotationPosition,
                    'status' => $status,
                    'joined_at' => now(),
                ]);
            } else {
                $participant->update([
                    'status' => $status,
                    'rotation_position' => $participant->rotation_position ?? $rotationPosition,
                    'joined_at' => $participant->joined_at ?: now(),
                ]);
            }

            return $participant->fresh();
        });

        $analyticsEvent = $participant->status === 'waitlisted'
            ? 'networking_waitlist_joined'
            : 'networking_session_joined';

        Analytics::track($analyticsEvent, [
            'session_id' => $networkingSession->id,
            'user_id' => $userId,
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

    public function pairings(
        Request $request,
        NetworkingSession $networkingSession,
        NetworkingPairingService $pairings
    ): JsonResponse {
        $this->authorize('update', $networkingSession);

        $validated = $request->validate([
            'round' => 'nullable|integer|min:1',
        ]);

        $round = $validated['round'] ?? $pairings->nextRoundNumber($networkingSession);
        $assignments = $pairings->generateRound($networkingSession, $round);

        Analytics::track('networking_round_completed', [
            'session_id' => $networkingSession->id,
            'round' => $round,
            'participants' => $assignments->unique('participant_id')->count(),
        ]);

        return response()->json([
            'round' => $round,
            'assignments' => $assignments->load(['participant.user', 'partner.user']),
        ]);
    }

    public function promoteWaitlist(NetworkingSession $networkingSession): JsonResponse
    {
        $this->authorize('update', $networkingSession);

        $promoted = $networkingSession->promoteWaitlist();

        if ($promoted > 0) {
            Analytics::track('networking_waitlist_promoted', [
                'session_id' => $networkingSession->id,
                'count' => $promoted,
            ]);
        }

        return response()->json(['promoted' => $promoted]);
    }

    public function exchangeContact(
        Request $request,
        NetworkingSession $networkingSession,
        NetworkingContactService $contacts
    ): JsonResponse {
        $this->authorize('view', $networkingSession);

        $validated = $request->validate([
            'partner_id' => 'required|integer|exists:users,id',
            'notes' => 'nullable|string',
            'follow_up_at' => 'nullable|date',
            'starred' => 'boolean',
        ]);

        $userId = $request->user()->getAuthIdentifier();
        $participant = $contacts->guardParticipant($networkingSession, $userId);

        if (! $participant) {
            throw ValidationException::withMessages([
                'registration' => __('Only registered participants can exchange contacts.'),
            ]);
        }

        $contacts->enforceRateLimit($networkingSession, $userId);

        $partnerParticipant = $networkingSession->participants()
            ->with('user')
            ->where('user_id', $validated['partner_id'])
            ->first();

        if (! $partnerParticipant) {
            throw ValidationException::withMessages([
                'partner_id' => __('Selected partner is not in this session.'),
            ]);
        }

        $metadata = [
            'partner_name' => optional($partnerParticipant->user)->name,
            'session_title' => $networkingSession->title,
        ];

        $exchange = $contacts->exchange(
            $networkingSession,
            $userId,
            (int) $validated['partner_id'],
            $validated['notes'] ?? null,
            $validated['follow_up_at'] ?? null,
            (bool) ($validated['starred'] ?? false),
            $metadata
        );

        Analytics::track('networking_contact_exchanged', [
            'session_id' => $networkingSession->id,
            'user_id' => $userId,
            'partner_id' => $validated['partner_id'],
            'starred' => (bool) ($validated['starred'] ?? false),
        ]);

        return response()->json($exchange->load(['partner']));
    }

    protected function resolveCoupon(NetworkingSession $networkingSession, string $code = ''): ?array
    {
        if (! $code) {
            return null;
        }

        $coupons = collect($networkingSession->metadata['coupons'] ?? [])
            ->map(function ($coupon) {
                $coupon['code'] = strtoupper($coupon['code'] ?? '');

                return $coupon;
            })
            ->filter(fn ($coupon) => $coupon['code']);

        $coupon = $coupons->firstWhere('code', strtoupper($code));

        if (! $coupon) {
            throw ValidationException::withMessages([
                'coupon' => __('Invalid coupon code'),
            ]);
        }

        $now = now();
        $validFrom = isset($coupon['valid_from']) ? Carbon::parse($coupon['valid_from']) : null;
        $validUntil = isset($coupon['valid_until']) ? Carbon::parse($coupon['valid_until']) : null;

        if ($validFrom && $now->lt($validFrom)) {
            throw ValidationException::withMessages([
                'coupon' => __('Coupon not yet active'),
            ]);
        }

        if ($validUntil && $now->gt($validUntil)) {
            throw ValidationException::withMessages([
                'coupon' => __('Coupon expired'),
            ]);
        }

        if ($coupon['usage_limit'] ?? false) {
            $usage = Ticket::where('ticketable_type', NetworkingSession::class)
                ->where('ticketable_id', $networkingSession->id)
                ->where('metadata->coupon_code', strtoupper($code))
                ->count();

            if ($usage >= $coupon['usage_limit']) {
                throw ValidationException::withMessages([
                    'coupon' => __('Coupon limit reached'),
                ]);
            }
        }

        return $coupon;
    }
}

