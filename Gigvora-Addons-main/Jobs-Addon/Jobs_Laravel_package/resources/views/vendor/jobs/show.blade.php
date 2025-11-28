@extends('layouts.app')

@section('title', $job->title ?? 'Job Detail')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/jobs">Jobs</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $job->title ?? 'Role' }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="job-detail-page" data-job-id="{{ $job->id ?? '' }}">
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h1 class="h3 mb-1">{{ $job->title ?? 'Job Title' }}</h1>
                    <div class="text-muted">{{ $job->company->name ?? 'Company' }} · {{ $job->location ?? 'Location' }} · {{ $job->employment_type ?? 'Full-time' }}</div>
                </div>
                <button class="btn btn-light save-job" data-job-id="{{ $job->id ?? '' }}"><i class="bi bi-bookmark"></i></button>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">About the role</h5>
                    <div class="mb-3">{!! nl2br(e($job->description ?? 'Detailed description goes here.')) !!}</div>
                    <h6>Key Details</h6>
                    <ul class="mb-0">
                        <li>Workplace: {{ $job->workplace_type ?? 'Flexible' }}</li>
                        <li>Salary: {{ $job->salary_label ?? 'Competitive' }}</li>
                        <li>Status: {{ ucfirst($job->status ?? 'open') }}</li>
                    </ul>
                </div>
            </div>

            @if(!empty($job->screening_questions))
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Screening Questions</h5>
                    <ul class="mb-0">
                        @foreach($job->screening_questions as $question)
                            <li>{{ $question['prompt'] ?? $question }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Similar Jobs</h5>
                    <div class="row g-3">
                        @foreach(($similarJobs ?? []) as $similar)
                            <div class="col-md-6">
                                @include('vendor.jobs.components.job_card', ['job' => $similar, 'showActions' => true])
                            </div>
                        @endforeach
                        @if(empty($similarJobs))
                            <p class="text-muted mb-0">No similar roles available right now.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="placeholder rounded-circle bg-light" style="width:48px;height:48px;"></div>
                        <div class="ms-3">
                            <div class="fw-semibold">{{ $job->company->name ?? 'Company Name' }}</div>
                            <div class="text-muted small">{{ $job->company->location ?? 'Location' }}</div>
                        </div>
                    </div>
                    <p class="text-muted mb-2">{{ $job->company->location ?? 'Company location' }}</p>
                    <a href="{{ $job->company->website ?? '#' }}" class="btn btn-outline-primary w-100 mb-2">View company profile</a>
                    <a class="btn btn-primary w-100" id="apply-now-btn" href="{{ route('jobs.apply', $job->id ?? null) }}">Apply Now</a>
                    <div class="text-muted small mt-2">Apply with CV or Linked profile</div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('vendor.jobs.components.apply_modal', ['job' => $job])
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/jobs/jobDetail.js') }}"></script>
@endpush
