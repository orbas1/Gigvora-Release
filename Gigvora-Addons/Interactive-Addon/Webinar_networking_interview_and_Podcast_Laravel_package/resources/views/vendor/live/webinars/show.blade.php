@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="text-sm uppercase tracking-wide text-indigo-500 font-semibold mb-2">{{ __('Webinars') }}</p>
        <h1 class="live-header__title">{{ $webinar->title }}</h1>
        <p class="live-header__subtitle text-base">{{ __('Hosted by :host', ['host' => optional($webinar->host)->name ?? __('Host')]) }}</p>
    </div>
@endsection

@section('live-content')
@php
    $startUtc = optional($webinar->starts_at)?->utc();
    $endUtc = optional($webinar->ends_at)?->utc() ?? optional($startUtc)?->copy()->addHour();
    $calendarUrl = $startUtc
        ? sprintf(
            'https://calendar.google.com/calendar/render?action=TEMPLATE&text=%s&dates=%s/%s&details=%s',
            urlencode($webinar->title),
            $startUtc->format('Ymd\THis\Z'),
            $endUtc?->format('Ymd\THis\Z'),
            urlencode(strip_tags($webinar->description ?? '')),
        )
        : null;
@endphp
<div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_360px]">
    <div class="space-y-4">
        <div class="gv-card space-y-3">
            <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
                <div>
                    <p class="gv-eyebrow mb-1">{{ get_phrase('Webinar') }}</p>
                    <h2 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                        {{ $webinar->title }}
                    </h2>
                    <p class="text-sm text-[var(--gv-color-neutral-500)] mb-0">
                        {{ get_phrase('Hosted by :host', ['host' => optional($webinar->host)->name ?? get_phrase('Host')]) }}
                        • {{ $webinar->starts_at?->format('M j • g:i A') }}
                        @if ($webinar->ends_at)
                            • {{ $webinar->ends_at?->diffInMinutes($webinar->starts_at) }} {{ get_phrase('mins') }}
                        @endif
                    </p>
                </div>
                <span class="gv-pill {{ $webinar->is_live ? 'gv-pill--danger' : '' }}">
                    {{ $webinar->is_live ? get_phrase('Live now') : ucfirst($webinar->status ?? 'Scheduled') }}
                </span>
            </div>
            @if ($webinar->waiting_room_message)
                <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                    {{ $webinar->waiting_room_message }}
                </p>
                @endif
            </div>

        <div class="gv-card space-y-4">
            <div class="space-y-2">
                <h3 class="text-lg font-semibold text-[var(--gv-color-neutral-900)]">
                    {{ get_phrase('Description') }}
                </h3>
                <p class="text-sm leading-relaxed text-[var(--gv-color-neutral-700)]">
                    {!! nl2br(e($webinar->description)) !!}
                </p>
            </div>
            <div class="grid gap-3 md:grid-cols-2">
                <div class="p-3 rounded-xl bg-[var(--gv-color-surface-alt)] border border-[var(--gv-color-border)]">
                    <p class="gv-eyebrow mb-1">{{ get_phrase('Schedule') }}</p>
                    <p class="text-sm text-[var(--gv-color-neutral-700)] mb-1">
                        {{ optional($webinar->starts_at)->format('M j, Y • g:i A') ?? get_phrase('TBD') }}
                    </p>
                    <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">
                        {{ $webinar->ends_at ? $webinar->ends_at->diffInMinutes($webinar->starts_at) . ' ' . get_phrase('mins planned') : get_phrase('Duration announced in the live room') }}
                    </p>
                </div>
                <div class="p-3 rounded-xl bg-[var(--gv-color-surface-alt)] border border-[var(--gv-color-border)]">
                    <p class="gv-eyebrow mb-1">{{ get_phrase('Format & access') }}</p>
                    <p class="text-sm text-[var(--gv-color-neutral-700)] mb-1">
                        {{ $webinar->is_paid ? get_phrase('Ticketed session') : get_phrase('Open registration') }}
                    </p>
                    <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">
                        {{ get_phrase('Replay shared with registrants after the session.') }}
                    </p>
                </div>
            </div>
            <div>
                <h4 class="text-base font-semibold text-[var(--gv-color-neutral-900)] mb-2">
                    {{ get_phrase('Agenda') }}
                </h4>
                <ul class="list-disc pl-5 text-sm text-[var(--gv-color-neutral-600)] space-y-1">
                    <li>{{ get_phrase('Key talking points & market insights') }}</li>
                    <li>{{ get_phrase('Live Q&A and networking prompts') }}</li>
                    <li>{{ get_phrase('Replay and resources shared after the session') }}</li>
                </ul>
            </div>
        </div>

        @if($webinar->recordings->isNotEmpty())
            <div class="gv-card space-y-3">
                <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">
                    {{ get_phrase('Recordings & highlights') }}
                </h3>
                <div class="space-y-2">
                    @foreach($webinar->recordings as $recording)
                        <div class="flex items-center justify-between rounded-xl border border-[var(--gv-color-border)] p-3">
                            <div>
                                <p class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                                    {{ $recording->title ?? get_phrase('Replay') }}
                                </p>
                                <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">
                                    {{ $recording->duration ? $recording->duration . 's' : get_phrase('Duration TBD') }}
                                </p>
                            </div>
                            <a class="gv-btn gv-btn-ghost" href="{{ $recording->path }}" target="_blank">
                                {{ get_phrase('Watch') }}
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="gv-card space-y-3">
            <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Trust & safety') }}</h3>
            <ul class="list-disc pl-5 text-sm text-[var(--gv-color-neutral-600)] space-y-1 mb-0">
                <li>{{ get_phrase('Hosts review questions and moderate chat; abusive attendees are removed.') }}</li>
                <li>{{ get_phrase('Paid replay links are scoped to registered attendees and expire after distribution.') }}</li>
                <li>{{ get_phrase('Consent and regional retention policies are honored for recordings and Q&A logs.') }}</li>
            </ul>
        </div>
    </div>

    <aside class="space-y-4">
        <div class="gv-card space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                        {{ $webinar->is_paid ? get_phrase('Paid session') : get_phrase('Free session') }}
                    </p>
                    <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">
                        {{ trans_choice('{0}Be first to register|{1}1 attendee registered|[2,*]:count attendees registered', $webinar->registrations->count(), ['count' => $webinar->registrations->count()]) }}
                    </p>
                </div>
                @if ($webinar->is_paid)
                    <span class="gv-pill gv-pill--warning">
                        {{ get_phrase(':amount GBP', ['amount' => number_format($webinar->price ?? 0, 2)]) }}
                    </span>
                @endif
            </div>

            @auth
                <form method="post" action="{{ route('wnip.webinars.register', $webinar) }}" class="space-y-3">
                    @csrf
                    <button class="gv-btn gv-btn-primary w-full" type="submit">
                        {{ $registration ? get_phrase('Registered') : get_phrase('Register now') }}
                    </button>
                </form>
                <a class="gv-btn gv-btn-ghost w-full" href="{{ route('wnip.webinars.waiting', $webinar) }}">
                    {{ get_phrase('Join waiting room') }}
                </a>
                @if ($calendarUrl)
                    <a class="gv-btn gv-btn-ghost w-full" target="_blank" rel="noopener" href="{{ $calendarUrl }}">
                        {{ get_phrase('Add to Google Calendar') }}
                    </a>
                @endif
            @else
                <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                    {{ get_phrase('Sign in to register or join the waiting room.') }}
                </p>
            @endauth
        </div>

        <div class="gv-card space-y-3">
            <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Share') }}</h3>
            <div class="flex flex-wrap gap-2">
                <a class="gv-btn gv-btn-ghost flex-1" href="https://twitter.com/intent/tweet?text={{ urlencode($webinar->title) }}"
                    target="_blank">
                    {{ get_phrase('Share on X') }}
                </a>
                <a class="gv-btn gv-btn-ghost flex-1" href="mailto:?subject={{ urlencode($webinar->title) }}">
                    {{ get_phrase('Email link') }}
                </a>
            </div>
        </div>

        <div class="gv-card space-y-2">
            <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Readiness checklist') }}</h3>
            <ul class="space-y-1 text-sm text-[var(--gv-color-neutral-600)] mb-0">
                <li>{{ get_phrase('Confirm camera/mic permissions before entering the live room.') }}</li>
                <li>{{ get_phrase('Join five minutes early so countdown + reminders stay in sync.') }}</li>
                <li>{{ get_phrase('Enable notifications for host announcements and Q&A prompts.') }}</li>
            </ul>
        </div>

        @include('components.utilities.quick-tools', ['context' => 'interactive', 'variant' => 'card'])
    </aside>
</div>
@endsection
