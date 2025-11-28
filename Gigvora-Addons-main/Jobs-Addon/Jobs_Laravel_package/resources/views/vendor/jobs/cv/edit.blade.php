@extends('layouts.app')

@section('title', 'Edit CV')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/jobs">Jobs</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cv.index') }}">CVs</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="cv-builder" data-cv-id="{{ $cv->id ?? '' }}">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">{{ $cv->name ?? 'New CV' }}</h1>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary" id="preview-cv">Preview</button>
            <button class="btn btn-primary" id="save-cv">Save</button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Personal Info</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full name</label>
                            <input type="text" class="form-control" name="full_name" value="{{ $cv->full_name ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Headline</label>
                            <input type="text" class="form-control" name="headline" value="{{ $cv->headline ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control" name="location" value="{{ $cv->location ?? '' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ $cv->email ?? '' }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase d-flex justify-content-between align-items-center">Summary <small class="text-muted" id="summary-count">0/500</small></h6>
                    <textarea class="form-control" rows="4" name="summary" maxlength="500">{{ $cv->summary ?? '' }}</textarea>
                </div>
            </div>
            <div class="card mb-3" id="experience-section">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-muted text-uppercase mb-0">Experience</h6>
                        <button class="btn btn-outline-primary btn-sm" id="add-experience">Add</button>
                    </div>
                    <div id="experience-list">
                        @foreach(($cv->experience ?? []) as $experience)
                            @include('vendor.jobs.components.candidate_card', ['candidate' => (object) $experience])
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card mb-3" id="education-section">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="text-muted text-uppercase mb-0">Education</h6>
                        <button class="btn btn-outline-primary btn-sm" id="add-education">Add</button>
                    </div>
                    <div id="education-list"></div>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Skills</h6>
                    <div class="d-flex flex-wrap gap-2" id="skills-chips">
                        @foreach(($cv->skills ?? []) as $skill)
                            <span class="badge bg-light text-dark">{{ $skill }}</span>
                        @endforeach
                    </div>
                    <input type="text" class="form-control mt-2" id="skills-input" placeholder="Add skill and hit enter">
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="text-muted text-uppercase">Template</h6>
                    <select class="form-select" name="template">
                        <option>Classic</option>
                        <option>Modern</option>
                        <option>Minimal</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/jobs/cvBuilder.js') }}"></script>
@endpush
