@extends('layouts.app')

@section('title', 'Gig Wizard')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item"><a href="/freelance/freelancer/gigs">My Gigs</a></li>
        <li class="breadcrumb-item active" aria-current="page">Gig Wizard</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="gig-wizard" data-save-url="{{ $saveUrl ?? '' }}">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="mb-0">Create / Edit Gig</h3>
                <div class="text-muted">Step <span id="wizard-step">1</span> of 5</div>
            </div>
            <ul class="nav nav-pills mb-4" id="wizard-tabs">
                <li class="nav-item"><a class="nav-link active" data-step="1" href="#">Overview</a></li>
                <li class="nav-item"><a class="nav-link" data-step="2" href="#">Pricing</a></li>
                <li class="nav-item"><a class="nav-link" data-step="3" href="#">Description & FAQ</a></li>
                <li class="nav-item"><a class="nav-link" data-step="4" href="#">Requirements</a></li>
                <li class="nav-item"><a class="nav-link" data-step="5" href="#">Preview</a></li>
            </ul>

            <div id="wizard-panels">
                <div class="wizard-panel" data-step="1">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" value="{{ $gig['title'] ?? '' }}">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select class="form-select" name="category">
                                <option value="">Select</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category }}" @selected(($gig['category'] ?? '') === $category)>{{ $category }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tags</label>
                            <input type="text" class="form-control" name="tags" value="{{ $gig['tags'] ?? '' }}" placeholder="design, web, ux">
                        </div>
                    </div>
                </div>

                <div class="wizard-panel d-none" data-step="2">
                    <div class="row g-3">
                        @foreach(['Basic', 'Standard', 'Premium'] as $package)
                            <div class="col-md-4">
                                <div class="border rounded p-3 h-100">
                                    <h6 class="mb-2">{{ $package }}</h6>
                                    <div class="mb-2">
                                        <label class="form-label">Price</label>
                                        <input type="number" min="0" class="form-control" name="packages[{{ strtolower($package) }}][price]" placeholder="100">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Delivery time (days)</label>
                                        <input type="number" min="1" class="form-control" name="packages[{{ strtolower($package) }}][delivery]" placeholder="3">
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Revisions</label>
                                        <input type="number" min="0" class="form-control" name="packages[{{ strtolower($package) }}][revisions]" placeholder="2">
                                    </div>
                                    <div class="text-muted small">Net earnings: <span class="net-earning" data-package="{{ strtolower($package) }}">$0</span></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="wizard-panel d-none" data-step="3">
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" rows="6" name="description">{{ $gig['description'] ?? '' }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Frequently Asked Questions</label>
                        <div id="faq-list"></div>
                        <button class="btn btn-outline-secondary btn-sm" id="add-faq">Add FAQ</button>
                    </div>
                </div>

                <div class="wizard-panel d-none" data-step="4">
                    <div id="requirements-list">
                        <div class="mb-3 requirement-item">
                            <label class="form-label">Requirement</label>
                            <input type="text" class="form-control" name="requirements[]" placeholder="Describe what you need from the client">
                        </div>
                    </div>
                    <button class="btn btn-outline-secondary btn-sm" id="add-requirement">Add requirement</button>
                </div>

                <div class="wizard-panel d-none" data-step="5">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="border rounded p-3">
                                <h5 class="mb-2" id="preview-title">{{ $gig['title'] ?? 'Gig preview title' }}</h5>
                                <p class="text-muted" id="preview-description">{{ $gig['description'] ?? 'Detailed gig description preview goes here.' }}</p>
                                <div id="preview-faq"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded p-3">
                                <h6>Fee breakdown</h6>
                                <p class="mb-1">Platform commission: <span id="commission-preview">10%</span></p>
                                <p class="mb-0">Net earnings: <span id="net-preview">$0</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 d-flex gap-2 justify-content-end">
                        <button class="btn btn-outline-secondary" id="save-draft">Save as Draft</button>
                        <button class="btn btn-primary" id="publish-gig">Publish</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between">
            <button class="btn btn-outline-secondary" id="wizard-prev">Back</button>
            <div class="d-flex gap-2">
                <button class="btn btn-light" id="wizard-save">Save &amp; Exit</button>
                <button class="btn btn-primary" id="wizard-next">Next</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/freelance/gigWizard.js') }}"></script>
@endpush
