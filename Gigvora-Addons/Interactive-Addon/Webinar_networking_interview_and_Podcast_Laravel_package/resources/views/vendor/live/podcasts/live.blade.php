@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="text-sm uppercase tracking-wide text-indigo-500 font-semibold mb-2">{{ __('Live Podcast') }}</p>
        <h1 class="live-header__title">{{ __('Live podcast recording') }}</h1>
        <p class="live-header__subtitle">{{ __('Record the show, manage guests, and capture highlights in real time.') }}</p>
    </div>
@endsection

@section('live-content')
<div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_320px]" id="podcast-live" data-session-id="{{ $session['id'] ?? 1 }}"
    data-series-id="{{ $series->id }}">
    <div class="gv-card space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-1">{{ $session['title'] ?? get_phrase('Recording session') }}</p>
                <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">{{ $session['scheduled_for']?->format('M j, Y • g:i A') ?? get_phrase('Realtime audio capture with guest controls') }}</p>
            </div>
            <span class="gv-pill gv-pill--danger" id="recording-status">{{ get_phrase('Live') }}</span>
        </div>
        <div class="bg-[var(--gv-color-neutral-900)] text-white text-center rounded-2xl py-16">
            {{ get_phrase('Audio/RTC container') }}
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button class="gv-btn gv-btn-primary" id="toggle-record">{{ get_phrase('Record / Stop') }}</button>
            <button class="gv-btn gv-btn-ghost" id="mute-guests">{{ get_phrase('Mute guests') }}</button>
            <span class="ml-auto text-sm text-[var(--gv-color-neutral-500)]" id="recording-timer">00:00</span>
        </div>
    </div>

    <aside class="space-y-4">
        <div class="gv-card space-y-2">
            <h3 class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Guests') }}</h3>
            <ul class="space-y-2" id="guest-list">
                @forelse($session['guests'] ?? [] as $guest)
                    <li class="flex items-center justify-between rounded-xl border border-[var(--gv-color-border)] p-3">
                        <span>{{ $guest['name'] ?? $guest }}</span>
                        <label class="inline-flex items-center gap-2 text-xs text-[var(--gv-color-neutral-500)]">
                            <input type="checkbox" class="h-4 w-4 accent-[var(--gv-color-primary-600)]" checked>
                            {{ get_phrase('On air') }}
                        </label>
                    </li>
                @empty
                    <li class="text-sm text-[var(--gv-color-neutral-500)]">{{ get_phrase('Guest list will populate after invites are sent.') }}</li>
                @endforelse
            </ul>
        </div>
        @include('wnip::components.notes_sidebar')
    </aside>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/podcastLive.js') }}"></script>
@endpush
