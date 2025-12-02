@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="gv-eyebrow mb-1">{{ get_phrase('Interview detail') }}</p>
        <h1 class="live-header__title">{{ get_phrase('Interview overview & prep') }}</h1>
        <p class="live-header__subtitle">{{ get_phrase('Review interview details, join links, and attachments.') }}</p>
    </div>
@endsection

@section('live-content')
@php
    $primarySlot = $primarySlot ?? $interview->slots->sortBy('starts_at')->first();
    $attachments = collect($interview->metadata['attachments'] ?? []);
@endphp
<div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_320px]" id="candidate-interview"
    data-waiting-url="{{ route('wnip.interviews.waiting', ['interview' => $interview]) }}"
    data-live-url="{{ route('wnip.interviews.live', ['interview' => $interview]) }}"
    data-start-at="{{ $primarySlot?->starts_at?->toIso8601String() ?? $interview->scheduled_at?->toIso8601String() }}">
    <div class="space-y-4">
        <div class="gv-card space-y-2">
            <h2 class="text-xl font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                {{ $interview->title }}
            </h2>
            <p class="text-sm text-[var(--gv-color-neutral-500)] mb-0">
                {{ $interview->scheduled_at?->format('M j • g:i A') ?? get_phrase('TBD') }}
                • {{ $interview->duration_minutes ?? 0 }} {{ get_phrase('mins') }}
                • {{ $primarySlot?->meeting_link ? get_phrase('Video') : ($interview->location ?? get_phrase('Virtual')) }}
            </p>
        </div>
        <div class="gv-card space-y-2">
            <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Interviewers') }}</h3>
            @if($primarySlot)
                <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                    {{ optional($primarySlot->interviewer)->name ?? get_phrase('Interviewer') }}
                </p>
            @else
                <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">{{ get_phrase('Interviewer details will appear once scheduled.') }}</p>
            @endif
        </div>
        <div class="gv-card space-y-2">
            <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Instructions') }}</h3>
            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                {{ $interview->description ?: get_phrase('Join 5 minutes early and test your mic.') }}
            </p>
        </div>
    </div>
    <aside class="space-y-4">
        <div class="gv-card space-y-3">
            <h4 class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Join') }}</h4>
            <button class="gv-btn gv-btn-primary w-full" id="join-waiting">{{ get_phrase('Join waiting room') }}</button>
            <button class="gv-btn gv-btn-ghost w-full opacity-50 pointer-events-none" id="join-interview" aria-disabled="true"
                data-ready-label="{{ get_phrase('Join interview') }}">
                {{ get_phrase('Join interview') }}
            </button>
        </div>
        <div class="gv-card space-y-2">
            <h4 class="text-sm font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Attachments') }}</h4>
            @if($attachments->isNotEmpty())
                <ul class="space-y-1 text-sm">
                    @foreach($attachments as $attachment)
                        <li>
                            <a href="{{ $attachment['url'] ?? '#' }}" class="text-[var(--gv-color-primary-600)]">
                                {{ $attachment['label'] ?? get_phrase('Attachment') }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">{{ get_phrase('No attachments shared yet.') }}</p>
            @endif
        </div>
    </aside>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/interviewDashboard.js') }}"></script>
@endpush
