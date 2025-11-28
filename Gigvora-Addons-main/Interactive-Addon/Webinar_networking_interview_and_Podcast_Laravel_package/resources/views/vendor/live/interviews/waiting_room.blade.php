@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-1">Interview Waiting Room</h1>
    <p class="text-muted">{{ $interview->title }} • {{ $interview->scheduled_at?->toDayDateTimeString() }}</p>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="fw-semibold">Host</div>
                <span class="badge bg-secondary">{{ optional($interview->host)->name ?? 'Host' }}</span>
            </div>
            <div class="display-6 mb-3" id="interview-countdown" data-start="{{ $interview->scheduled_at?->toIso8601String() }}">--:--</div>
            <p class="text-muted">Keep this page open; we will take you to the live interview when it starts.</p>
            <a id="enter-interview" class="btn btn-primary" href="{{ route('wnip.interviews.show', $interview) }}" disabled>Enter Interview</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const interviewCountdown = document.getElementById('interview-countdown');
    if (interviewCountdown) {
        const start = new Date(interviewCountdown.dataset.start);
        const enterBtn = document.getElementById('enter-interview');
        const tick = () => {
            const now = new Date();
            const diff = start - now;
            if (diff <= 0) {
                interviewCountdown.textContent = '00:00';
                enterBtn?.removeAttribute('disabled');
                return;
            }
            const minutes = Math.floor(diff / 1000 / 60);
            const seconds = Math.floor((diff / 1000) % 60);
            interviewCountdown.textContent = `${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
        };
        tick();
        setInterval(tick, 1000);
    }
</script>
@endpush
