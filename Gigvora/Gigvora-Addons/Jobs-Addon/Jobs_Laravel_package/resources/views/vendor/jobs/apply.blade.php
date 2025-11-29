@extends('layouts.app')

@section('title', 'Apply for ' . ($job->title ?? 'Job'))

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/jobs">Jobs</a></li>
        <li class="breadcrumb-item"><a href="{{ route('jobs.show', $job->id ?? null) }}">{{ $job->title ?? 'Role' }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Apply</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="job-apply-wizard" data-job-id="{{ $job->id ?? '' }}">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="h3 mb-4">Apply to {{ $job->title ?? 'this role' }}</h1>
            <div class="progress mb-3" style="height: 6px;">
                <div class="progress-bar" id="apply-progress" style="width:20%;"></div>
            </div>
            <form id="application-form" method="post" action="{{ route('jobs.apply.submit', $job->id ?? null) }}" novalidate>
                @csrf
                <input type="hidden" name="job_id" value="{{ $job->id }}">
                <input type="hidden" name="candidate_id" value="{{ auth()->id() }}">
                <div class="step" data-step="1">
                    <h5 class="mb-3">Profile & Contact</h5>
                    <div class="mb-3">
                        <label class="form-label">Full name</label>
                        <input type="text" class="form-control" name="name" value="{{ auth()->user()->name ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ auth()->user()->email ?? '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" name="phone" value="{{ auth()->user()->phone ?? '' }}">
                    </div>
                </div>
                <div class="step d-none" data-step="2">
                    <h5 class="mb-3">CV Selection</h5>
                    <div class="mb-3">
                        <label class="form-label">Choose existing CV</label>
                        <select class="form-select" name="cv_template_id">
                            @foreach(($cvs ?? []) as $cv)
                                <option value="{{ $cv->id }}">{{ $cv->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Upload new CV</label>
                        <input type="file" class="form-control" name="cv_upload">
                        <small class="text-muted">Upload handled by backend.</small>
                    </div>
                </div>
                <div class="step d-none" data-step="3">
                    <h5 class="mb-3">Cover Letter</h5>
                    <textarea class="form-control" rows="6" name="notes" placeholder="Write a short message"></textarea>
                    <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="generate-cover-letter">Generate from template</button>
                </div>
                <div class="step d-none" data-step="4">
                    <h5 class="mb-3">Screening Questions</h5>
                    @forelse(($screeningQuestions ?? []) as $question)
                        <div class="mb-3">
                            <input type="hidden" name="answers[{{ $loop->index }}][screening_question_id]" value="{{ $question->id }}">
                            <label class="form-label">{{ $question->question ?? 'Question' }}</label>
                            @if(($question['type'] ?? '') === 'multiple_choice')
                                <select class="form-select" name="answers[{{ $loop->index }}][answer]">
                                    @foreach($question['options'] ?? [] as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            @elseif(($question['type'] ?? '') === 'boolean')
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[{{ $loop->index }}][answer]" value="yes" id="q{{ $loop->index }}yes">
                                    <label class="form-check-label" for="q{{ $loop->index }}yes">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="answers[{{ $loop->index }}][answer]" value="no" id="q{{ $loop->index }}no">
                                    <label class="form-check-label" for="q{{ $loop->index }}no">No</label>
                                </div>
                            @else
                                <textarea class="form-control" name="answers[{{ $loop->index }}][answer]" rows="3"></textarea>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted">No screening questions for this role.</p>
                    @endforelse
                </div>
                <div class="step d-none" data-step="5">
                    <h5 class="mb-3">Review & Submit</h5>
                    <p class="text-muted">Review your details before submitting.</p>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="1" id="consentCheck" required>
                        <label class="form-check-label" for="consentCheck">I consent to sharing my data with {{ $job->company->name ?? 'the employer' }}</label>
                    </div>
                    <button class="btn btn-success" type="submit">Submit Application</button>
                </div>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary" id="prev-step">Back</button>
                    <button type="button" class="btn btn-primary" id="next-step">Next</button>
                </div>
            </form>
        </div>
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="text-muted">Summary</h6>
                    <p class="mb-1 fw-semibold">{{ $job->title ?? 'Role' }}</p>
                    <p class="text-muted small mb-1">{{ $job->company->name ?? '' }}</p>
                    <p class="text-muted small mb-0">{{ $job->location ?? '' }} · {{ $job->employment_type ?? '' }} · {{ $job->salary_label ?? '' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/jobs/jobApplyWizard.js') }}"></script>
@endpush
