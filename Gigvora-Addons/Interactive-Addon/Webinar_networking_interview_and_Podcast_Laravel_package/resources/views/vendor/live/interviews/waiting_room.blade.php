@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="gv-eyebrow mb-1">{{ get_phrase('Interview waiting room') }}</p>
        <h1 class="live-header__title">{{ $interview->title }}</h1>
        <p class="live-header__subtitle">{{ get_phrase('Keep this page open; we will take you to the live interview when it starts.') }}</p>
    </div>
@endsection

@section('live-content')
<div class="gv-card space-y-4" id="interview-waiting-room"
    data-start-at="{{ $interview->scheduled_at?->toIso8601String() }}"
    data-live-url="{{ route('wnip.interviews.live', $interview) }}">
    @include('wnip::components.waiting_room_header', [
        'title' => $interview->title,
        'host' => optional($interview->host)->name ?? get_phrase('Host'),
        'start' => $interview->scheduled_at?->format('M j • g:i A'),
        'status' => get_phrase('Waiting'),
        'statusAttributes' => 'data-waiting-status="true" data-live-label="' . get_phrase('Live now') . '"',
    ])

    <div class="text-4xl font-mono text-[var(--gv-color-neutral-900)]" id="interview-countdown"
        data-start="{{ $interview->scheduled_at?->toIso8601String() }}">--:--</div>
    <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
        {{ get_phrase('We will notify you when it is time to enter the interview room.') }}
    </p>
    <a id="enter-interview" class="gv-btn gv-btn-primary opacity-50 pointer-events-none"
        href="{{ route('wnip.interviews.live', $interview) }}" aria-disabled="true"
        data-live-url="{{ route('wnip.interviews.live', $interview) }}">
        {{ get_phrase('Enter interview') }}
    </a>
</div>
@endsection

