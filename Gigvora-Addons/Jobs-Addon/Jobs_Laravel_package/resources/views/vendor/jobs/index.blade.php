@extends('layouts.app')

@section('title', get_phrase('Jobs'))

@section('page-header')
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div class="space-y-2">
            <p class="gv-eyebrow">{{ get_phrase('Careers & ATS') }}</p>
            <h1 class="text-3xl font-semibold text-[var(--gv-color-neutral-900)]">
                {{ get_phrase('Discover roles across the Gigvora network') }}
            </h1>
            <p class="gv-muted max-w-2xl">
                {{ get_phrase('Search curated opportunities, track applications, and stay interview-ready with Utilities reminders.') }}
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('employer.jobs.create') }}" class="gv-btn gv-btn-secondary">
                <i class="fa-solid fa-briefcase me-2"></i>{{ get_phrase('Post a Job') }}
            </a>
            <a href="{{ route('jobs.saved') }}" class="gv-btn gv-btn-ghost">
                <i class="fa-regular fa-bookmark me-2"></i>{{ get_phrase('Saved Jobs') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    @php($totalJobs = $jobs->total())
    @include('components.utilities.quick-tools', ['context' => 'jobs'])

    <div id="jobs-search-app" class="grid gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">
        <aside class="space-y-6">
            <div class="gv-card space-y-2">
                <p class="gv-eyebrow mb-1">{{ get_phrase('Marketplace snapshot') }}</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-semibold text-[var(--gv-color-neutral-900)]">
                        {{ number_format($totalJobs) }}
                    </span>
                    <span class="gv-muted">{{ get_phrase('open roles') }}</span>
                </div>
                <p class="gv-muted text-sm mb-0">
                    {{ get_phrase('Feeds refresh in real-time across companies, startups, and creators.') }}
                </p>
            </div>
            @include('vendor.jobs.components.filter_bar')
        </aside>

        <section class="space-y-4">
            <div class="gv-card p-4 space-y-3">
                <form id="job-search-form" class="grid gap-3 md:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto]" action="{{ route('jobs.index') }}">
                    <div class="flex flex-col gap-2">
                        <label class="gv-label" for="keywords">{{ get_phrase('Job title or keywords') }}</label>
                        <input type="text" id="keywords" name="keywords" class="gv-input"
                               placeholder="{{ get_phrase('e.g. Product Designer') }}" value="{{ request('keywords') }}">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="gv-label" for="location">{{ get_phrase('Location') }}</label>
                        <input type="text" id="location" name="location" class="gv-input"
                               placeholder="{{ get_phrase('Remote, Lagos, Berlin...') }}" value="{{ request('location') }}">
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="gv-btn gv-btn-primary w-full md:w-auto">
                            <i class="fa-solid fa-magnifying-glass me-2"></i>{{ get_phrase('Search') }}
                        </button>
                    </div>
                </form>
                <div class="flex flex-wrap items-center gap-3 text-sm">
                    <span class="gv-muted">{{ get_phrase('Showing') }} {{ $jobs->count() }}
                        {{ get_phrase('roles') }}</span>
                    <button type="button" class="gv-btn gv-btn-ghost gv-btn-sm" id="apply-filters">
                        <i class="fa-solid fa-sliders me-2"></i>{{ get_phrase('Apply filters') }}
                    </button>
                </div>
            </div>

            <div id="job-results-list" class="space-y-3">
                @include('vendor.jobs.components.job_results', ['jobs' => $jobs])
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script type="module" src="{{ mix('resources/js/jobs/jobsSearch.js') }}"></script>
@endpush
