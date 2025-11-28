@extends('layouts.app')

@section('title', 'Candidate Detail')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/employer">Employer</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employer.jobs.index') }}">Jobs</a></li>
        <li class="breadcrumb-item active" aria-current="page">Candidate</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="candidate-detail" data-candidate-id="{{ $candidate->id ?? '' }}">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body d-flex align-items-center">
                    <div class="placeholder rounded-circle bg-light me-3" style="width:64px;height:64px;"></div>
                    <div>
                        <h1 class="h5 mb-0">{{ $candidate->name ?? 'Candidate name' }}</h1>
                        <div class="text-muted">{{ $candidate->headline ?? '' }} · {{ $candidate->location ?? '' }}</div>
                        <a href="{{ $candidate->profile_url ?? '#' }}" class="small">View platform profile</a>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Application</h6>
                    <p class="mb-1">Applied: {{ optional($candidate->applied_at)->format('M d, Y') }}</p>
                    <p class="mb-1">Stage: <span class="badge bg-light text-dark">{{ $candidate->stage ?? 'Applied' }}</span></p>
                    <p class="mb-0">CV: <a href="{{ $candidate->cv_url ?? '#' }}">View</a></p>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Screening Answers</h6>
                    @foreach(($candidate->screening_answers ?? []) as $answer)
                        <div class="mb-2">
                            <div class="fw-semibold">{{ $answer['question'] ?? '' }}</div>
                            <div class="text-muted">{{ $answer['answer'] ?? '' }}</div>
                        </div>
                    @endforeach
                    @if(empty($candidate->screening_answers))
                        <p class="text-muted mb-0">No screening responses.</p>
                    @endif
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Notes & Tags</h6>
                    <div class="mb-3">
                        <textarea class="form-control" id="candidate-note" rows="3" placeholder="Add a note"></textarea>
                        <button class="btn btn-outline-primary btn-sm mt-2" id="save-note">Save note</button>
                    </div>
                    <div class="d-flex flex-wrap gap-2" id="candidate-tags">
                        @foreach(($candidate->tags ?? []) as $tag)
                            <span class="badge bg-light text-dark">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <input type="text" class="form-control mt-2" id="new-tag" placeholder="Add tag and press enter">
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Actions</h6>
                    <select class="form-select mb-2" id="stage-select">
                        @foreach(['applied','screening','shortlisted','interview','offer','hired','rejected'] as $stage)
                            <option value="{{ $stage }}" @selected(($candidate->stage ?? '') === $stage)>{{ ucfirst($stage) }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary w-100 mb-2" id="update-stage">Move to stage</button>
                    <button class="btn btn-outline-primary w-100 mb-2" id="invite-interview">Invite to interview</button>
                    <button class="btn btn-outline-danger w-100" id="reject-candidate">Reject</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
