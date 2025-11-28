@extends('layouts.app')

@section('title', 'Employer Dashboard')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/employer">Employer</a></li>
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="employer-dashboard">
    <div class="row g-3 mb-3">
        @foreach([
            ['label' => 'Active jobs', 'value' => $metrics['active_jobs'] ?? 0],
            ['label' => 'New applications (7d)', 'value' => $metrics['new_applications'] ?? 0],
            ['label' => 'Interviews scheduled', 'value' => $metrics['interviews'] ?? 0],
            ['label' => 'Offers made', 'value' => $metrics['offers'] ?? 0],
        ] as $metric)
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted text-uppercase small">{{ $metric['label'] }}</div>
                    <div class="display-6">{{ $metric['value'] }}</div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Applications over time</h5>
                <select class="form-select w-auto" id="dashboard-range">
                    <option value="7">Last 7 days</option>
                    <option value="30">Last 30 days</option>
                </select>
            </div>
            <canvas id="applications-chart" height="120"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Jobs</h5>
                <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary">Post a Job</a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Applications</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(($jobs ?? []) as $job)
                            <tr>
                                <td>{{ $job->title ?? '' }}</td>
                                <td><span class="badge bg-light text-dark text-uppercase">{{ $job->status ?? 'Draft' }}</span></td>
                                <td>{{ $job->views ?? 0 }}</td>
                                <td>{{ $job->applications_count ?? 0 }}</td>
                                <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('employer.jobs.edit', $job->id ?? null) }}">Manage</a></td>
                            </tr>
                        @endforeach
                        @if(empty($jobs))
                            <tr><td colspan="5" class="text-center text-muted py-4">No jobs yet.</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/jobs/employerDashboard.js') }}"></script>
@endpush
