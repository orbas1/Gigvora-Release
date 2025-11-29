@extends('layouts.app')

@section('title', ($creative['id'] ?? false) ? 'Edit Creative' : 'Create Creative')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('advertisement.creatives.index') }}">Creatives</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ ($creative['id'] ?? false) ? 'Edit' : 'Create' }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Creative Details</h5>
            </div>
            <div class="card-body">
                <form id="creative-form" method="POST" action="{{ $action ?? '' }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Ad Type</label>
                        <select name="type" id="creative-type" class="form-select" required>
                            <option value="text">Text</option>
                            <option value="banner">Banner</option>
                            <option value="video">Video</option>
                            <option value="search">Search</option>
                            <option value="recommendation">Recommendation</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Headline</label>
                        <input type="text" name="headline" class="form-control" value="{{ $creative['headline'] ?? '' }}" maxlength="60" required>
                        <small class="text-muted">Max 60 characters.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" maxlength="150" required>{{ $creative['description'] ?? '' }}</textarea>
                        <small class="text-muted">Max 150 characters.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Destination URL</label>
                        <input type="url" name="url" class="form-control" value="{{ $creative['url'] ?? '' }}" required>
                    </div>
                    <div class="row g-3 mb-3" id="media-section">
                        <div class="col-md-6">
                            <label class="form-label">Media Asset</label>
                            <input type="text" name="media" class="form-control" placeholder="Select or paste asset URL" value="{{ $creative['media'] ?? '' }}">
                            <small class="text-muted">Use existing asset manager to choose files.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Call To Action</label>
                            <input type="text" name="cta" class="form-control" value="{{ $creative['cta'] ?? '' }}" placeholder="Shop Now" />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Attach to Campaign</label>
                        <select name="campaign_id" class="form-select" required>
                            @foreach(($campaignOptions ?? []) as $id => $name)
                                <option value="{{ $id }}" @selected(($creative['campaign_id'] ?? null) == $id)>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">{{ ($creative['id'] ?? false) ? 'Save Changes' : 'Save Creative' }}</button>
                        <button type="button" class="btn btn-outline-secondary" id="save-draft">Save as Draft</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0">Preview</h6>
                <span class="badge bg-light text-dark" id="preview-type">Text</span>
            </div>
            <div class="card-body" id="creative-preview">
                <p class="text-muted">Start typing to see your ad preview.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/advertisement/creatives.js') }}"></script>
@endpush
