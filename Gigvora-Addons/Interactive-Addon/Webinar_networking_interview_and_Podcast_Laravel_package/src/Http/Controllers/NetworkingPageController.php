<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingParticipant;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingSession;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Ticket;
use Jobi\WebinarNetworkingInterviewPodcast\Support\Analytics\Analytics;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('metadata->template', $request->string('type')->toString());
            })
            ->when($request->filled('pricing'), function ($query) use ($request) {
                if ($request->string('pricing') === 'free') {
                    $query->where('is_paid', false);
                }

                if ($request->string('pricing') === 'paid') {
                    $query->where('is_paid', true);
                }
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->string('status')->toString());
            })
            ->when($request->filled('window'), function ($query) use ($request) {
                $window = $request->string('window')->toString();
                if ($window === 'upcoming') {
                    $query->where('starts_at', '>=', now());
                }
                if ($window === 'past') {
                    $query->where('starts_at', '<', now());
                }
            })
            ->orderBy('starts_at')
            ->paginate()
            ->withQueryString();

        return view('wnip::networking.index', ['sessions' => $sessions, 'filters' => $request->only('q', 'type', 'pricing', 'status', 'window')]);
    }

    public function show(Request $request, NetworkingSession $networkingSession): View
    {
        $this->authorize('view', $networkingSession);
        $networkingSession->load(['participants', 'host']);

        $participant = $this->resolveParticipant($request, $networkingSession, allowWaitlist: true);

        return view('wnip::networking.show', [
            'session' => $networkingSession,
            'participant' => $participant,
        ]);
    }

    public function register(Request $request, NetworkingSession $networkingSession)
    {
        $this->authorize('view', $networkingSession);

        $request->validate([
            'coupon' => 'nullable|string|max:32',
        ]);

        $userId = $request->user()->getAuthIdentifier();

        $participant = DB::transaction(function () use ($networkingSession, $userId, $request) {
            $existing = NetworkingParticipant::where('networking_session_id', $networkingSession->id)
                ->where('user_id', $userId)
                ->first();

            $capacity = $networkingSession->capacity;
            $registeredCount = $networkingSession->confirmedParticipants()->count();
            $isFull = $capacity && $registeredCount >= $capacity;

            if ($isFull && ! $networkingSession->waitlist_enabled) {
                throw ValidationException::withMessages(['capacity' => __('This networking session is full.')]);
            }

            $status = $isFull ? 'waitlisted' : 'registered';
            $rotationPosition = $existing?->rotation_position ?? ($registeredCount + 1);

            if ($networkingSession->is_paid && ($networkingSession->price ?? 0) > 0) {
                $ticketMeta = [];
                $finalPrice = $networkingSession->price ?? 0;
                $couponCode = $request->string('coupon')->trim();

                if ($couponCode) {
                    $coupon = $this->resolveCoupon($networkingSession, $couponCode);
                    $ticketMeta['coupon_code'] = $coupon['code'];
                    $ticketMeta['discount'] = Arr::only($coupon, ['discount_percent', 'discount_amount']);

                    if ($coupon['discount_percent']) {
                        $finalPrice = $finalPrice * (1 - ($coupon['discount_percent'] / 100));
                    }

                    if ($coupon['discount_amount']) {
                        $finalPrice = max(0, $finalPrice - $coupon['discount_amount']);
                    }
                }

                Ticket::firstOrCreate([
                    'ticketable_type' => NetworkingSession::class,
                    'ticketable_id' => $networkingSession->id,
                    'user_id' => $userId,
                ], [
                    'price' => $finalPrice,
                    'currency' => 'USD',
                    'status' => $status === 'waitlisted' ? 'pending' : 'confirmed',
                    'metadata' => $ticketMeta ?? [],
                ]);
            }

            if ($existing) {
                $existing->update([
                    'status' => $status,
                    'rotation_position' => $existing->rotation_position ?? $rotationPosition,
                    'joined_at' => $existing->joined_at ?: now(),
                ]);

                return $existing;
            }

            return NetworkingParticipant::create([
                'networking_session_id' => $networkingSession->id,
                'user_id' => $userId,
                'status' => $status,
                'rotation_position' => $rotationPosition,
                'joined_at' => now(),
            ]);
        });

        $analyticsEvent = $participant?->status === 'waitlisted'
            ? 'networking_waitlist_joined'
            : 'networking_session_joined';

        Analytics::track($analyticsEvent, [
            'session_id' => $networkingSession->id,
            'user_id' => $request->user()->getAuthIdentifier(),
        ]);

        $message = $participant?->status === 'waitlisted'
            ? __('Added to waitlist. You will be notified when a seat opens.')
            : __('Registered for networking session.');

        return back()->with('status', $message);
    }

    public function waitingRoom(NetworkingSession $networkingSession): View
    {
        $this->authorize('view', $networkingSession);
        $networkingSession->load('host');

        $participant = $this->resolveParticipant(request(), $networkingSession, allowWaitlist: true);
        if (! $participant) {
            return redirect()->route('wnip.networking.show', $networkingSession)
                ->withErrors(['registration' => __('Register to access the waiting room.')]);
        }

        return view('wnip::networking.waiting_room', [
            'session' => $networkingSession,
            'participant' => $participant,
        ]);
    }

    public function live(NetworkingSession $networkingSession): View
    {
        $this->authorize('view', $networkingSession);
        $networkingSession->load('participants');
        $participant = $this->resolveParticipant(request(), $networkingSession);

        if (! $participant) {
            return redirect()->route('wnip.networking.show', $networkingSession)
                ->withErrors(['registration' => __('Only registered ticket holders can join the live session.')]);
        }

        return view('wnip::networking.live', [
            'session' => $networkingSession,
            'participant' => $participant,
        ]);
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

    protected function resolveParticipant(Request $request, NetworkingSession $networkingSession, bool $allowWaitlist = false): ?NetworkingParticipant
    {
        if (! $request->user()) {
            return null;
        }

        $participant = NetworkingParticipant::where('networking_session_id', $networkingSession->id)
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->first();

        if (! $participant) {
            return null;
        }

        if (! $allowWaitlist && $participant->status === 'waitlisted') {
            return null;
        }

        return $participant;
    }
}
