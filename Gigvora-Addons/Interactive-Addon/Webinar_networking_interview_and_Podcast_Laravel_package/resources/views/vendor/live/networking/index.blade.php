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
        <label class="space-y-1 w-full md:w-1/2">
            <span class="gv-label">{{ get_phrase('Search sessions') }}</span>
            <input type="text" name="q" class="gv-input" value="{{ $filters['q'] ?? '' }}"
                placeholder="{{ get_phrase('Title or topic') }}">
        </label>
        <div class="flex justify-end">
            <button class="gv-btn gv-btn-primary" type="submit">{{ get_phrase('Filter') }}</button>
        </div>
    </form>

    <div class="grid gap-4 lg:grid-cols-2 xl:grid-cols-3">
        @forelse($sessions as $session)
            @include('wnip::components.event_card', [
                'title' => $session->title,
                'description' => Str::limit($session->description, 140),
                'meta' => optional($session->starts_at)->format('M j • g:i A'),
                'caption' => get_phrase('Rotations every :seconds s', ['seconds' => $session->rotation_interval ?? 60]),
                'status' => ucfirst($session->status ?? 'Scheduled'),
                'tag' => $session->is_paid ? get_phrase('Paid') : get_phrase('Open'),
                'detail' => trans_choice('{0}Be first to register|{1}1 participant|[2,*]:count participants', $session->participants->count(), ['count' => $session->participants->count()]),
                'secondary' => collect([
                    $session->is_paid
                        ? get_phrase('Ticket from :amount', ['amount' => currency_format($session->price ?? 0)])
                        : get_phrase('Free to join'),
                    $session->rotation_count
                        ? get_phrase(':count rotations • :interval s', ['count' => $session->rotation_count, 'interval' => $session->rotation_interval])
                        : get_phrase('Rotation ready'),
                    $session->ends_at
                        ? get_phrase('Ends :time', ['time' => $session->ends_at->format('g:i A')])
                        : null,
                ])->filter()->take(2)->implode(' • '),
                'href' => route('wnip.networking.show', $session),
                'cta' => get_phrase('View session'),
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
