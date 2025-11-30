@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="gv-eyebrow mb-1">{{ get_phrase('Interviews') }}</p>
        <h1 class="live-header__title">{{ get_phrase('Interview schedule') }}</h1>
        <p class="live-header__subtitle">{{ get_phrase('Upcoming and in-progress interviews with scoring and waiting rooms.') }}</p>
    </div>
@endsection

@section('live-content')
@php
    use Illuminate\Support\Str;
@endphp
<div class="space-y-6">
    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-xl font-semibold text-[var(--gv-color-neutral-900)] mb-1">{{ get_phrase('Interviews') }}</h2>
            <p class="gv-muted mb-0">{{ get_phrase('Stay on top of interviews for candidates and panels.') }}</p>
        </div>
        <a class="gv-btn gv-btn-primary" href="{{ route('wnip.interviews.index', ['create' => 1]) }}">
            {{ get_phrase('Schedule interview') }}
        </a>
    </div>

    <form method="get" class="gv-card space-y-3">
        <label class="space-y-1 w-full md:w-1/2">
            <span class="gv-label">{{ get_phrase('Search interviews') }}</span>
            <input type="text" name="q" class="gv-input" value="{{ $filters['q'] ?? '' }}"
                placeholder="{{ get_phrase('Title') }}">
        </label>
        <div class="flex justify-end">
            <button class="gv-btn gv-btn-primary" type="submit">{{ get_phrase('Filter') }}</button>
        </div>
    </form>

    <div class="grid gap-4 lg:grid-cols-2">
        @forelse($interviews as $interview)
            <article class="gv-card space-y-3">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)] mb-1">{{ $interview->title }}</h3>
                        <p class="text-sm text-[var(--gv-color-neutral-500)] mb-1">
                            {{ $interview->scheduled_at?->format('M j • g:i A') ?? get_phrase('TBD') }}
                        </p>
                        <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                            {{ Str::limit($interview->description, 140) }}
                        </p>
                    </div>
                    <span class="gv-pill">
                        {{ $interview->is_panel ? get_phrase('Panel') : get_phrase('1:1') }}
                    </span>
                </div>
                <a class="gv-btn gv-btn-ghost w-full" href="{{ route('wnip.interviews.show', $interview) }}">
                    {{ get_phrase('Open interview') }}
                </a>
            </article>
        @empty
            <div class="lg:col-span-2">
                <div class="gv-empty">
                    {{ get_phrase('No interviews scheduled.') }}
                </div>
            </div>
        @endforelse
    </div>

    <div>
        {{ $interviews->links() }}
    </div>
</div>
@endsection
