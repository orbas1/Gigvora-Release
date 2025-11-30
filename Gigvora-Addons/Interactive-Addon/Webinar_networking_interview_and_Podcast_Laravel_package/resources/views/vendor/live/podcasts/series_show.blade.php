@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="text-sm uppercase tracking-wide text-indigo-500 font-semibold mb-2">{{ __('Podcast Series') }}</p>
        <h1 class="live-header__title">{{ $series['title'] ?? __('Series overview') }}</h1>
        <p class="live-header__subtitle">{{ __('Follow hosts, binge episodes, and manage live tapings.') }}</p>
    </div>
@endsection

@section('live-content')
@php
    $seriesData = $series ?? [
        'title' => 'Product Stories',
        'description' => 'Behind the roadmap with top PMs.',
        'host' => 'Kim',
        'episodes' => [
            ['title' => 'Episode 1', 'duration' => '32m', 'date' => 'Apr 1'],
            ['title' => 'Episode 2', 'duration' => '28m', 'date' => 'Apr 8'],
        ],
    ];
@endphp
<div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_320px]" id="podcast-series"
    data-follow-url="{{ route('wnip.podcasts.follow', ['series' => $seriesData['id'] ?? 1]) ?? '#' }}">
    <div class="space-y-4">
        <div class="gv-card space-y-3">
            <div class="flex gap-3">
                <div class="w-16 h-16 rounded-2xl bg-[var(--gv-color-neutral-100)] flex items-center justify-center text-lg font-semibold text-[var(--gv-color-neutral-400)]">
                    {{ \Illuminate\Support\Str::substr($seriesData['title'], 0, 2) }}
                </div>
                <div>
                    <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-1">{{ $seriesData['title'] }}</h1>
                    <p class="text-xs text-[var(--gv-color-neutral-500)] mb-1">
                        {{ get_phrase('Host: :host', ['host' => $seriesData['host']]) }}
                    </p>
                    <button class="gv-btn gv-btn-ghost" id="follow-series">{{ get_phrase('Follow') }}</button>
                </div>
            </div>
            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                {{ $seriesData['description'] }}
            </p>
        </div>

        <div class="gv-card space-y-2">
            <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Episodes') }}</h3>
            <ul class="space-y-2" id="episode-list">
                @foreach($seriesData['episodes'] as $episode)
                    <li class="flex items-center justify-between rounded-xl border border-[var(--gv-color-border)] p-3">
                        <div>
                            <p class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                                {{ $episode['title'] }}
                            </p>
                            <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">
                                {{ $episode['date'] }} • {{ $episode['duration'] }}
                            </p>
                        </div>
                        <a href="{{ route('wnip.podcasts.episode', ['episode' => $loop->index + 1]) ?? '#' }}"
                            class="gv-btn gv-btn-ghost text-sm">
                            {{ get_phrase('Play') }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    <aside>
        @include('wnip::components.notes_sidebar')
    </aside>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/podcastPlayer.js') }}"></script>
@endpush
