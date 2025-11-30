@extends('admin.layouts.app')

@section('title', 'Advertiser Management')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.admin.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Advertisers</li>
    </ol>
</nav>
@endsection

@section('ads-page')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Advertisers</h5>
            <small class="text-muted">Manage advertiser accounts and usage.</small>
        </div>
        <input type="text" class="form-control form-control-sm" placeholder="Search advertiser" style="width: 220px;" id="advertiser-search">
    </div>
    <div class="table-responsive">
        <table class="table align-middle" id="advertiser-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Total Spend</th>
                    <th>Active Campaigns</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($advertisers ?? []) as $adv)
                    <tr data-id="{{ $adv['id'] ?? 0 }}">
                        <td>{{ $adv['name'] ?? '' }}</td>
                        <td><span class="badge bg-success">{{ $adv['status'] ?? 'active' }}</span></td>
                        <td>{{ $adv['spend'] ?? '$0.00' }}</td>
                        <td>{{ $adv['campaigns'] ?? 0 }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-outline-primary view-advertiser">View</button>
                                <button class="btn btn-outline-secondary suspend-advertiser">Suspend</button>
                                <button class="btn btn-outline-danger flag-advertiser">Flag</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No advertisers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/advertisement/admin.js') }}"></script>
@endpush
