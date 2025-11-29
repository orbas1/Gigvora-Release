@extends('layouts.app')

@section('title', 'Screening Templates')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/employer">Employer</a></li>
        <li class="breadcrumb-item active" aria-current="page">Screening</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="screening-builder">
    <div class="row">
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Templates</span>
                    <button class="btn btn-sm btn-primary" id="new-template">New</button>
                </div>
                <ul class="list-group list-group-flush" id="template-list">
                    @foreach(($templates ?? []) as $template)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>{{ $template->name }}</span>
                            <button class="btn btn-sm btn-outline-secondary load-template" data-template-id="{{ $template->id }}">Edit</button>
                        </li>
                    @endforeach
                    @if(empty($templates))
                        <li class="list-group-item text-muted">No templates yet.</li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Template name</label>
                        <input type="text" class="form-control" id="template-name" value="">
                    </div>
                    <div id="questions-list">
                        <!-- dynamic questions -->
                    </div>
                    <button class="btn btn-outline-primary btn-sm" id="add-question">Add question</button>
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-primary" id="save-template">Save template</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/jobs/screeningBuilder.js') }}"></script>
@endpush
