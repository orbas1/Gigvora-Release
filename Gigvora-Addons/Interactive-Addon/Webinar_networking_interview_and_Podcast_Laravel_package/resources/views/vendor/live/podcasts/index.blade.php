@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="text-sm uppercase tracking-wide text-indigo-500 font-semibold mb-2">{{ __('Podcasts') }}</p>
        <h1 class="live-header__title">{{ __('Podcasts & Series') }}</h1>
        <p class="live-header__subtitle">{{ __('Listen live, catch replays, and manage your subscribed series in one place.') }}</p>
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
                {{ get_phrase('Podcasts & series') }}
            </h1>
            <p class="gv-muted mb-0">
                {{ get_phrase('Browse live recordings, series, and latest episodes.') }}
            </p>
        </div>
        <a class="gv-btn gv-btn-primary" href="{{ route('wnip.podcasts.index', ['create' => 1]) }}">
            {{ get_phrase('Create series') }}
        </a>
    </div>

    <form method="get" class="gv-card space-y-3">
        <label class="space-y-1 w-full md:w-1/2">
            <span class="gv-label">{{ get_phrase('Search series') }}</span>
            <input type="text" name="q" class="gv-input" value="{{ $filters['q'] ?? '' }}"
                placeholder="{{ get_phrase('Series title') }}">
        </label>
        <div class="flex justify-end">
            <button class="gv-btn gv-btn-primary" type="submit">{{ get_phrase('Filter') }}</button>
        </div>
    </form>

    <div class="grid gap-4 lg:grid-cols-2 xl:grid-cols-3">
        @forelse($series as $item)
            <article class="gv-card space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-[var(--gv-color-neutral-100)] flex items-center justify-center text-sm text-[var(--gv-color-neutral-400)]">
                        {{ Str::substr($item->title, 0, 2) }}
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                            {{ $item->title }}
                        </h3>
                        <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">
                            {{ trans_choice('{1}1 episode|[2,*]:count episodes', $item->episodes->count(), ['count' => $item->episodes->count()]) }}
                        </p>
                    </div>
                </div>
                <p class="text-sm text-[var(--gv-color-neutral-600)]">
                    {{ Str::limit($item->description, 120) }}
                </p>
                <a class="gv-btn gv-btn-ghost w-full" href="{{ route('wnip.podcasts.series', $item) }}">
                    {{ get_phrase('Open series') }}
                </a>
            </article>
        @empty
            <div class="lg:col-span-2 xl:col-span-3">
                <div class="gv-empty">
                    {{ get_phrase('No podcast series found.') }}
                </div>
            </div>
        @endforelse
    </div>

    <div>
        {{ $series->links() }}
    </div>
</div>
@endsection
