@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="text-sm uppercase tracking-wide text-indigo-500 font-semibold mb-2">{{ __('Networking Lounges') }}</p>
        <h1 class="live-header__title">{{ __('Networking Sessions') }}</h1>
        <p class="live-header__subtitle">{{ __('Drop into speed networking, mentorship circles, and curated meetups.') }}</p>
    </div>
@endsection

@section('live-content')
@php
    use Illuminate\Support\Str;
@endphp

<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                {{ get_phrase('Networking sessions') }}
            </h1>
            <p class="gv-muted mb-0">
                {{ get_phrase('Speed networking, mentorship circles, and curated meetups.') }}
            </p>
        </div>
        <a class="gv-btn gv-btn-primary" href="{{ route('wnip.networking.index', ['create' => 1]) }}">
            {{ get_phrase('Host a session') }}
        </a>
    </div>

    <form method="get" class="gv-card space-y-3">
        <div class="grid gap-3 lg:grid-cols-4">
            <label class="space-y-1 w-full">
                <span class="gv-label">{{ get_phrase('Search sessions') }}</span>
                <input type="text" name="q" class="gv-input" value="{{ $filters['q'] ?? '' }}"
                    placeholder="{{ get_phrase('Title or topic') }}">
            </label>
            <label class="space-y-1 w-full">
                <span class="gv-label">{{ get_phrase('Type') }}</span>
                <select name="type" class="gv-input">
                    <option value="">{{ get_phrase('Any') }}</option>
                    <option value="speed" @selected(($filters['type'] ?? '') === 'speed')>{{ get_phrase('Speed networking') }}</option>
                    <option value="group" @selected(($filters['type'] ?? '') === 'group')>{{ get_phrase('Group networking') }}</option>
                </select>
            </label>
            <label class="space-y-1 w-full">
                <span class="gv-label">{{ get_phrase('Pricing') }}</span>
                <select name="pricing" class="gv-input">
                    <option value="">{{ get_phrase('Any') }}</option>
                    <option value="free" @selected(($filters['pricing'] ?? '') === 'free')>{{ get_phrase('Free') }}</option>
                    <option value="paid" @selected(($filters['pricing'] ?? '') === 'paid')>{{ get_phrase('Paid') }}</option>
                </select>
            </label>
            <label class="space-y-1 w-full">
                <span class="gv-label">{{ get_phrase('Window') }}</span>
                <select name="window" class="gv-input">
                    <option value="">{{ get_phrase('All sessions') }}</option>
                    <option value="upcoming" @selected(($filters['window'] ?? '') === 'upcoming')>{{ get_phrase('Upcoming') }}</option>
                    <option value="past" @selected(($filters['window'] ?? '') === 'past')>{{ get_phrase('Past') }}</option>
                </select>
            </label>
        </div>
        <div class="flex justify-end">
            <button class="gv-btn gv-btn-primary" type="submit">{{ get_phrase('Filter') }}</button>
        </div>
    </form>

    <div class="grid gap-4 lg:grid-cols-2 xl:grid-cols-3">
        @forelse($sessions as $session)
            @php
                $isFull = $session->is_full;
                $tag = $isFull ? get_phrase('Waitlist') : ($session->is_paid ? get_phrase('Paid') : get_phrase('Free'));
                $statusLabel = $session->is_live ? get_phrase('Live') : ucfirst($session->status ?? 'Scheduled');
                $capacity = $session->capacity
                    ? get_phrase(':taken / :capacity seats', [
                        'taken' => $session->participants->whereIn('status', ['registered', 'confirmed'])->count(),
                        'capacity' => $session->capacity,
                    ])
                    : trans_choice('{0}Be first to register|{1}1 participant|[2,*]:count participants', $session->participants->count(), ['count' => $session->participants->count()]);
                $topics = collect($session->topics ?? [])->take(2)->implode(', ');
            @endphp
            @include('wnip::components.event_card', [
                'title' => $session->title,
                'description' => Str::limit($session->description, 140),
                'meta' => collect([
                    optional($session->starts_at)->format('M j • g:i A'),
                    $session->template === 'group' ? get_phrase('Group networking') : get_phrase('Speed networking'),
                ])->filter()->implode(' • '),
                'caption' => $topics ?: get_phrase('Rotations every :seconds s', ['seconds' => $session->rotation_interval ?? 60]),
                'status' => $statusLabel,
                'tag' => $tag,
                'detail' => $capacity,
                'secondary' => collect([
                    $session->is_paid
                        ? get_phrase('Ticket from :amount', ['amount' => currency_format($session->price ?? 0)])
                        : get_phrase('Free to join'),
                    $session->capacity && $isFull
                        ? get_phrase('Waitlist enabled')
                        : ($session->remaining_capacity ? get_phrase(':count seats left', ['count' => $session->remaining_capacity]) : null),
                    $session->round_count
                        ? get_phrase(':count rotations • :interval s', ['count' => $session->round_count, 'interval' => $session->round_duration ?? $session->rotation_interval])
                        : get_phrase('Rotation ready'),
                ])->filter()->take(2)->implode(' • '),
                'href' => route('wnip.networking.show', $session),
                'cta' => $session->is_live ? get_phrase('Join now') : get_phrase('View session'),
            ])
        @empty
            <div class="lg:col-span-2 xl:col-span-3">
                <div class="gv-empty">
                    {{ get_phrase('No networking sessions yet.') }}
                </div>
            </div>
        @endforelse
    </div>

    <div>
        {{ $sessions->links() }}
    </div>
</div>
@endsection
