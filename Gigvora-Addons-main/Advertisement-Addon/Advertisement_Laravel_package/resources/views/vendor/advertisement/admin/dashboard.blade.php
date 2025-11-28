@extends('admin.layouts.app')

@section('title', 'Ads Admin Dashboard')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Ads Dashboard</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row g-3 mb-3">
    @foreach([
        ['label' => 'Total Ad Revenue', 'id' => 'admin-revenue'],
        ['label' => 'Total Impressions', 'id' => 'admin-impressions'],
        ['label' => 'Total Clicks', 'id' => 'admin-clicks'],
        ['label' => 'Conversions', 'id' => 'admin-conversions'],
    ] as $metric)
    <div class="col-md-3">
        <div class="card h-100">
            <div class="card-body">
                <p class="text-muted small mb-1">{{ $metric['label'] }}</p>
                <h4 class="fw-bold mb-0" id="{{ $metric['id'] }}">--</h4>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Revenue Over Time</h5>
                <input type="text" id="admin-revenue-range" class="form-control form-control-sm" style="width: 180px;" placeholder="Date range">
            </div>
            <div class="card-body">
                <canvas id="admin-revenue-chart" height="120" class="w-100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Moderation</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">Pending ads waiting for review.</p>
                <div class="display-6" id="admin-pending-count">{{ $pendingCount ?? 0 }}</div>
                <a href="{{ route('advertisement.admin.moderation.index') }}" class="btn btn-primary mt-3">Open Queue</a>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5 class="mb-0">Top Advertisers</h5>
    </div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Advertiser</th>
                    <th>Status</th>
                    <th>Total Spend</th>
                    <th>Active Campaigns</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($topAdvertisers ?? []) as $adv)
                    <tr>
                        <td>{{ $adv['name'] ?? 'Advertiser' }}</td>
                        <td><span class="badge bg-success">{{ $adv['status'] ?? 'active' }}</span></td>
                        <td>{{ $adv['spend'] ?? '$0.00' }}</td>
                        <td>{{ $adv['campaigns'] ?? 0 }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-3">No advertisers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/advertisement/admin.js') }}"></script>
@endpush
