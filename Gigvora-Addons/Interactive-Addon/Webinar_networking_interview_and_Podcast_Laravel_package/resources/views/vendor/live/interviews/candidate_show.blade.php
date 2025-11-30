@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="gv-eyebrow mb-1">{{ get_phrase('Interview detail') }}</p>
        <h1 class="live-header__title">{{ get_phrase('Interview overview & prep') }}</h1>
        <p class="live-header__subtitle">{{ get_phrase('Review interview details, join links, and attachments.') }}</p>
    </div>
@php
    $interview = $interview ?? [
        'role' => 'Senior Engineer',
        'company' => 'Acme',
        'interviewers' => 'Alex, Priya',
        'datetime' => 'May 7, 10:00',
        'duration' => '60 mins',
        'type' => 'Video',
        'instructions' => 'Join 5 minutes early and test your mic.',
    ];
@endphp
@endsection

@section('live-content')
<div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_320px]" id="candidate-interview"
    data-waiting-url="{{ route('wnip.interviews.waiting', ['interview' => $interview['id'] ?? 1]) ?? '#' }}">
    <div class="space-y-4">
        <div class="gv-card space-y-2">
            <h2 class="text-xl font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                {{ $interview['role'] }} {{ get_phrase('at') }} {{ $interview['company'] }}
            </h2>
            <p class="text-sm text-[var(--gv-color-neutral-500)] mb-0">
                {{ $interview['datetime'] }} • {{ $interview['duration'] }} • {{ $interview['type'] }}
            </p>
        </div>
        <div class="gv-card space-y-2">
            <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Interviewers') }}</h3>
            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">{{ $interview['interviewers'] }}</p>
        </div>
        <div class="gv-card space-y-2">
            <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Instructions') }}</h3>
            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">{{ $interview['instructions'] }}</p>
        </div>
    </div>
    <aside class="space-y-4">
        <div class="gv-card space-y-3">
            <h4 class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Join') }}</h4>
            <button class="gv-btn gv-btn-primary w-full" id="join-waiting">{{ get_phrase('Join waiting room') }}</button>
            <button class="gv-btn gv-btn-ghost w-full opacity-50 pointer-events-none" id="join-interview" aria-disabled="true">
                {{ get_phrase('Join interview') }}
            </button>
        </div>
        <div class="gv-card space-y-2">
            <h4 class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Attachments') }}</h4>
            <ul class="space-y-1 text-sm">
                <li><a href="#" class="text-[var(--gv-color-primary-600)]">{{ get_phrase('Job description') }}</a></li>
                <li><a href="#" class="text-[var(--gv-color-primary-600)]">{{ get_phrase('Prep doc') }}</a></li>
            </ul>
        </div>
    </aside>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/interviewDashboard.js') }}"></script>
@endpush
