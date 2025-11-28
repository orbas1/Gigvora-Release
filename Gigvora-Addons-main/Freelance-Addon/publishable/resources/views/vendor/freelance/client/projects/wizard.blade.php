@extends('layouts.app')

@section('title', 'Post a Project')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/freelance/client/projects">Projects</a></li>
        <li class="breadcrumb-item active" aria-current="page">Post Project</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="project-wizard" data-save-url="{{ $saveUrl ?? '' }}">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Post Project</h3>
                <div class="text-muted">Step <span id="project-step">1</span> of 5</div>
            </div>
            <ul class="nav nav-pills mb-3" id="project-tabs">
                <li class="nav-item"><a class="nav-link active" data-step="1" href="#">Overview</a></li>
                <li class="nav-item"><a class="nav-link" data-step="2" href="#">Scope</a></li>
                <li class="nav-item"><a class="nav-link" data-step="3" href="#">Budget</a></li>
                <li class="nav-item"><a class="nav-link" data-step="4" href="#">Screening</a></li>
                <li class="nav-item"><a class="nav-link" data-step="5" href="#">Review</a></li>
            </ul>

            <div id="project-panels">
                <div class="project-panel" data-step="1">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" value="{{ $project['title'] ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select class="form-select" name="category">
                            @foreach($categories ?? [] as $category)
                                <option value="{{ $category }}" @selected(($project['category'] ?? '') === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tags</label>
                        <input type="text" class="form-control" name="tags" value="{{ $project['tags'] ?? '' }}">
                    </div>
                </div>

                <div class="project-panel d-none" data-step="2">
                    <div class="mb-3">
                        <label class="form-label">Describe work</label>
                        <textarea class="form-control" rows="6" name="description">{{ $project['description'] ?? '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Skills required</label>
                        <input type="text" class="form-control" name="skills" value="{{ $project['skills'] ?? '' }}">
                    </div>
                </div>

                <div class="project-panel d-none" data-step="3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Payment type</label>
                            <select class="form-select" name="type">
                                <option value="fixed">Fixed price</option>
                                <option value="hourly">Hourly</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Budget</label>
                            <input type="number" class="form-control" name="budget" value="{{ $project['budget'] ?? '' }}">
                        </div>
                    </div>
                    <div class="mt-3 p-3 bg-light rounded">
                        <p class="mb-0">Estimated service fee: <span id="fee-estimate">$0</span></p>
                    </div>
                </div>

                <div class="project-panel d-none" data-step="4">
                    <div class="mb-3">
                        <label class="form-label">Screening questions</label>
                        <div id="questions"></div>
                        <button class="btn btn-outline-secondary btn-sm" id="add-question">Add question</button>
                    </div>
                </div>

                <div class="project-panel d-none" data-step="5">
                    <div class="border rounded p-3 mb-3">
                        <h5>Summary</h5>
                        <p class="mb-1"><strong>Title:</strong> <span id="review-title">{{ $project['title'] ?? '' }}</span></p>
                        <p class="mb-1"><strong>Budget:</strong> <span id="review-budget">{{ $project['budget'] ?? '' }}</span></p>
                        <p class="mb-1"><strong>Type:</strong> <span id="review-type">{{ $project['type'] ?? '' }}</span></p>
                    </div>
                    <div class="p-3 bg-light rounded">
                        <h6>Commission estimation</h6>
                        <p class="mb-0">Platform fee: <span id="review-fee">$0</span></p>
                    </div>
                    <div class="d-flex gap-2 justify-content-end mt-3">
                        <button class="btn btn-outline-secondary" id="save-project-draft">Save Draft</button>
                        <button class="btn btn-primary" id="publish-project">Publish</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <button class="btn btn-outline-secondary" id="project-prev">Back</button>
            <div class="d-flex gap-2">
                <button class="btn btn-light" id="project-save">Save &amp; Exit</button>
                <button class="btn btn-primary" id="project-next">Next</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/freelance/projectWizard.js') }}"></script>
@endpush
