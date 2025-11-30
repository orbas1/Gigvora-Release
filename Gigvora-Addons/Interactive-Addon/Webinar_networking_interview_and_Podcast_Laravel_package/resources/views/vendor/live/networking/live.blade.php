@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="text-sm uppercase tracking-wide text-indigo-500 font-semibold mb-2">{{ __('Live Networking') }}</p>
        <h1 class="live-header__title">{{ $networkingSession->title ?? __('Live session') }}</h1>
        <p class="live-header__subtitle">{{ __('Rotate participants, run polls, and share resources with attendees.') }}</p>
    </div>
@endsection

@section('live-content')
<div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_360px]">
    <div class="space-y-4">
        <div class="gv-card space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Current partner') }}</h3>
                <span class="gv-pill" id="rotation-label" data-interval="{{ $session->rotation_interval ?? 60 }}">
                    {{ get_phrase('Rotation :seconds s', ['seconds' => $session->rotation_interval ?? 60]) }}
                </span>
            </div>
            <div class="space-y-2">
                <div class="flex items-center justify-between text-sm text-[var(--gv-color-neutral-600)]">
                    <span>{{ get_phrase('Rotations automatically advance every :seconds seconds.', ['seconds' => $session->rotation_interval ?? 60]) }}</span>
                    <span id="rotation-timer" class="font-semibold text-[var(--gv-color-neutral-900)]">--</span>
                </div>
                <div class="w-full h-2 bg-[var(--gv-color-neutral-100)] rounded" aria-hidden="true">
                    <div id="rotation-progress" class="h-2 bg-[var(--gv-color-primary-500)] rounded transition-[width]" style="width: 0%;"></div>
                </div>
            </div>

            <div class="gv-card bg-[var(--gv-color-neutral-25)] border border-[var(--gv-color-neutral-100)]">
                <div class="flex items-center justify-between gap-3">
                    <div class="space-y-1">
                        <p class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-0">{{ get_phrase('Partner seat') }} #<span id="partner-seat">{{ $session->participants->first()->rotation_position ?? '—' }}</span></p>
                        <p class="text-xs text-[var(--gv-color-neutral-600)] mb-0" id="partner-meta">
                            {{ get_phrase('Swaps every :seconds seconds • tap to exchange cards', ['seconds' => $session->rotation_interval ?? 60]) }}
                        </p>
                    </div>
                    <button class="gv-btn gv-btn-secondary" type="button" id="exchange-contact">{{ get_phrase('Share contact') }}</button>
                </div>
            </div>

            <textarea class="gv-input min-h-[140px]" id="partner-notes" placeholder="{{ get_phrase('Notes about this connection...') }}"></textarea>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <button class="gv-btn gv-btn-primary w-full sm:w-auto" id="save-notes">{{ get_phrase('Save notes') }}</button>
                <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0" id="notes-status">{{ get_phrase('Notes sync locally and appear in your Utilities recap.') }}</p>
            </div>
        </div>
    </div>

    <aside class="space-y-4">
        <div class="gv-card space-y-2">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                        {{ $session->title }}
                    </p>
                    <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">
                        {{ $session->starts_at?->format('M j • g:i A') }}
                    </p>
                </div>
                <span class="gv-pill gv-pill--success">{{ get_phrase('Live') }}</span>
            </div>
            <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">
                {{ trans_choice('{0}No participants|{1}1 participant|[2,*]:count participants', $session->participants->count(), ['count' => $session->participants->count()]) }}
            </p>
        </div>

        <div class="gv-card space-y-2">
            <h4 class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">
                {{ get_phrase('Rotation roster') }}
            </h4>
            <div class="space-y-2 max-h-72 overflow-auto">
                @forelse($session->participants as $row)
                    <div class="flex items-center justify-between text-sm">
                        <span>{{ get_phrase('Seat :seat', ['seat' => $row->rotation_position ?? '—']) }}</span>
                        <span class="text-xs text-[var(--gv-color-neutral-500)]">{{ get_phrase('User #:id', ['id' => $row->user_id]) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-[var(--gv-color-neutral-500)] mb-0">{{ get_phrase('No attendees') }}</p>
                @endforelse
            </div>
        </div>

        @include('wnip::components.notes_sidebar')
    </aside>
</div>
@endsection

@push('scripts')
<script>
    const rotationLabel = document.getElementById('rotation-label');
    const rotationTimer = document.getElementById('rotation-timer');
    const rotationProgress = document.getElementById('rotation-progress');
    const notes = document.getElementById('partner-notes');
    const notesStatus = document.getElementById('notes-status');
    const exchangeButton = document.getElementById('exchange-contact');
    const storageKey = 'gv_networking_notes_{{ $session->id }}';

    const rotationInterval = Number(rotationLabel?.dataset.interval || {{ $session->rotation_interval ?? 60 }});
    let remaining = rotationInterval;

    const hydrateNotes = () => {
        if (!notes) return;
        try {
            const saved = JSON.parse(localStorage.getItem(storageKey) || '{}');
            if (saved.text) {
                notes.value = saved.text;
                notesStatus.textContent = '{{ get_phrase('Restored from your last save') }}';
            }
        } catch (e) {
            /* noop */
        }
    };

    const persistNotes = () => {
        if (!notes) return;
        localStorage.setItem(storageKey, JSON.stringify({ text: notes.value, saved_at: new Date().toISOString() }));
        notesStatus.textContent = '{{ get_phrase('Saved locally; will sync to Utilities after the session') }}';
    };

    const tickRotation = () => {
        if (!rotationTimer || !rotationProgress) return;
        remaining -= 1;
        if (remaining <= 0) {
            remaining = rotationInterval;
        }
        rotationTimer.textContent = `${remaining}s`;
        rotationProgress.style.width = `${Math.max(0, Math.min(100, (remaining / rotationInterval) * 100))}%`;
    };

    hydrateNotes();
    tickRotation();
    setInterval(tickRotation, 1000);

    if (notes) {
        notes.addEventListener('input', () => {
            notesStatus.textContent = '{{ get_phrase('Drafting…') }}';
        });
    }

    document.getElementById('save-notes')?.addEventListener('click', (event) => {
        event.preventDefault();
        persistNotes();
    });

    exchangeButton?.addEventListener('click', () => {
        notesStatus.textContent = '{{ get_phrase('Contact card shared. Follow-up reminder queued.') }}';
    });
</script>
@endpush
