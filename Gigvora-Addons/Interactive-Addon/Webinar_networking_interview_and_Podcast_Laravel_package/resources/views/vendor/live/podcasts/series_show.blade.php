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
    use Illuminate\Support\Str;
    $followersCount = $series->followers_count ?? $series->followers->count();
    $hostName = $series->metadata['host_name'] ?? get_phrase('Host');
@endphp
<div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_320px]" id="podcast-series"
    data-follow-url="{{ auth()->check() ? route('wnip.podcasts.follow', $series) : '' }}" data-followed="{{ auth()->check() && $isFollowed ? 'true' : 'false' }}"
    data-series-id="{{ $series->id }}">
    <div class="space-y-4">
        <div class="gv-card space-y-3">
            <div class="flex gap-3">
                <div class="w-16 h-16 rounded-2xl bg-[var(--gv-color-neutral-100)] flex items-center justify-center text-lg font-semibold text-[var(--gv-color-neutral-400)]">
                    {{ Str::substr($series->title, 0, 2) }}
                </div>
                <div>
                    <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-1">{{ $series->title }}</h1>
                    <p class="text-xs text-[var(--gv-color-neutral-500)] mb-1">
                        {{ get_phrase('Host: :host', ['host' => $hostName]) }} •
                        <span id="series-followers-count" data-followers-count="{{ $followersCount }}">
                            {{ trans_choice('{1}1 follower|[2,*]:count followers', $followersCount, ['count' => $followersCount]) }}
                        </span>
                    </p>
                    <button class="gv-btn gv-btn-ghost" id="follow-series" @guest disabled @endguest>
                        {{ $isFollowed ? get_phrase('Following') : get_phrase('Follow') }}
                    </button>
                </div>
            </div>
            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                {{ $series->description }}
            </p>
        </div>

        <div class="gv-card space-y-2">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Episodes') }}</h3>
                <span class="text-xs text-[var(--gv-color-neutral-500)]">{{ trans_choice('{1}1 episode|[2,*]:count episodes', $series->episodes->count(), ['count' => $series->episodes->count()]) }}</span>
            </div>
            <ul class="space-y-2" id="episode-list">
                @forelse($series->episodes as $episode)
                    <li class="flex items-center justify-between rounded-xl border border-[var(--gv-color-border)] p-3">
                        <div>
                            <p class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                                {{ $episode->title }}
                            </p>
                            <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">
                                {{ $episode->published_at?->format('M j, Y') ?? get_phrase('Draft') }}
                                @if($episode->duration)
                                    • {{ get_phrase(':minutes min', ['minutes' => $episode->duration]) }}
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('wnip.podcasts.episode', [$series, $episode]) }}" class="gv-btn gv-btn-ghost text-sm">
                            {{ get_phrase('Play') }}
                        </a>
                    </li>
                @empty
                    <li class="gv-empty">{{ get_phrase('No episodes yet.') }}</li>
                @endforelse
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
