@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-1">Networking Waiting Room</h1>
    <p class="text-muted">{{ $session->title }} • {{ $session->starts_at?->toDayDateTimeString() }}</p>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="fw-semibold">Countdown</div>
                <span class="badge bg-secondary session-state">{{ ucfirst($session->status) }}</span>
            </div>
            <div class="display-6" id="networking-countdown" data-start="{{ $session->starts_at?->toIso8601String() }}">--:--</div>
            <p class="text-muted">Finalise your intro card while we prepare your first rotation.</p>
            <form class="mt-3">
                <div class="row g-2">
                    <div class="col-md-6"><input class="form-control" placeholder="Headline" /></div>
                    <div class="col-md-6"><input class="form-control" placeholder="Bio / Links" /></div>
                </div>
            </form>
            <a id="enter-networking" href="{{ route('wnip.networking.live', $session) }}" class="btn btn-primary mt-3" {{ $session->status === 'in_rotation' ? '' : 'disabled' }}>Join Session</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const countdown = document.getElementById('networking-countdown');
    if (countdown) {
        const start = new Date(countdown.dataset.start);
        const state = document.querySelector('.session-state');
        const join = document.getElementById('enter-networking');
        const tick = () => {
            const now = new Date();
            const diff = start - now;
            if (diff <= 0) {
                countdown.textContent = '00:00';
                state.textContent = 'Live';
                state.classList.replace('bg-secondary', 'bg-success');
                join?.removeAttribute('disabled');
                return;
            }
            const minutes = Math.floor(diff / 1000 / 60);
            const seconds = Math.floor((diff / 1000) % 60);
            countdown.textContent = `${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
        };
        tick();
        setInterval(tick, 1000);
    }
</script>
@endpush
