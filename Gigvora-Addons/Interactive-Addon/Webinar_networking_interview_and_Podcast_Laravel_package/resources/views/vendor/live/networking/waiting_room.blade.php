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

    <div class="grid gap-3 md:grid-cols-3" role="list">
        <div class="gv-pill md:justify-center" role="listitem">
            {{ get_phrase('Rotations every :seconds s', ['seconds' => $session->rotation_interval ?? 60]) }}
        </div>
        <div class="gv-pill md:justify-center" role="listitem">
            {{ $session->rotation_count ? get_phrase(':count planned rotations', ['count' => $session->rotation_count]) : get_phrase('Rotation schedule pending') }}
        </div>
        <div class="gv-pill md:justify-center" role="listitem">
            {{ $session->is_paid ? get_phrase('Ticketed') : get_phrase('Free entry') }}
        </div>
    </div>

    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
        <div>
            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-1">
                {{ get_phrase('Countdown to start') }}
            </p>
            <div class="text-4xl font-mono text-[var(--gv-color-neutral-900)]" id="networking-countdown"
                data-start="{{ $session->starts_at?->toIso8601String() }}">
                --:--
            </div>
        </div>
        <div class="text-sm text-[var(--gv-color-neutral-600)] leading-relaxed max-w-xl">
            {{ get_phrase('Finalise your intro card while we prepare your first rotation. You can edit this later from the live shell and your Utilities saved items.') }}
        </div>
    </div>

    <form class="grid gap-3 md:grid-cols-2" id="networking-intro" aria-label="{{ get_phrase('Intro card form') }}">
        <label class="space-y-1">
            <span class="gv-label">{{ get_phrase('Headline') }}</span>
            <input class="gv-input" name="headline" placeholder="{{ get_phrase('Product designer & host') }}" />
        </label>
        <label class="space-y-1">
            <span class="gv-label">{{ get_phrase('Bio / Links') }}</span>
            <input class="gv-input" name="bio" placeholder="{{ get_phrase('Focus on marketplaces and live events • calendly.com/you') }}" />
        </label>
        <label class="space-y-1 md:col-span-2">
            <span class="gv-label">{{ get_phrase('Goal for this session') }}</span>
            <textarea class="gv-input min-h-[96px]" name="goal" placeholder="{{ get_phrase('Find two partners to co-host a live webinar series...') }}"></textarea>
        </label>
        <div class="md:col-span-2 flex flex-wrap gap-2 items-center">
            <span class="gv-eyebrow">{{ get_phrase('Checklist') }}</span>
            <span class="gv-pill">{{ get_phrase('Camera & mic checked') }}</span>
            <span class="gv-pill">{{ get_phrase('Notes synced to Utilities') }}</span>
            <span class="gv-pill">{{ get_phrase('Contact card ready') }}</span>
        </div>
    </form>

    @php $canJoin = $session->is_joinable; @endphp
    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div class="text-sm text-[var(--gv-color-neutral-600)]">
            {{ $session->ends_at ? get_phrase('Expected end :time with automatic follow-up summary.', ['time' => $session->ends_at->format('g:i A')]) : get_phrase('Host controls when rotations start.') }}
        </div>
        <a id="enter-networking" href="{{ route('wnip.networking.live', $session) }}"
            class="gv-btn gv-btn-primary {{ $canJoin ? '' : 'opacity-50 pointer-events-none' }}"
            @unless($canJoin) aria-disabled="true" @endunless>
            {{ get_phrase('Join session') }}
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const countdown = document.getElementById('networking-countdown');
    const introForm = document.getElementById('networking-intro');
    const storageKey = 'gv_networking_intro_{{ $session->id }}';

    if (introForm) {
        try {
            const saved = JSON.parse(localStorage.getItem(storageKey) || '{}');
            ['headline', 'bio', 'goal'].forEach((field) => {
                if (saved[field] && introForm.elements[field]) {
                    introForm.elements[field].value = saved[field];
                }
            });
        } catch (e) {
            /* noop */
        }

        introForm.addEventListener('input', () => {
            const payload = {
                headline: introForm.elements.headline.value,
                bio: introForm.elements.bio.value,
                goal: introForm.elements.goal.value,
            };
            localStorage.setItem(storageKey, JSON.stringify(payload));
        });
    }

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
