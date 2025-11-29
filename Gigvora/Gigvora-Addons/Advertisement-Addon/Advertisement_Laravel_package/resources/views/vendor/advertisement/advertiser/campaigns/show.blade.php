@extends('layouts.app')

@section('title', $campaign['name'] ?? 'Campaign Detail')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('advertisement.campaigns.index') }}">Campaigns</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $campaign['name'] ?? 'Campaign' }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-start mb-4 flex-wrap gap-3">
    <div>
        <h1 class="h4 mb-1">{{ $campaign['name'] ?? 'Campaign' }}</h1>
        <div class="d-flex align-items-center gap-2 text-muted">
            <span class="badge bg-success text-uppercase">{{ $campaign['status'] ?? 'active' }}</span>
            <span>{{ $campaign['objective'] ?? 'Traffic' }}</span>
            <span>•</span>
            <span>{{ $campaign['date_range'] ?? (($campaign['start_date'] ?? '-') . ' - ' . ($campaign['end_date'] ?? '-')) }}</span>
        </div>
    </div>
    <div class="btn-group">
        <a href="{{ route('advertisement.campaigns.edit', $campaign['id'] ?? 0) }}" class="btn btn-outline-primary">Edit</a>
        <button class="btn btn-outline-secondary" id="campaign-toggle">Pause/Resume</button>
        <button class="btn btn-outline-secondary" id="campaign-duplicate">Duplicate</button>
    </div>
</div>

<ul class="nav nav-tabs" id="campaignTabs" role="tablist">
    @php
        $tabs = [
            'overview' => 'Overview',
            'ads' => 'Ads',
            'targeting' => 'Targeting',
            'budget' => 'Budget & Schedule',
            'performance' => 'Performance',
        ];
    @endphp
    @foreach($tabs as $key => $label)
        <li class="nav-item" role="presentation">
            <button class="nav-link @if($loop->first) active @endif" data-bs-toggle="tab" data-bs-target="#tab-{{ $key }}" type="button" role="tab">{{ $label }}</button>
        </li>
    @endforeach
</ul>
<div class="tab-content pt-3" id="campaignTabsContent">
    <div class="tab-pane fade show active" id="tab-overview" role="tabpanel">
        <div class="row g-3">
            @foreach(($campaign['kpis'] ?? []) as $kpi)
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <p class="text-muted small mb-1">{{ $kpi['label'] }}</p>
                            <h4 class="fw-bold mb-0">{{ $kpi['value'] }}</h4>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Performance</h5>
                <input type="text" class="form-control form-control-sm" id="detail-date-range" style="width: 200px" placeholder="Date range" />
            </div>
            <div class="card-body">
                <canvas id="campaign-overview-chart" height="120" class="w-100"></canvas>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="tab-ads" role="tabpanel">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Creatives</h5>
            <a href="{{ route('advertisement.creatives.create', ['campaign' => $campaign['id'] ?? null]) }}" class="btn btn-primary btn-sm">Create New Ad</a>
        </div>
        <div class="list-group">
            @forelse(($campaign['ads'] ?? []) as $ad)
                <div class="list-group-item d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded bg-light p-3 text-center" style="width: 64px; height: 64px;">
                            <span class="text-muted text-uppercase small">{{ $ad['type'] ?? 'AD' }}</span>
                        </div>
                        <div>
                            <h6 class="mb-1">{{ $ad['name'] ?? 'Creative' }}</h6>
                            <p class="mb-0 text-muted small">{{ $ad['status'] ?? 'active' }} • {{ $ad['placement'] ?? 'feed' }}</p>
                        </div>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('advertisement.creatives.edit', $ad['id'] ?? 0) }}" class="btn btn-outline-secondary">Edit</a>
                        <button class="btn btn-outline-secondary creative-toggle" data-id="{{ $ad['id'] ?? 0 }}">Pause/Resume</button>
                        <button class="btn btn-outline-danger creative-archive" data-id="{{ $ad['id'] ?? 0 }}">Archive</button>
                    </div>
                </div>
            @empty
                <p class="text-muted">No creatives yet.</p>
            @endforelse
        </div>
    </div>
    <div class="tab-pane fade" id="tab-targeting" role="tabpanel">
        <div class="card">
            <div class="card-body">
                <h6 class="text-uppercase text-muted small mb-3">Audience</h6>
                <p class="mb-1"><strong>Gender:</strong> {{ implode(', ', $campaign['targeting']['gender'] ?? []) }}</p>
                <p class="mb-1"><strong>Locations:</strong> {{ implode(', ', $campaign['targeting']['locations'] ?? []) }}</p>
                <p class="mb-1"><strong>Interests:</strong> {{ implode(', ', $campaign['targeting']['interests'] ?? []) }}</p>
                <p class="mb-0"><strong>Keywords:</strong> {{ implode(', ', $campaign['targeting']['keywords'] ?? []) }}</p>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="tab-budget" role="tabpanel">
        <div class="card">
            <div class="card-body d-flex flex-column gap-2">
                <div><strong>Budget Type:</strong> {{ $campaign['budget']['type'] ?? 'Daily' }}</div>
                <div><strong>Amount:</strong> {{ $campaign['budget']['amount'] ?? '$0.00' }}</div>
                <div><strong>Schedule:</strong> {{ $campaign['budget']['start'] ?? '-' }} - {{ $campaign['budget']['end'] ?? '-' }}</div>
                <div><strong>Bidding Model:</strong> {{ $campaign['budget']['bidding'] ?? 'CPC' }}</div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="tab-performance" role="tabpanel">
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h6 class="mb-0">Performance by Placement</h6>
                <input type="text" class="form-control form-control-sm" id="performance-date-range" style="width: 200px" placeholder="Date range" />
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Placement</th>
                            <th>Impressions</th>
                            <th>Clicks</th>
                            <th>CTR</th>
                            <th>Conversions</th>
                            <th>Spend</th>
                        </tr>
                    </thead>
                    <tbody id="performance-table-body">
                        @forelse(($campaign['performance'] ?? []) as $row)
                            <tr>
                                <td>{{ $row['placement'] ?? '-' }}</td>
                                <td>{{ $row['impressions'] ?? 0 }}</td>
                                <td>{{ $row['clicks'] ?? 0 }}</td>
                                <td>{{ $row['ctr'] ?? '0%' }}</td>
                                <td>{{ $row['conversions'] ?? 0 }}</td>
                                <td>{{ $row['spend'] ?? '$0.00' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No performance data.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/advertisement/dashboard.js') }}"></script>
<script type="module" src="{{ mix('js/advertisement/campaigns.js') }}"></script>
@endpush
