@extends('layouts.app')

@section('title', 'Campaigns')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Campaigns</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1">Campaigns</h1>
        <p class="text-muted mb-0">Manage and filter all your campaigns.</p>
    </div>
    <a href="{{ route('advertisement.campaigns.create') }}" class="btn btn-primary">Create Campaign</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form id="campaign-filter-form" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Search</label>
                <input type="text" name="search" class="form-control" placeholder="Campaign name" />
            </div>
            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Any</option>
                    <option value="active">Active</option>
                    <option value="paused">Paused</option>
                    <option value="completed">Completed</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Objective</label>
                <select name="objective" class="form-select">
                    <option value="">Any</option>
                    <option value="traffic">Traffic</option>
                    <option value="conversions">Conversions</option>
                    <option value="awareness">Awareness</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Date Range</label>
                <input type="text" name="date_range" class="form-control" placeholder="Select dates" />
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                <button type="reset" class="btn btn-outline-secondary" id="campaign-filter-reset">Reset</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle" id="campaigns-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Objective</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Impressions</th>
                    <th>Clicks</th>
                    <th>CTR</th>
                    <th>Spend</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($campaigns ?? []) as $campaign)
                    <tr data-id="{{ $campaign['id'] ?? '' }}">
                        <td class="fw-semibold">{{ $campaign['name'] ?? 'Untitled' }}</td>
                        <td><span class="badge bg-success text-uppercase">{{ $campaign['status'] ?? 'active' }}</span></td>
                        <td>{{ $campaign['objective'] ?? 'Traffic' }}</td>
                        <td>{{ $campaign['start_date'] ?? '-' }}</td>
                        <td>{{ $campaign['end_date'] ?? '-' }}</td>
                        <td>{{ $campaign['impressions'] ?? 0 }}</td>
                        <td>{{ $campaign['clicks'] ?? 0 }}</td>
                        <td>{{ $campaign['ctr'] ?? '0%' }}</td>
                        <td>{{ $campaign['spend'] ?? '$0.00' }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group">
                                <a class="btn btn-outline-primary" href="{{ route('advertisement.campaigns.show', $campaign['id'] ?? 0) }}">View</a>
                                <button class="btn btn-outline-secondary campaign-pause" data-action="toggle">Pause/Resume</button>
                                <button class="btn btn-outline-secondary campaign-duplicate">Duplicate</button>
                                <button class="btn btn-outline-danger campaign-archive">Archive</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">No campaigns found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center" id="campaign-pagination">
        {{ $campaignsPagination ?? '' }}
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/advertisement/campaigns.js') }}"></script>
@endpush
