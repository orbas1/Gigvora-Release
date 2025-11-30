@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="text-sm uppercase tracking-wide text-indigo-500 font-semibold mb-2">{{ __('Networking Waiting Room') }}</p>
        <h1 class="live-header__title">{{ $networkingSession->title }}</h1>
        <p class="live-header__subtitle">{{ __('Shuffle into the next rotation as soon as the host starts the room.') }}</p>
    </div>
@endsection

@section('live-content')
<div class="gv-card space-y-4">
    @include('wnip::components.waiting_room_header', [
        'title' => $session->title,
        'host' => optional($session->host)->name ?? get_phrase('Host'),
        'start' => $session->starts_at?->format('M j • g:i A'),
        'status' => ucfirst($session->status ?? 'waiting'),
        'statusAttributes' => 'data-waiting-status="true"',
    ])

    <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
        {{ get_phrase('Rotations every :seconds seconds', ['seconds' => $session->rotation_interval ?? 60]) }}
    </p>
    <div class="text-4xl font-mono text-[var(--gv-color-neutral-900)]" id="networking-countdown"
        data-start="{{ $session->starts_at?->toIso8601String() }}">--:--</div>
    <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
        {{ get_phrase('Finalise your intro card while we prepare your first rotation.') }}
    </p>
    <form class="grid gap-3 md:grid-cols-2">
        <input class="gv-input" placeholder="{{ get_phrase('Headline') }}" />
        <input class="gv-input" placeholder="{{ get_phrase('Bio / Links') }}" />
    </form>
    @php $canJoin = $session->status === 'in_rotation'; @endphp
    <a id="enter-networking" href="{{ route('wnip.networking.live', $session) }}"
        class="gv-btn gv-btn-primary {{ $canJoin ? '' : 'opacity-50 pointer-events-none' }}"
        @unless($canJoin) aria-disabled="true" @endunless>
        {{ get_phrase('Join session') }}
    </a>
</div>
@endsection

@push('scripts')
<script>
    const countdown = document.getElementById('networking-countdown');
    if (countdown) {
        const start = new Date(countdown.dataset.start);
        const state = document.querySelector('[data-waiting-status]');
        const join = document.getElementById('enter-networking');
        const enableJoin = () => {
            if (!join) {
                return;
            }
            join.classList.remove('opacity-50', 'pointer-events-none');
            join.removeAttribute('aria-disabled');
        };
        const disableJoin = () => {
            if (!join) {
                return;
            }
            join.classList.add('opacity-50', 'pointer-events-none');
            join.setAttribute('aria-disabled', 'true');
        };
        const tick = () => {
            const now = new Date();
            const diff = start - now;
            if (diff <= 0) {
                countdown.textContent = '00:00';
                if (state) {
                    state.textContent = '{{ get_phrase('Live now') }}';
                    state.classList.add('gv-pill--danger');
                }
                enableJoin();
                return;
            }
            disableJoin();
            const minutes = Math.floor(diff / 1000 / 60);
            const seconds = Math.floor((diff / 1000) % 60);
            countdown.textContent = `${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
        };
        tick();
        setInterval(tick, 1000);
    }
</script>
@endpush
