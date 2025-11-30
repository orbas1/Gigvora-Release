@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="text-sm uppercase tracking-wide text-indigo-500 font-semibold mb-2">{{ __('Webinar Waiting Room') }}</p>
        <h1 class="live-header__title">{{ $webinar->title }}</h1>
        <p class="live-header__subtitle">{{ __('Your session will begin soon. Stay ready to go live.') }}</p>
    </div>
@endsection

@section('live-content')
<div class="gv-card space-y-5">
    @include('wnip::components.waiting_room_header', [
        'title' => $webinar->title,
        'host' => optional($webinar->host)->name ?? get_phrase('Host'),
        'start' => $webinar->starts_at?->format('M j • g:i A'),
        'status' => $webinar->is_live ? get_phrase('Live now') : get_phrase('Waiting'),
        'statusAttributes' => 'data-waiting-status="true"',
    ])

    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm text-[var(--gv-color-neutral-500)] mb-1">
                {{ get_phrase('Session begins soon. We will open the doors as soon as the host starts the session.') }}
            </p>
            <div class="text-4xl font-mono text-[var(--gv-color-neutral-900)]" id="countdown"
                data-start="{{ $webinar->starts_at?->toIso8601String() }}">--:--</div>
        </div>
        @php $canJoin = $webinar->is_live; @endphp
        <div class="text-right">
            <a id="enter-webinar" href="{{ route('wnip.webinars.live', $webinar) }}"
                class="gv-btn gv-btn-primary {{ $canJoin ? '' : 'opacity-50 pointer-events-none' }}"
                @unless($canJoin) aria-disabled="true" @endunless>
                {{ get_phrase('Enter webinar') }}
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const countdownEl = document.getElementById('countdown');
    if (countdownEl) {
        const startTime = new Date(countdownEl.dataset.start);
        const state = document.querySelector('[data-waiting-status]');
        const joinBtn = document.getElementById('enter-webinar');
        const enableJoin = () => {
            if (!joinBtn) return;
            joinBtn.classList.remove('opacity-50', 'pointer-events-none');
            joinBtn.removeAttribute('aria-disabled');
        };
        const disableJoin = () => {
            if (!joinBtn) return;
            joinBtn.classList.add('opacity-50', 'pointer-events-none');
            joinBtn.setAttribute('aria-disabled', 'true');
        };
        const tick = () => {
            const now = new Date();
            const diff = startTime - now;
            if (diff <= 0) {
                countdownEl.textContent = '00:00';
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
            countdownEl.textContent =
                String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
        };
        tick();
        setInterval(tick, 1000);
    }
</script>
@endpush
