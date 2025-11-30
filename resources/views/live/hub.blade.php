@extends('layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('title', get_phrase('Live & Webinars'))

@section('page-header')
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <p class="gv-eyebrow">{{ get_phrase('Interactive / Live Addon') }}</p>
            <h1 class="text-3xl font-semibold text-[var(--gv-color-neutral-900)]">
                {{ get_phrase('Webinars, Networking & Replays') }}
            </h1>
            <p class="gv-muted mt-1 max-w-2xl">
                {{ get_phrase('Join live webinars, networking lounges, and replay recordings powered by the Interactive addon.') }}
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('wnip.webinars.index') }}" class="gv-btn gv-btn-primary">
                {{ get_phrase('Browse Webinars') }}
            </a>
            <a href="{{ route('wnip.networking.index') }}" class="gv-btn gv-btn-ghost">
                {{ get_phrase('Networking Sessions') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    @php
        $overview = $overview ?? ['metrics' => [], 'sections' => [], 'ad' => null];
        $sections = $overview['sections'] ?? [];
        $metrics = $overview['metrics'] ?? [];
        $ad = $overview['ad'] ?? null;
    @endphp

    <div class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_320px]">
        <div class="space-y-6">
            @if (!empty($metrics))
                <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($metrics as $metric)
                        <div class="gv-stat-tile">
                            <div class="gv-stat-tile__label">{{ $metric['label'] }}</div>
                            <div class="gv-stat-tile__value">{{ $metric['value'] }}</div>
                            <div class="gv-stat-tile__meta">{{ $metric['meta'] }}</div>
                        </div>
                    @endforeach
                </div>
            @endif

            @forelse ($sections as $section)
                <section id="{{ $section['key'] ?? '' }}" class="gv-card space-y-5">
                    <div
                        class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                        <div class="space-y-1">
                            @if (!empty($section['eyebrow']))
                                <p class="gv-eyebrow mb-1">{{ $section['eyebrow'] }}</p>
                            @endif
                            <h2 class="text-xl font-semibold text-[var(--gv-color-neutral-900)]">
                                {{ $section['title'] }}
                            </h2>
                            <p class="gv-muted">
                                {{ $section['description'] }}
                            </p>
                        </div>
                        @if (!empty($section['actions']))
                            <div class="flex flex-wrap gap-2">
                                @foreach ($section['actions'] as $action)
                                    <a href="{{ $action['route'] ?? '#' }}"
                                        class="gv-btn {{ ($action['type'] ?? 'ghost') === 'primary' ? 'gv-btn-primary' : 'gv-btn-ghost' }}">
                                        {{ $action['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    @php
                        $items = $section['items'] ?? [];
                    @endphp

                    @if (!empty($items))
                        <div class="space-y-4">
                            @foreach ($items as $item)
                                <article class="border border-[var(--gv-color-border)] rounded-2xl p-4 bg-[var(--gv-color-surface-alt)] flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                                    <div class="space-y-1">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">
                                                {{ $item['title'] }}
                                            </h3>
                                            @if (!empty($item['status']))
                                                <span class="gv-pill {{ Str::of($item['status'])->lower()->contains('live') ? 'gv-pill--danger' : '' }}">
                                                    {{ $item['status'] }}
                                                </span>
                                            @endif
                                            @if (!empty($item['tag']))
                                                <span class="gv-pill gv-pill--success">{{ $item['tag'] }}</span>
                                            @endif
                                        </div>
                                        <div class="text-sm text-[var(--gv-color-neutral-500)] flex flex-wrap gap-3">
                                            @if (!empty($item['caption']))
                                                <span>{{ $item['caption'] }}</span>
                                            @endif
                                            @if (!empty($item['meta']))
                                                <span>{{ $item['meta'] }}</span>
                                            @endif
                                            @if (!empty($item['detail']))
                                                <span>{{ $item['detail'] }}</span>
                                            @endif
                                        </div>
                                        @if (!empty($item['description']))
                                            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                                                {{ $item['description'] }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if (!empty($item['href']))
                                            <a href="{{ $item['href'] }}"
                                                class="gv-btn gv-btn-ghost">
                                                {{ $item['cta'] ?? get_phrase('Open flow') }}
                                            </a>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="gv-empty">
                            {{ get_phrase('No sessions yet. Check back later!') }}
                        </div>
                    @endif
                </section>
            @empty
                <div class="gv-empty">
                    {{ get_phrase('Interactive addon is disabled. Enable the Live addon to view webinars, networking sessions, podcasts, and interviews.') }}
                </div>
            @endforelse
        </div>

        <aside class="space-y-4">
            @include('components.utilities.quick-tools', ['context' => 'interactive'])

            @if ($ad)
                <div class="gv-card space-y-3">
                    <p class="gv-eyebrow mb-1">{{ get_phrase('Sponsored spotlight') }}</p>
                    <h3 class="text-lg font-semibold text-[var(--gv-color-neutral-900)]">
                        {{ $ad->title }}
                    </h3>
                    <p class="gv-muted">{{ $ad->description }}</p>
                    <a href="{{ $ad->url }}" class="gv-btn gv-btn-primary w-full">
                        {{ $ad->cta }}
                    </a>
                </div>
            @endif
        </aside>
    </div>
@endsection

