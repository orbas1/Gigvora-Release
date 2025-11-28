@extends('layouts.app')

@section('title', 'Freelancer Dashboard')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Freelancer Dashboard</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="freelancer-dashboard">
    @component('vendor.freelance.components.dashboard_kpi_cards', ['kpis' => $kpis ?? []])
    @endcomponent

    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title">Earnings (last 6 months)</h5>
            <div id="earnings-chart" class="chart-placeholder" style="height:280px;"></div>
        </div>
    </div>

    <div class="row mt-4 g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Open Contracts</span>
                    <a href="/freelance/contracts" class="small">View all</a>
                </div>
                <div class="card-body">
                    @forelse($openContracts ?? [] as $contract)
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="fw-semibold">{{ $contract['title'] }}</div>
                                <small class="text-muted">{{ $contract['client'] }}</small>
                            </div>
                            <span class="badge bg-light text-dark">{{ $contract['status'] }}</span>
                        </div>
                    @empty
                        <p class="text-muted">No open contracts.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Latest Messages</span>
                    <a href="/messages" class="small">Open inbox</a>
                </div>
                <div class="card-body">
                    @forelse($messages ?? [] as $message)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-semibold">{{ $message['from'] }}</div>
                                <small class="text-muted">{{ $message['excerpt'] }}</small>
                            </div>
                            <small class="text-muted">{{ $message['time'] }}</small>
                        </div>
                    @empty
                        <p class="text-muted">No recent messages.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Recommended Projects</span>
            <button class="btn btn-sm btn-outline-secondary" id="refresh-recommended">Refresh</button>
        </div>
        <div class="card-body" id="recommended-projects" data-fetch-url="{{ $recommendedUrl ?? '' }}">
            <div class="text-muted">Loading recommendations...</div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/freelance/freelancerDashboard.js') }}"></script>
@endpush
