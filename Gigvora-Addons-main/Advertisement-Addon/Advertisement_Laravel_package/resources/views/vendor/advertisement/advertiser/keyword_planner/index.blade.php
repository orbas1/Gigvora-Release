@extends('layouts.app')

@section('title', 'Keyword Planner')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Keyword Planner</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <form id="keyword-planner-form" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label">Seed Keywords or URL</label>
                <input type="text" name="query" id="keyword-query" class="form-control" placeholder="Enter keyword or URL">
            </div>
            <div class="col-md-3">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control" placeholder="Global">
            </div>
            <div class="col-md-2">
                <label class="form-label">Language</label>
                <input type="text" name="language" class="form-control" placeholder="Any">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" type="submit">Search</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Results</h5>
            <small class="text-muted">Estimated CPC, volume and conversions</small>
        </div>
        <button class="btn btn-outline-primary btn-sm" id="export-keywords">Export to Campaign</button>
    </div>
    <div class="table-responsive">
        <table class="table align-middle" id="keyword-results-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all-keywords"></th>
                    <th>Keyword</th>
                    <th>Suggested CPC</th>
                    <th>Estimated CPA</th>
                    <th>Impressions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="text-muted text-center py-4">Start searching to see results.</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/advertisement/keyword_planner.js') }}"></script>
@endpush
