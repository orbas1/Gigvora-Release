@extends('wnip::layouts.live')

@section('live-header')
    <div>
        <p class="gv-eyebrow mb-1">{{ get_phrase('Candidate schedule') }}</p>
        <h1 class="live-header__title">{{ get_phrase('Upcoming interviews') }}</h1>
        <p class="live-header__subtitle">{{ get_phrase('Keep track of interviews, waiting rooms, and follow-ups.') }}</p>
    </div>
@endsection

@section('live-content')
@php
    $upcoming = $upcoming ?? [
        ['role' => 'Senior Engineer', 'company' => 'Acme', 'datetime' => 'May 7, 10:00', 'status' => 'Confirmed'],
        ['role' => 'Product Manager', 'company' => 'Beta', 'datetime' => 'May 10, 14:00', 'status' => 'Pending'],
    ];
@endphp
<div class="grid gap-6 lg:grid-cols-[minmax(0,1.6fr)_320px]" id="interview-dashboard">
    <div class="space-y-4">
        <div class="gv-card flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-[var(--gv-color-neutral-900)] mb-1">{{ get_phrase('Upcoming interviews') }}</h2>
                <p class="gv-muted mb-0">{{ get_phrase('Keep track of your interview schedule.') }}</p>
            </div>
            <button class="gv-btn gv-btn-ghost">{{ get_phrase('Export') }}</button>
        </div>

        <div class="gv-card space-y-3">
            <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Upcoming') }}</h3>
            <div class="space-y-2" id="upcoming-list">
                @foreach($upcoming as $item)
                    <div class="flex flex-col gap-2 rounded-xl border border-[var(--gv-color-border)] p-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm font-semibold text-[var(--gv-color-neutral-900)] mb-1">
                                {{ $item['role'] }} {{ get_phrase('at') }} {{ $item['company'] }}
                            </p>
                            <p class="text-xs text-[var(--gv-color-neutral-500)] mb-0">{{ $item['datetime'] }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="gv-pill">{{ $item['status'] }}</span>
                            <a href="{{ route('wnip.interviews.show', ['interview' => $loop->index + 1]) ?? '#' }}"
                                class="gv-btn gv-btn-ghost text-sm">
                                {{ get_phrase('View details') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="gv-card">
            <h3 class="text-base font-semibold text-[var(--gv-color-neutral-900)] mb-2">{{ get_phrase('Past interviews') }}</h3>
            <p class="text-sm text-[var(--gv-color-neutral-600)] mb-0">
                {{ get_phrase('Your previous interviews will appear here.') }}
            </p>
        </div>
    </div>

    <aside>
        @include('wnip::components.calendar_widget')
    </aside>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/interviewDashboard.js') }}"></script>
@endpush
