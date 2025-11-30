@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="gv-eyebrow mb-1">{{ get_phrase('Interview waiting room') }}</p>
        <h1 class="live-header__title">{{ $interview->title }}</h1>
        <p class="live-header__subtitle">{{ get_phrase('Keep this page open; we will take you to the live interview when it starts.') }}</p>
    </div>
@endsection

@section('live-content')
<div class="gv-card space-y-4">
    @include('wnip::components.waiting_room_header', [
        'title' => $interview->title,
        'host' => optional($interview->host)->name ?? get_phrase('Host'),
        'start' => $interview->scheduled_at?->format('M j • g:i A'),
        'status' => get_phrase('Waiting'),
        'statusAttributes' => 'data-waiting-status="true"',
    ])

    <div class="text-4xl font-mono text-[var(--gv-color-neutral-900)]" id="interview-countdown"
        data-start="{{ $interview->scheduled_at?->toIso8601String() }}">--:--</div>
    <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
        {{ get_phrase('We will notify you when it is time to enter the interview room.') }}
    </p>
    <a id="enter-interview" class="gv-btn gv-btn-primary opacity-50 pointer-events-none"
        href="{{ route('wnip.interviews.show', $interview) }}" aria-disabled="true">
        {{ get_phrase('Enter interview') }}
    </a>
</div>
@endsection

@push('scripts')
<script>
    const interviewCountdown = document.getElementById('interview-countdown');
    if (interviewCountdown) {
        const start = new Date(interviewCountdown.dataset.start);
        const enterBtn = document.getElementById('enter-interview');
        const state = document.querySelector('[data-waiting-status]');
        const enableJoin = () => {
            enterBtn.classList.remove('opacity-50', 'pointer-events-none');
            enterBtn.removeAttribute('aria-disabled');
            if (state) {
                state.textContent = '{{ get_phrase('Live now') }}';
                state.classList.add('gv-pill--danger');
            }
        };
        const disableJoin = () => {
            enterBtn.classList.add('opacity-50', 'pointer-events-none');
            enterBtn.setAttribute('aria-disabled', 'true');
        };
        const tick = () => {
            const now = new Date();
            const diff = start - now;
            if (diff <= 0) {
                interviewCountdown.textContent = '00:00';
                enableJoin();
                return;
            }
            disableJoin();
            const minutes = Math.floor(diff / 1000 / 60);
            const seconds = Math.floor((diff / 1000) % 60);
            interviewCountdown.textContent = `${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
        };
        tick();
        setInterval(tick, 1000);
    }
</script>
@endpush
