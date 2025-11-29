@extends('layouts.app')

@section('title', 'Jobs')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Jobs</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="jobs-search-app">
    <div class="row align-items-center mb-4">
        <div class="col-lg-7">
            <h1 class="fw-bold mb-2">Find your next role</h1>
            <p class="text-muted mb-0">Search thousands of opportunities tailored to your skills.</p>
        </div>
        <div class="col-lg-5">
            <form class="d-flex gap-2" id="job-search-form">
                <input type="text" class="form-control" name="keywords" placeholder="Job title or keywords" value="{{ request('keywords') }}">
                <input type="text" class="form-control" name="location" placeholder="Location" value="{{ request('location') }}">
                <button type="submit" class="btn btn-primary px-4">Search</button>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 mb-4">
            @include('vendor.jobs.components.filter_bar')
        </div>
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Showing {{ $jobs->count() ?? 0 }} roles</h5>
                <div class="text-muted small">Updated frequently</div>
            </div>
            <div id="job-results-list">
                @include('vendor.jobs.components.job_results', ['jobs' => $jobs])
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/jobs/jobsSearch.js') }}"></script>
@endpush
