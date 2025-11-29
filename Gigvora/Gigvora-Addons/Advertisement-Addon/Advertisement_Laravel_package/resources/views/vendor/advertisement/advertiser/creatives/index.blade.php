@extends('layouts.app')

@section('title', 'Creatives')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Creatives</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h4 mb-1">Creatives</h1>
        <p class="text-muted mb-0">Manage creatives attached to your campaigns.</p>
    </div>
    <a href="{{ route('advertisement.creatives.create') }}" class="btn btn-primary">Create New Ad</a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form id="creative-filter-form" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Campaign</label>
                <select name="campaign" class="form-select">
                    <option value="">All</option>
                    @foreach(($campaignOptions ?? []) as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select">
                    <option value="">Any</option>
                    <option value="text">Text</option>
                    <option value="banner">Banner</option>
                    <option value="video">Video</option>
                    <option value="search">Search</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Any</option>
                    <option value="active">Active</option>
                    <option value="paused">Paused</option>
                    <option value="draft">Draft</option>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3" id="creatives-list">
    @forelse(($creatives ?? []) as $creative)
        <div class="col-md-6">
            <div class="card h-100 shadow-sm">
                <div class="card-body d-flex gap-3">
                    <div class="bg-light rounded text-center p-3" style="width: 96px; height: 96px;">
                        <span class="text-uppercase text-muted small">{{ $creative['type'] ?? 'AD' }}</span>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ $creative['name'] ?? 'Creative' }}</h6>
                                <p class="text-muted small mb-1">{{ $creative['campaign'] ?? 'Campaign' }}</p>
                                <div class="text-muted small">Impressions: {{ $creative['impressions'] ?? 0 }} • Clicks: {{ $creative['clicks'] ?? 0 }} • CTR: {{ $creative['ctr'] ?? '0%' }}</div>
                            </div>
                            <span class="badge bg-success">{{ $creative['status'] ?? 'active' }}</span>
                        </div>
                        <div class="mt-2 d-flex gap-2">
                            <a href="{{ route('advertisement.creatives.edit', $creative['id'] ?? 0) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <button class="btn btn-sm btn-outline-secondary creative-preview" data-id="{{ $creative['id'] ?? 0 }}">Preview</button>
                            <button class="btn btn-sm btn-outline-secondary creative-toggle" data-id="{{ $creative['id'] ?? 0 }}">Pause</button>
                            <button class="btn btn-sm btn-outline-danger creative-archive" data-id="{{ $creative['id'] ?? 0 }}">Archive</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted">No creatives available.</p>
    @endforelse
</div>

<div class="modal fade" id="creativePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ad Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="creative-preview-body">
                <p class="text-muted">Loading preview...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/advertisement/creatives.js') }}"></script>
@endpush
