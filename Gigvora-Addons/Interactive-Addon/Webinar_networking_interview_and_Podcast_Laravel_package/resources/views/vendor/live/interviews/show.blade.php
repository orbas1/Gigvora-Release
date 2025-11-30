@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="gv-eyebrow mb-1">{{ get_phrase('Interview detail') }}</p>
        <h1 class="live-header__title">{{ $interview->title }}</h1>
        <p class="live-header__subtitle">{{ get_phrase('Review context, slots, hosts, and scoring in one glance.') }}</p>
    </div>
@endsection

@section('live-content')
<div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_320px]">
    <div class="space-y-4">
        <div class="gv-card space-y-2">
            <p class="text-sm text-[var(--gv-color-neutral-500)] mb-0">
                {{ $interview->scheduled_at?->format('M j • g:i A') ?? get_phrase('TBD') }}
                • {{ $interview->duration_minutes ?? 30 }} {{ get_phrase('mins') }}
            </p>
            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                {!! nl2br(e($interview->description)) !!}
            </p>
        </div>

        <div class="gv-card space-y-3">
            <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Slots') }}</h3>
            <div class="space-y-2">
                @forelse($interview->slots as $slot)
                    <div class="rounded-xl border border-[var(--gv-color-border)] p-3 flex flex-col gap-2">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-0">
                                {{ $slot->starts_at?->format('M j • g:i A') }}
                            </p>
                            <span class="gv-pill">{{ get_phrase('Slot') }}</span>
                        </div>
                        <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">
                            {{ get_phrase('Interviewer :interviewer → Interviewee :interviewee', ['interviewer' => $slot->interviewer_id, 'interviewee' => $slot->interviewee_id]) }}
                        </p>
                        @auth
                            <form method="post" action="{{ route('wnip.interviews.score', [$interview, $slot]) }}" class="flex gap-2">
                                @csrf
                                <input type="hidden" name="criteria[communication]" value="5">
                                <input type="hidden" name="scores[communication]" value="5">
                                <button class="gv-btn gv-btn-ghost" type="submit">{{ get_phrase('Score slot') }}</button>
                            </form>
                        @endauth
                    </div>
                @empty
                    <div class="gv-empty">
                        {{ get_phrase('No slots scheduled.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <aside class="space-y-4">
        <div class="gv-card space-y-2">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-0">{{ get_phrase('Host') }}</p>
                <span class="gv-pill">
                    {{ $interview->is_panel ? get_phrase('Panel') : get_phrase('1:1') }}
                </span>
            </div>
            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-2">
                {{ optional($interview->host)->name ?? get_phrase('Host') }}
            </p>
            <a class="gv-btn gv-btn-ghost w-full" href="{{ route('wnip.interviews.waiting', $interview) }}">
                {{ get_phrase('Waiting room') }}
            </a>
        </div>

        <div class="gv-card space-y-2">
            <h4 class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Scores') }}</h4>
            <div class="space-y-2 max-h-64 overflow-auto">
                @forelse($interview->scores as $score)
                    <div class="rounded-xl border border-[var(--gv-color-border)] p-3">
                        <p class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                            {{ get_phrase('Interviewer #:id', ['id' => $score->interviewer_id]) }}
                        </p>
                        <pre class="text-xs text-[var(--gv-color-neutral-500)] mb-0">{{ json_encode($score->scores) }}</pre>
                    </div>
                @empty
                    <p class="text-sm text-[var(--gv-color-neutral-500)] mb-0">{{ get_phrase('No scores yet.') }}</p>
                @endforelse
            </div>
        </div>

        @include('wnip::components.notes_sidebar')
    </aside>
</div>
@endsection
