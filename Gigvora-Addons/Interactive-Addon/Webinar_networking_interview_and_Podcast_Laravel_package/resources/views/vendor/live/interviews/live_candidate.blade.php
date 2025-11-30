@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="gv-eyebrow mb-1">{{ get_phrase('Interview live') }}</p>
        <h1 class="live-header__title">{{ get_phrase('Candidate live room') }}</h1>
        <p class="live-header__subtitle">{{ get_phrase('Control mic, camera, and jot down notes while you interview.') }}</p>
    </div>
@endsection

@section('live-content')
@php
    $primarySlot = $primarySlot ?? $interview->slots->sortBy('starts_at')->first();
@endphp
<div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_320px]" id="interview-live"
    data-session-id="{{ $interview->id }}"
    data-start-at="{{ $primarySlot?->starts_at?->toIso8601String() ?? $interview->scheduled_at?->toIso8601String() }}"
    data-meeting-link="{{ $primarySlot?->meeting_link }}">
    <div class="gv-card p-0 overflow-hidden">
        <div class="bg-[var(--gv-color-neutral-900)] text-white flex flex-col items-center justify-center min-h-[360px] gap-3">
            <span class="gv-pill" data-live-status data-live-label="{{ get_phrase('Live') }}">{{ get_phrase('Preparing') }}</span>
            <p class="mb-0 font-semibold">{{ optional($primarySlot?->interviewer)->name ?? get_phrase('Interviewer joining') }}</p>
            <p class="mb-0 text-sm text-[var(--gv-color-neutral-200)]">
                {{ $primarySlot?->starts_at?->format('M j • g:i A') ?? $interview->scheduled_at?->format('M j • g:i A') }}
            </p>
            <p class="mb-0 text-xs text-[var(--gv-color-neutral-300)]">{{ get_phrase('Video container') }}</p>
        </div>
    </div>
    <aside class="space-y-4">
        <div class="gv-card space-y-2">
            <button class="gv-btn gv-btn-ghost w-full" id="toggle-mic">{{ get_phrase('Mute / Unmute') }}</button>
            <button class="gv-btn gv-btn-ghost w-full" id="toggle-camera">{{ get_phrase('Camera on/off') }}</button>
            <button class="gv-btn bg-[var(--gv-color-danger)] text-white w-full" id="leave-interview">
                {{ get_phrase('Leave interview') }}
            </button>
        </div>
        @include('wnip::components.notes_sidebar')
    </aside>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/interviewDashboard.js') }}"></script>
@endpush
