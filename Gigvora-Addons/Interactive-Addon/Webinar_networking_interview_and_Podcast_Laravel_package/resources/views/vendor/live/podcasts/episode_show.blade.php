@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="text-sm uppercase tracking-wide text-indigo-500 font-semibold mb-2">{{ __('Podcast Episode') }}</p>
        <h1 class="live-header__title">{{ $episode->title ?? __('Episode Player') }}</h1>
        <p class="live-header__subtitle">{{ __('Listen live, scrub through bookmarks, and review show notes.') }}</p>
    </div>
@endsection

@section('live-content')
<div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_320px]" id="podcast-episode" data-episode-id="{{ $episode->id }}"
    data-analytics-endpoint="{{ route('wnip.podcasts.playback', [$series, $episode]) }}">
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
        <audio id="podcast-audio" preload="metadata" @if($episode->audio_path) src="{{ $episode->audio_path }}" @endif></audio>
        <div>
            <div class="flex flex-col gap-1 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-wide text-[var(--gv-color-neutral-500)] mb-1">{{ $series->title }}</p>
                    <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-1">{{ $episode->title }}</h1>
                    <p class="text-xs text-[var(--gv-color-neutral-500)] mb-3">{{ $episode->published_at?->format('M j, Y • g:i A') ?? get_phrase('Draft') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="gv-pill">{{ get_phrase('Duration: :minutes min', ['minutes' => $episode->duration ?? get_phrase('TBD')]) }}</span>
                    <a class="gv-btn gv-btn-ghost" href="{{ route('wnip.podcasts.series', $series) }}">{{ get_phrase('Back to series') }}</a>
                </div>
            </div>
            <p class="text-sm text-[var(--gv-color-neutral-700)] mb-4">{{ $episode->description }}</p>
            <div class="grid gap-4 md:grid-cols-2">
                <div>
                    <h3 class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-2">{{ get_phrase('Guests') }}</h3>
                    <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">{{ data_get($episode->metadata, 'guests', get_phrase('TBD')) }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-2">{{ get_phrase('Show notes') }}</h3>
                    <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">{{ data_get($episode->metadata, 'show_notes', get_phrase('Links and resources go here.')) }}</p>
                </div>
            </div>
        </div>
    </div>
    <aside class="gv-card space-y-2">
        <h4 class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('More episodes') }}</h4>
        <ul class="space-y-1 text-sm" id="related-episodes">
            @forelse($relatedEpisodes as $related)
                <li>
                    <a href="{{ route('wnip.podcasts.episode', [$series, $related]) }}" class="text-[var(--gv-color-primary-600)]">
                        {{ $related->title }}
                    </a>
                </li>
            @empty
                <li class="text-[var(--gv-color-neutral-500)]">{{ get_phrase('No other episodes yet.') }}</li>
            @endforelse
        </ul>
    </aside>
</div>
@endsection

@push('scripts')
    <script type="module" src="{{ mix('js/live/podcastPlayer.js') }}"></script>
@endpush
