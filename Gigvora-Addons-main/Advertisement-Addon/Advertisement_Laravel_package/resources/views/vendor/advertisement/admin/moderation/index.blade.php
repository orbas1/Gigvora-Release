@extends('admin.layouts.app')

@section('title', 'Ads Moderation')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.admin.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Moderation Queue</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Pending Creatives</h5>
            <small class="text-muted">Review and approve ads.</small>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <select class="form-select form-select-sm" id="moderation-status-filter">
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
            <select class="form-select form-select-sm" id="moderation-type-filter">
                <option value="">Any Type</option>
                <option value="text">Text</option>
                <option value="banner">Banner</option>
                <option value="video">Video</option>
                <option value="search">Search</option>
            </select>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table align-middle" id="moderation-table">
            <thead>
                <tr>
                    <th>Advertiser</th>
                    <th>Campaign</th>
                    <th>Ad Name</th>
                    <th>Type</th>
                    <th>Created</th>
                    <th>Flagged Reason</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($queue ?? []) as $item)
                    <tr data-id="{{ $item['id'] ?? 0 }}">
                        <td>{{ $item['advertiser'] ?? '-' }}</td>
                        <td>{{ $item['campaign'] ?? '-' }}</td>
                        <td>{{ $item['name'] ?? '-' }}</td>
                        <td>{{ $item['type'] ?? '-' }}</td>
                        <td>{{ $item['created_at'] ?? '-' }}</td>
                        <td>{{ $item['flagged_reason'] ?? '-' }}</td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm" role="group">
                                <button class="btn btn-outline-primary moderation-preview">Preview</button>
                                <button class="btn btn-outline-success moderation-approve">Approve</button>
                                <button class="btn btn-outline-danger moderation-reject">Reject</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No pending ads.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="moderationPreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ad Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="moderation-preview-body">
                <p class="text-muted">Loading...</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-success" id="modal-approve">Approve</button>
                <button class="btn btn-danger" id="modal-reject">Reject with Reason</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/advertisement/admin.js') }}"></script>
@endpush
