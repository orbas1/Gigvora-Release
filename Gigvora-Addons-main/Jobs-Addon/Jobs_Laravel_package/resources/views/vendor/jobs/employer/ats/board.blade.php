@extends('layouts.app')

@section('title', 'ATS Board')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/employer">Employer</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employer.jobs.index') }}">Jobs</a></li>
        <li class="breadcrumb-item active" aria-current="page">ATS Board</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid py-3" id="ats-board" data-job-id="{{ $job->id ?? '' }}">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-0">{{ $job->title ?? 'ATS Board' }}</h1>
            <p class="text-muted mb-0">Drag candidates between stages</p>
        </div>
        <div class="w-25">
            <input type="search" class="form-control" id="candidate-search" placeholder="Search candidates">
        </div>
    </div>
    <div class="row flex-nowrap overflow-auto" style="gap:16px;">
        @foreach(['applied' => 'Applied', 'screening' => 'Screening', 'shortlisted' => 'Shortlisted', 'interview' => 'Interview', 'offer' => 'Offer', 'hired' => 'Hired', 'rejected' => 'Rejected'] as $stage => $label)
            <div class="col-md-3 min-width-300">
                @include('vendor.jobs.components.ats_stage_column', ['stage' => $stage, 'label' => $label, 'candidates' => $pipelines[$stage] ?? []])
            </div>
        @endforeach
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/jobs/atsBoard.js') }}"></script>
@endpush
