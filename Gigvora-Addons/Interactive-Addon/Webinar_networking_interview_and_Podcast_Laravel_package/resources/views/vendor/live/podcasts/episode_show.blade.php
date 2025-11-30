@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="text-sm uppercase tracking-wide text-indigo-500 font-semibold mb-2">{{ __('Podcast Episode') }}</p>
        <h1 class="live-header__title">{{ $episode['title'] ?? __('Episode Player') }}</h1>
        <p class="live-header__subtitle">{{ __('Listen live, scrub through bookmarks, and review show notes.') }}</p>
    </div>
@endsection

@section('live-content')
@php
    $episodeData = $episode ?? [
        'title' => 'Episode 1: Shipping fast',
        'description' => 'Conversation on agile shipping.',
        'guests' => 'Kim & Lee',
        'show_notes' => 'Links and resources go here.',
    ];
@endphp
<div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_320px]" id="podcast-episode" data-episode-id="{{ $episodeData['id'] ?? 1 }}">
    <div class="gv-card space-y-4">
        <div class="flex items-center gap-3">
            <button class="gv-btn gv-btn-primary" id="audio-toggle">{{ get_phrase('Play') }}</button>
            <input type="range" class="flex-1 accent-[var(--gv-color-primary-600)]" id="audio-progress" min="0" max="100" value="0">
            <div class="text-sm text-[var(--gv-color-neutral-500)]" id="audio-time">00:00 / 00:00</div>
            <select class="gv-input w-28" id="audio-speed">
                <option value="0.75">0.75x</option>
                <option value="1" selected>1x</option>
                <option value="1.25">1.25x</option>
                <option value="1.5">1.5x</option>
            </select>
        </div>
        <div>
            <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-1">{{ $episodeData['title'] }}</h1>
            <p class="text-sm text-[var(--gv-color-neutral-500)] mb-3">{{ get_phrase('Guests: :guests', ['guests' => $episodeData['guests'] ?? get_phrase('TBD')]) }}</p>
            <p class="text-sm text-[var(--gv-color-neutral-700)] mb-4">{{ $episodeData['description'] }}</p>
            <div>
                <h3 class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-2">{{ get_phrase('Show notes') }}</h3>
                <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">{{ $episodeData['show_notes'] }}</p>
            </div>
        </div>
    </div>
    <aside class="gv-card space-y-2">
        <h4 class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('More episodes') }}</h4>
        <ul class="space-y-1 text-sm" id="related-episodes">
            <li><a href="#" class="text-[var(--gv-color-primary-600)]">{{ get_phrase('Episode 2: Growth') }}</a></li>
            <li><a href="#" class="text-[var(--gv-color-primary-600)]">{{ get_phrase('Episode 3: Hiring') }}</a></li>
        </ul>
    </aside>
</div>
@endsection

@push('scripts')
    <script type="module" src="{{ mix('js/live/podcastPlayer.js') }}"></script>
@endpush
