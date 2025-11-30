@extends('layouts.app')

@section('title', get_phrase('Notifications Center'))

@section('page-header')
    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <p class="gv-eyebrow">{{ get_phrase('Utilities') }}</p>
            <h1 class="text-3xl font-semibold text-[var(--gv-color-neutral-900)]">
                {{ get_phrase('Notifications center') }}
            </h1>
            <p class="gv-muted max-w-2xl mt-1">
                {{ get_phrase('Stay on top of invites, approvals, and cross-addon alerts across Jobs, Freelance, Interactive, and Groups.') }}
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('utilities.saved.index') }}" class="gv-btn gv-btn-ghost">
                <i class="fa-regular fa-bookmark me-1"></i> {{ get_phrase('Saved items') }}
            </a>
            <a href="{{ route('utilities.calendar.index') }}" class="gv-btn gv-btn-primary">
                <i class="fa-regular fa-calendar me-1"></i> {{ get_phrase('Open calendar') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <section class="gv-card gv-notification-hero">
            <div class="grid gap-4 sm:grid-cols-3">
                <div class="gv-stat-tile">
                    <p class="gv-stat-tile__label">{{ get_phrase('Unread') }}</p>
                    <p class="gv-stat-tile__value" data-gv-notification-stat="unread">{{ number_format($stats['unread']) }}</p>
                    <p class="gv-stat-tile__meta">{{ get_phrase('Awaiting your attention') }}</p>
                </div>
                <div class="gv-stat-tile">
                    <p class="gv-stat-tile__label">{{ get_phrase('Last 7 days') }}</p>
                    <p class="gv-stat-tile__value">{{ number_format($stats['week']) }}</p>
                    <p class="gv-stat-tile__meta">{{ get_phrase('Cross-addon updates') }}</p>
                </div>
                <div class="gv-stat-tile">
                    <p class="gv-stat-tile__label">{{ get_phrase('Total stored') }}</p>
                    <p class="gv-stat-tile__value">{{ number_format($stats['total']) }}</p>
                    <p class="gv-stat-tile__meta">{{ get_phrase('Rolling 90-day retention') }}</p>
                </div>
            </div>
        </section>

        <div class="grid gap-6 lg:grid-cols-[minmax(0,2fr),minmax(280px,1fr)]">
            <div class="space-y-6">
                @foreach ($streams as $stream)
                    @if($stream['groups']->isNotEmpty())
                        <section class="gv-card">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-semibold text-[var(--gv-color-neutral-900)]">
                                    {{ $stream['label'] }}
                                </h2>
                                <span class="gv-chip gv-chip-muted">
                                    {{ number_format($stream['groups']->sum(fn($group) => $group['items']->count())) }}
                                </span>
                            </div>
                            <div class="gv-notification-stream" data-gv-notification-stream="{{ $stream['key'] }}">
                                @foreach ($stream['groups'] as $group)
                                    <div class="gv-notification-stream__group">
                                        <p class="gv-notification-stream__group-label">{{ $group['label'] }}</p>
                                        @foreach ($group['items'] as $notification)
                                            @include('utilities.partials.notification-row', ['notification' => $notification, 'timezone' => $timezone])
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                @endforeach
            </div>

            <aside class="space-y-6">
                <section class="gv-card gv-notification-filter-card">
                    <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)] mb-3">
                        {{ get_phrase('Filter by type') }}
                    </h3>
                    <div class="flex flex-col gap-2" data-gv-notification-filters>
                        <button type="button" class="gv-notification-filter gv-notification-filter--active" data-gv-notification-filter data-gv-notification-filter-type="all">
                            <span>{{ get_phrase('All activity') }}</span>
                            <span class="gv-notification-filter__count">{{ number_format($stats['total']) }}</span>
                        </button>
                        @forelse($filters as $filter)
                            <button type="button"
                                class="gv-notification-filter"
                                data-gv-notification-filter
                                data-gv-notification-filter-type="{{ $filter['type'] ?? 'other' }}">
                                <span>{{ $filter['label'] }}</span>
                                <span class="gv-notification-filter__count">{{ number_format($filter['count']) }}</span>
                            </button>
                        @empty
                            <p class="gv-muted text-sm mb-0">{{ get_phrase('No category data yet.') }}</p>
                        @endforelse
                    </div>
                </section>

                @if($interviewReminders->isNotEmpty())
                    <section class="gv-card">
                        <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)] mb-3">
                            {{ get_phrase('Interview reminders') }}
                        </h3>
                        <div class="flex flex-col gap-3">
                            @foreach($interviewReminders as $reminder)
                                <div class="gv-reminder-row">
                                    <p class="gv-reminder-row__title mb-1">{{ $reminder['title'] }}</p>
                                    <p class="gv-reminder-row__meta mb-1">
                                        {{ get_phrase('Reminds :lead before', ['lead' => $reminder['lead_time']]) }}
                                    </p>
                                    <p class="gv-reminder-row__caption mb-0">
                                        {{ $reminder['starts_at']->timezone($timezone)->format('M d · H:i') }} · {{ ucfirst($reminder['status']) }}
                                    </p>
                                    @if(!empty($reminder['cta_url']))
                                        <a href="{{ $reminder['cta_url'] }}" class="gv-btn gv-btn-text mt-2">
                                            {{ get_phrase('Open details') }}
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <section class="gv-card">
                    <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)] mb-3">
                        {{ get_phrase('Interview digest') }}
                    </h3>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <div class="gv-stat-pill">
                            <p class="gv-stat-pill__label">{{ get_phrase('Scheduled') }}</p>
                            <p class="gv-stat-pill__value">{{ number_format($interviewDigest['scheduled'] ?? 0) }}</p>
                        </div>
                        <div class="gv-stat-pill">
                            <p class="gv-stat-pill__label">{{ get_phrase('Rescheduled') }}</p>
                            <p class="gv-stat-pill__value">{{ number_format($interviewDigest['rescheduled'] ?? 0) }}</p>
                        </div>
                        <div class="gv-stat-pill">
                            <p class="gv-stat-pill__label">{{ get_phrase('Completed') }}</p>
                            <p class="gv-stat-pill__value">{{ number_format($interviewDigest['completed'] ?? 0) }}</p>
                        </div>
                        <div class="gv-stat-pill">
                            <p class="gv-stat-pill__label">{{ get_phrase('Cancelled') }}</p>
                            <p class="gv-stat-pill__value">{{ number_format($interviewDigest['cancelled'] ?? 0) }}</p>
                        </div>
                    </div>
                </section>

                <section class="gv-card">
                    <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)] mb-3">
                        {{ get_phrase('Cross-addon quick links') }}
                    </h3>
                    <div class="flex flex-col gap-2">
                        @if($integrationLinks['jobsSaved'])
                            <a href="{{ $integrationLinks['jobsSaved'] }}" class="gv-chip gv-chip-action">
                                <span><i class="fa-solid fa-briefcase me-2"></i>{{ get_phrase('Saved jobs') }}</span>
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        @endif
                        @if($integrationLinks['freelanceDashboard'])
                            <a href="{{ $integrationLinks['freelanceDashboard'] }}" class="gv-chip gv-chip-action">
                                <span><i class="fa-solid fa-handshake-angle me-2"></i>{{ get_phrase('Freelance workspace') }}</span>
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        @endif
                        @if($integrationLinks['liveHub'])
                            <a href="{{ $integrationLinks['liveHub'] }}" class="gv-chip gv-chip-action">
                                <span><i class="fa-solid fa-broadcast-tower me-2"></i>{{ get_phrase('Live & events hub') }}</span>
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        @endif
                        @if($integrationLinks['utilitiesHub'])
                            <a href="{{ $integrationLinks['utilitiesHub'] }}" class="gv-chip gv-chip-action">
                                <span><i class="fa-solid fa-bolt me-2"></i>{{ get_phrase('Utilities hub') }}</span>
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        @endif
                    </div>
                </section>
            </aside>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ mix('js/utilities/notifications.js') }}" defer></script>
@endpush
