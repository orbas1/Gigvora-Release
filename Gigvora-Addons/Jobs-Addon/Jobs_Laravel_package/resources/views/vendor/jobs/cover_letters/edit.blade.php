@extends('layouts.app')

@section('title', 'Cover Letter')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/jobs">Jobs</a></li>
        <li class="breadcrumb-item active" aria-current="page">Cover Letter</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="cover-letter-editor">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">{{ $coverLetter->title ?? 'New Cover Letter' }}</h1>
        <button class="btn btn-primary" id="save-cover-letter">Save</button>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" id="cover-letter-title" value="{{ $coverLetter->title ?? '' }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Body</label>
                <textarea class="form-control" id="cover-letter-body" rows="10" placeholder="Write your letter here...">{{ $coverLetter->body ?? '' }}</textarea>
            </div>
            <div class="d-flex gap-2 mb-3">
                <button class="btn btn-outline-secondary btn-sm insert-tag" data-tag="[Company Name]">[Company Name]</button>
                <button class="btn btn-outline-secondary btn-sm insert-tag" data-tag="[Job Title]">[Job Title]</button>
                <button class="btn btn-outline-secondary btn-sm insert-tag" data-tag="[Hiring Manager]">[Hiring Manager]</button>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="save-as-template" {{ ($coverLetter->is_template ?? false) ? 'checked' : '' }}>
                <label class="form-check-label" for="save-as-template">Save as template</label>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/jobs/coverLetterEditor.js') }}"></script>
@endpush
