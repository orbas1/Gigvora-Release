@extends('layouts.app')

@section('title', 'Saved Jobs')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/jobs">Jobs</a></li>
        <li class="breadcrumb-item active" aria-current="page">Saved</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="saved-jobs-page">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Saved Jobs</h1>
        <span class="text-muted">{{ $savedJobs->total() ?? 0 }} saved</span>
    </div>
    <div id="saved-jobs-list">
        @forelse(($savedJobs ?? []) as $bookmark)
            @include('vendor.jobs.components.job_card', ['job' => $bookmark->job, 'showActions' => true])
        @empty
            <div class="alert alert-light border">You haven't saved any jobs yet.</div>
        @endforelse
    </div>
    @include('vendor.jobs.components.pagination', ['paginator' => $savedJobs])
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/jobs/jobsSearch.js') }}"></script>
@endpush
