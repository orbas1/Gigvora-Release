@extends('layouts.app')

@section('title', 'Application Detail')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/jobs">Jobs</a></li>
        <li class="breadcrumb-item"><a href="{{ route('applications.index') }}">Applications</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $application->job->title ?? 'Application' }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="h4 mb-2">{{ $application->job->title ?? 'Role' }}</h1>
            <p class="text-muted">{{ $application->job->company->name ?? 'Company' }}</p>
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Timeline</h6>
                    <ul class="list-group list-group-flush">
                        @foreach(($application->timeline ?? []) as $event)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $event['label'] ?? 'Update' }}</div>
                                    <div class="text-muted small">{{ $event['description'] ?? '' }}</div>
                                </div>
                                <span class="text-muted small">{{ $event['date'] ?? '' }}</span>
                            </li>
                        @endforeach
                        @if(empty($application->timeline))
                            <li class="list-group-item text-muted">No updates yet.</li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Submitted Documents</h6>
                    <p class="mb-1">CV: <a href="{{ $application->cv_url ?? '#' }}">View</a></p>
                    <p class="mb-0">Cover letter: {{ $application->cover_letter_title ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Interview invitations</h6>
                    @forelse(($application->interviews ?? []) as $interview)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div>
                                <div class="fw-semibold">{{ $interview['title'] ?? 'Interview' }}</div>
                                <div class="text-muted small">{{ $interview['datetime'] ?? '' }}</div>
                            </div>
                            <a href="#" class="btn btn-sm btn-outline-primary">Add to calendar</a>
                        </div>
                    @empty
                        <p class="text-muted mb-0">No interviews scheduled.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Status</h6>
                    <p class="fs-5">{{ ucfirst($application->status ?? 'applied') }}</p>
                    <p class="text-muted">Next step: {{ $application->next_step ?? 'Awaiting review' }}</p>
                    <button class="btn btn-outline-secondary w-100">Withdraw application</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
