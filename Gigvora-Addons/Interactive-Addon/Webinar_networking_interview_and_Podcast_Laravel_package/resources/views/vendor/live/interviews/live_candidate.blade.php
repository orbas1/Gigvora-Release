@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="gv-eyebrow mb-1">{{ get_phrase('Interview live') }}</p>
        <h1 class="live-header__title">{{ get_phrase('Candidate live room') }}</h1>
        <p class="live-header__subtitle">{{ get_phrase('Control mic, camera, and jot down notes while you interview.') }}</p>
    </div>
@endsection

@section('live-content')
<div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_320px]" id="interview-live" data-session-id="{{ $interview['id'] ?? 1 }}">
    <div class="gv-card p-0 overflow-hidden">
        <div class="bg-[var(--gv-color-neutral-900)] text-white flex items-center justify-center min-h-[360px]">
            {{ get_phrase('Video container') }}
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
