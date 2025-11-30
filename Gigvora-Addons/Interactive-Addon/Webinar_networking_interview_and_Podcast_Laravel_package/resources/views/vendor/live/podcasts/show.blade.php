@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="text-sm uppercase tracking-wide text-indigo-500 font-semibold mb-2">{{ __('Podcast Series') }}</p>
        <h1 class="live-header__title">{{ $podcastSeries->title }}</h1>
        <p class="live-header__subtitle">{{ __('Browse episodes, guest bios, and livestream recordings in one view.') }}</p>
    </div>
@endsection

@section('live-content')
<div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_320px]">
    <div class="space-y-4">
        <div class="gv-card space-y-3">
            <div class="flex items-center gap-3">
                <div class="w-16 h-16 rounded-2xl bg-[var(--gv-color-neutral-100)] flex items-center justify-center text-lg font-semibold text-[var(--gv-color-neutral-400)]">
                    {{ \Illuminate\Support\Str::substr($series->title, 0, 2) }}
                </div>
                <div>
                    <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-1">{{ $series->title }}</h1>
                    <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">
                        {{ trans_choice('{1}1 episode|[2,*]:count episodes', $series->episodes->count(), ['count' => $series->episodes->count()]) }}
                    </p>
                </div>
            </div>
            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                {{ $series->description }}
            </p>
        </div>

        <div class="gv-card space-y-2">
            <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Episodes') }}</h3>
            <div class="space-y-2">
                @forelse($series->episodes as $episode)
                    <div class="flex items-center justify-between rounded-xl border border-[var(--gv-color-border)] p-3">
                        <div>
                            <p class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                                {{ $episode->title }}
                            </p>
                            <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">
                                {{ $episode->published_at?->format('M j • g:i A') ?? get_phrase('Draft') }}
                            </p>
                        </div>
                        <a class="gv-btn gv-btn-ghost" href="{{ $episode->audio_path ?? '#' }}" target="_blank">
                            {{ get_phrase('Play') }}
                        </a>
                    </div>
                @empty
                    <div class="gv-empty">
                        {{ get_phrase('No episodes yet.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <aside class="space-y-4">
        @include('wnip::components.notes_sidebar')
    </aside>
</div>
@endsection
