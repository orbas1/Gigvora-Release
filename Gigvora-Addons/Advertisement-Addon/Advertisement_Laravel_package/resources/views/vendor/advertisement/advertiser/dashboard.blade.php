@extends('layouts.app')

@section('title', 'Gigvora Ads Dashboard')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ads Dashboard</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4">
<div id="ads-dashboard-feedback" class="alert alert-warning d-none" role="status"></div>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Ads Performance</h1>
        <p class="text-muted mb-0">Monitor your campaigns and take quick actions.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('advertisement.campaigns.create') }}" class="btn btn-primary">Create Campaign</a>
        <a href="{{ route('advertisement.campaigns.index') }}" class="btn btn-outline-secondary">View All Campaigns</a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <label class="mb-0 fw-semibold">Date Range:</label>
            <input type="text" id="ads-date-range" class="form-control" style="width: 220px" placeholder="Select range" />
        </div>
        <button class="btn btn-light" id="refresh-dashboard">Refresh</button>
    </div>
</div>

<div class="row g-3" id="ads-kpis">
    @php
        $stats = $kpis ?? [
            ['label' => 'Total Spend', 'value' => '$0', 'id' => 'spend'],
            ['label' => 'Impressions', 'value' => '0', 'id' => 'impressions'],
            ['label' => 'Clicks', 'value' => '0', 'id' => 'clicks'],
            ['label' => 'Conversions', 'value' => '0', 'id' => 'conversions'],
            ['label' => 'Avg CPC', 'value' => '$0.00', 'id' => 'cpc'],
            ['label' => 'Avg CPA', 'value' => '$0.00', 'id' => 'cpa'],
            ['label' => 'Avg CPM', 'value' => '$0.00', 'id' => 'cpm'],
        ];
    @endphp
    @foreach($stats as $stat)
    <div class="col-6 col-md-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">{{ $stat['label'] }}</p>
                <h4 class="fw-bold mb-0" id="kpi-{{ $stat['id'] }}">{{ $stat['value'] }}</h4>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Performance Trends</h5>
            <small class="text-muted">Spend vs Impressions vs Clicks</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <select id="chart-metric" class="form-select form-select-sm">
                <option value="all">All Metrics</option>
                <option value="spend">Spend</option>
                <option value="impressions">Impressions</option>
                <option value="clicks">Clicks</option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <canvas id="ads-performance-chart" height="120" class="w-100"></canvas>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Top Campaigns</h5>
        <a href="{{ route('advertisement.campaigns.index') }}" class="btn btn-sm btn-outline-primary">View all</a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Impressions</th>
                    <th>Clicks</th>
                    <th>CTR</th>
                    <th>Spend</th>
                </tr>
            </thead>
            <tbody id="top-campaigns-body">
                @forelse(($topCampaigns ?? []) as $campaign)
                    <tr data-id="{{ $campaign['id'] ?? '' }}" class="campaign-row">
                        <td class="fw-semibold">{{ $campaign['name'] ?? 'Untitled' }}</td>
                        <td><span class="badge bg-success">{{ $campaign['status'] ?? 'Active' }}</span></td>
                        <td>{{ $campaign['impressions'] ?? 0 }}</td>
                        <td>{{ $campaign['clicks'] ?? 0 }}</td>
                        <td>{{ $campaign['ctr'] ?? '0%' }}</td>
                        <td>{{ $campaign['spend'] ?? '$0.00' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No campaigns yet. Start by creating your first campaign.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</div>
</div>

<div class="modal fade" id="campaignDetailModal" tabindex="-1" aria-labelledby="campaignDetailLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="campaignDetailLabel">Campaign details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="campaign-detail-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ mix('js/advertisement/dashboard.js') }}" defer></script>
@endpush
