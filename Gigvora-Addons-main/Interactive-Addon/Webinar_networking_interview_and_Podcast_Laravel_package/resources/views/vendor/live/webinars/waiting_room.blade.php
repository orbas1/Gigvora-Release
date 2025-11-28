@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-1">Waiting Room</h1>
    <p class="text-muted">{{ $webinar->title }} • starts {{ $webinar->starts_at?->toDayDateTimeString() }}</p>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    <div class="fw-semibold">Host</div>
                    <div class="text-muted">{{ optional($webinar->host)->name ?? 'Host' }}</div>
                </div>
                <div class="text-end">
                    <div class="text-muted small">Status</div>
                    <div class="badge bg-secondary live-state">{{ $webinar->is_live ? 'Live' : 'Waiting' }}</div>
                </div>
            </div>
            <div class="display-6 mb-3" id="countdown" data-start="{{ $webinar->starts_at?->toIso8601String() }}">--:--</div>
            <p class="text-muted">{{ $webinar->waiting_room_message ?? 'We will open the doors as soon as the host starts the session.' }}</p>
            <a id="enter-webinar" href="{{ route('wnip.webinars.live', $webinar) }}" class="btn btn-primary" {{ $webinar->is_live ? '' : 'disabled' }}>Enter Webinar</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const countdownEl = document.getElementById('countdown');
    if (countdownEl) {
        const startTime = new Date(countdownEl.dataset.start);
        const state = document.querySelector('.live-state');
        const joinBtn = document.getElementById('enter-webinar');
        const tick = () => {
            const now = new Date();
            const diff = startTime - now;
            if (diff <= 0) {
                countdownEl.textContent = '00:00';
                state.textContent = 'Live';
                state.classList.remove('bg-secondary');
                state.classList.add('bg-success');
                joinBtn?.removeAttribute('disabled');
                return;
            }
            const minutes = Math.floor(diff / 1000 / 60);
            const seconds = Math.floor((diff / 1000) % 60);
            countdownEl.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        };
        tick();
        setInterval(tick, 1000);
    }
</script>
@endpush
