@extends('layouts.app')

@section('title', 'Job Wizard')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/employer">Employer</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employer.jobs.index') }}">Jobs</a></li>
        <li class="breadcrumb-item active" aria-current="page">Wizard</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="job-post-wizard" data-job-id="{{ $job->id ?? '' }}">
    <h1 class="h4 mb-4">Post a Job</h1>
    <div class="progress mb-3" style="height:6px;">
        <div class="progress-bar" id="job-wizard-progress" style="width: 20%;"></div>
    </div>
    <form id="job-wizard-form" method="post" action="{{ route('employer.jobs.store') }}">
        @csrf
        <div class="wizard-step" data-step="1">
            <h5>Basics</h5>
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Job title</label><input class="form-control" name="title" required></div>
                <div class="col-md-6"><label class="form-label">Location</label><input class="form-control" name="location"></div>
                <div class="col-md-6"><label class="form-label">Employment type</label><select class="form-select" name="type"><option>Full-time</option><option>Part-time</option><option>Contract</option></select></div>
                <div class="col-md-6"><label class="form-label">Work mode</label><select class="form-select" name="mode"><option>On-site</option><option>Hybrid</option><option>Remote</option></select></div>
            </div>
        </div>
        <div class="wizard-step d-none" data-step="2">
            <h5>Details</h5>
            <div class="mb-3"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="5"></textarea></div>
            <div class="mb-3"><label class="form-label">Responsibilities</label><textarea class="form-control" name="responsibilities" rows="4"></textarea></div>
            <div class="mb-3"><label class="form-label">Requirements</label><textarea class="form-control" name="requirements" rows="4"></textarea></div>
            <div class="mb-3"><label class="form-label">Benefits</label><textarea class="form-control" name="benefits" rows="3"></textarea></div>
        </div>
        <div class="wizard-step d-none" data-step="3">
            <h5>Compensation & Policies</h5>
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Salary range</label><input class="form-control" name="salary_range"></div>
                <div class="col-md-6"><label class="form-label">Currency</label><input class="form-control" name="currency" value="USD"></div>
                <div class="col-md-6"><label class="form-label">Pay frequency</label><select class="form-select" name="pay_frequency"><option>Monthly</option><option>Yearly</option></select></div>
            </div>
        </div>
        <div class="wizard-step d-none" data-step="4">
            <h5>Screening</h5>
            <p class="text-muted">Attach a screening template or add questions.</p>
            <div id="screening-questions"></div>
            <button type="button" class="btn btn-outline-secondary btn-sm" id="add-screening">Add question</button>
        </div>
        <div class="wizard-step d-none" data-step="5">
            <h5>Publishing</h5>
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Visibility</label><select class="form-select" name="visibility"><option value="public">Public</option><option value="internal">Internal</option></select></div>
                <div class="col-md-6"><label class="form-label">Start date</label><input type="date" class="form-control" name="start_date"></div>
                <div class="col-md-6"><label class="form-label">Expiry date</label><input type="date" class="form-control" name="expiry_date"></div>
                <div class="col-md-6"><label class="form-label">Posting plan</label><select class="form-select" name="plan"><option>Credit</option><option>Subscription</option></select></div>
            </div>
            <div class="mt-3 p-3 bg-light rounded">Review summary appears here.</div>
        </div>
        <div class="d-flex justify-content-between mt-4">
            <button type="button" class="btn btn-outline-secondary" id="job-prev-step">Back</button>
            <button type="button" class="btn btn-primary" id="job-next-step">Next</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/jobs/jobPostWizard.js') }}"></script>
@endpush
