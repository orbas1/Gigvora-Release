@extends('layouts.app')

@section('title', get_phrase('Employer dashboard'))

@section('page-header')
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <p class="gv-eyebrow mb-1">{{ get_phrase('Jobs & ATS') }}</p>
            <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)]">{{ get_phrase('Talent pipeline overview') }}</h1>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('employer.jobs.create') }}" class="gv-btn gv-btn-primary">
                <i class="fa-solid fa-plus me-2"></i>{{ get_phrase('Post a job') }}
            </a>
            <a href="{{ route('employer.interviews.index') }}" class="gv-btn gv-btn-ghost">
                <i class="fa-regular fa-calendar-check me-2"></i>{{ get_phrase('Interview schedule') }}
            </a>
        </div>
    </div>
@endsection

@section('content')
    @include('components.utilities.quick-tools', ['context' => 'jobs'])

    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        @foreach([
            ['label' => get_phrase('Active jobs'), 'value' => $metrics['active_jobs'] ?? 0],
            ['label' => get_phrase('New applications (7d)'), 'value' => $metrics['new_applications'] ?? 0],
            ['label' => get_phrase('Interviews scheduled'), 'value' => $metrics['interviews'] ?? 0],
            ['label' => get_phrase('Offers extended'), 'value' => $metrics['offers'] ?? 0],
        ] as $metric)
            <div class="gv-card space-y-1">
                <p class="gv-eyebrow mb-0">{{ $metric['label'] }}</p>
                <p class="text-3xl font-semibold text-[var(--gv-color-neutral-900)] mb-0">{{ $metric['value'] }}</p>
            </div>
        @endforeach
    </div>

    <section class="gv-card mt-6 space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-lg font-semibold mb-0">{{ get_phrase('Applications over time') }}</h2>
            <select class="gv-input w-auto" id="dashboard-range">
                <option value="7">{{ get_phrase('Last 7 days') }}</option>
                <option value="30">{{ get_phrase('Last 30 days') }}</option>
            </select>
        </div>
        <canvas id="applications-chart" height="140"></canvas>
    </section>

    <section class="gv-card mt-6 space-y-3">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <h2 class="text-lg font-semibold mb-0">{{ get_phrase('Recent jobs') }}</h2>
            <a href="{{ route('employer.jobs.index') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Manage all') }}</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="text-left text-[var(--gv-color-neutral-500)] border-b border-[var(--gv-color-border)]">
                    <tr>
                        <th class="py-2 pr-4">{{ get_phrase('Title') }}</th>
                        <th class="py-2 pr-4">{{ get_phrase('Status') }}</th>
                        <th class="py-2 pr-4">{{ get_phrase('Applications') }}</th>
                        <th class="py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--gv-color-border)]">
                    @forelse($jobs as $job)
                        <tr>
                            <td class="py-3 pr-4">
                                <p class="font-medium text-[var(--gv-color-neutral-900)] mb-0">{{ $job->title }}</p>
                                <p class="gv-muted text-xs mb-0">{{ optional($job->company)->name }}</p>
                            </td>
                            <td class="py-3 pr-4">
                                <span class="gv-chip gv-chip-muted">{{ ucfirst($job->status ?? 'draft') }}</span>
                            </td>
                            <td class="py-3 pr-4">
                                <span class="font-semibold text-[var(--gv-color-neutral-900)]">{{ $job->applications_count ?? 0 }}</span>
                            </td>
                            <td class="py-3 text-right">
                                <a href="{{ route('employer.jobs.edit', $job) }}" class="gv-btn gv-btn-ghost gv-btn-sm">
                                    {{ get_phrase('Manage') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-6 text-center gv-muted">{{ get_phrase('No jobs yet. Post your first role to start receiving applications.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@push('head-scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@push('scripts')
    <script type="module" src="{{ mix('resources/js/jobs/employerDashboard.js') }}"></script>
@endpush