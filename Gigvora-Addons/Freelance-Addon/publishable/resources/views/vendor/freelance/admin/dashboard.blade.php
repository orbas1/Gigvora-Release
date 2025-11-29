@extends('layouts.admin')

@section('title', 'Freelance Admin Dashboard')

@section('content')
<div class="container-fluid py-4" id="admin-freelance-dashboard">
    <h1 class="mb-4">Freelance Overview</h1>
    @component('vendor.freelance.components.dashboard_kpi_cards', ['kpis' => $kpis ?? []])
    @endcomponent

    <div class="row mt-4 g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Platform Metrics</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li>Total clients using freelance: {{ $metrics['clients'] ?? 0 }}</li>
                        <li>Active gigs/projects: {{ $metrics['active_items'] ?? 0 }}</li>
                        <li>Total escrow volume: {{ $metrics['escrow'] ?? '$0' }}</li>
                        <li>Disputes open: {{ $metrics['disputes'] ?? 0 }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header">Trends</div>
                <div class="card-body">
                    <div id="volume-chart" style="height:200px;"></div>
                    <div id="dispute-chart" style="height:200px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/freelance/adminFreelanceDashboard.js') }}"></script>
@endpush
