@extends('layouts.admin')

@section('title', 'Jobs Admin Dashboard')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Jobs</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid py-4" id="admin-jobs-dashboard">
    <div class="row g-3 mb-3">
        @foreach([
            ['label' => 'Total jobs', 'value' => $metrics['total_jobs'] ?? 0],
            ['label' => 'Active jobs', 'value' => $metrics['active_jobs'] ?? 0],
            ['label' => 'Total applications', 'value' => $metrics['applications'] ?? 0],
            ['label' => 'Employers', 'value' => $metrics['employers'] ?? 0],
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
    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-3">Jobs posted over time</h5>
                    <canvas id="jobs-posted-chart" height="140"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="mb-3">Applications over time</h5>
                    <canvas id="applications-chart-admin" height="140"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/jobs/adminJobsDashboard.js') }}"></script>
@endpush
