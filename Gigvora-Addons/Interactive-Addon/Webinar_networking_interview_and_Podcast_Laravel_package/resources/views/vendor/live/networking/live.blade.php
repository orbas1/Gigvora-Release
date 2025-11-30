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
                <span class="gv-pill">{{ get_phrase('Rotation :seconds s', ['seconds' => $session->rotation_interval ?? 60]) }}</span>
            </div>
            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                {{ get_phrase('Rotations automatically advance every :seconds seconds. Share quick intros and capture notes below.', ['seconds' => $session->rotation_interval ?? 60]) }}
            </p>
            <textarea class="gv-input min-h-[140px]" placeholder="{{ get_phrase('Notes about this connection...') }}"></textarea>
            <button class="gv-btn gv-btn-primary w-full">{{ get_phrase('Save notes') }}</button>
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
