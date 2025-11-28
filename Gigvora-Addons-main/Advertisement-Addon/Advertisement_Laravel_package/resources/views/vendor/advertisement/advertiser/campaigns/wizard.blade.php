@extends('layouts.app')

@section('title', ($campaign['id'] ?? false) ? 'Edit Campaign' : 'Create Campaign')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('advertisement.campaigns.index') }}">Campaigns</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ ($campaign['id'] ?? false) ? 'Edit' : 'Create' }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Campaign Wizard</h4>
        <div class="text-muted small">Step <span id="wizard-step-label">1</span> of 5</div>
    </div>
    <div class="card-body">
        <form id="campaign-wizard-form" method="POST" action="{{ $action ?? '' }}">
            @csrf
            <div class="wizard-step" data-step="1">
                <h5>Objective & Naming</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Campaign Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $campaign['name'] ?? '' }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Objective</label>
                        <select name="objective" class="form-select" required>
                            <option value="">Select objective</option>
                            <option value="traffic">Traffic</option>
                            <option value="conversions">Conversions</option>
                            <option value="awareness">Awareness</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="wizard-step d-none" data-step="2">
                <h5>Audience / Targeting</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Gender</label>
                        <select name="gender[]" class="form-select" multiple>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="nonbinary">Non-binary</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Locations</label>
                        <input type="text" name="locations" class="form-control" placeholder="Country, Region, City" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Interests/Tags</label>
                        <input type="text" name="interests" class="form-control" placeholder="e.g. marketing, fitness" />
                    </div>
                    <div class="col-12">
                        <label class="form-label">Keywords</label>
                        <textarea name="keywords" class="form-control" rows="2" placeholder="Enter keywords separated by commas"></textarea>
                    </div>
                </div>
            </div>

            <div class="wizard-step d-none" data-step="3">
                <h5>Placements</h5>
                <div class="row g-3">
                    @foreach(['Feed','Profile','Search','Jobs','Gigs/Projects','Podcasts','Webinars','Networking'] as $placement)
                        <div class="col-md-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="placements[]" value="{{ strtolower($placement) }}" id="placement-{{ strtolower($placement) }}">
                                <label class="form-check-label" for="placement-{{ strtolower($placement) }}">{{ $placement }}</label>
                            </div>
                            <p class="text-muted small mb-0">Recommended assets vary per placement.</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="wizard-step d-none" data-step="4">
                <h5>Budget & Schedule</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Budget Type</label>
                        <select name="budget_type" class="form-select">
                            <option value="daily">Daily</option>
                            <option value="lifetime">Lifetime</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Amount</label>
                        <input type="number" step="0.01" name="budget_amount" class="form-control" />
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Bidding Model</label>
                        <select name="bidding_model" class="form-select">
                            <option value="cpc">CPC</option>
                            <option value="cpa">CPA</option>
                            <option value="cpm">CPM</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Start Date/Time</label>
                        <input type="datetime-local" name="start_at" class="form-control" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Date/Time</label>
                        <input type="datetime-local" name="end_at" class="form-control" />
                    </div>
                </div>
                <div class="mt-3 p-3 bg-light rounded" id="budget-preview">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Estimated Reach</h6>
                            <p class="text-muted mb-0 small" id="estimate-description">Adjust budget to see forecast.</p>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold" id="estimate-impressions">--</div>
                            <small class="text-muted">Impressions</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold" id="estimate-clicks">--</div>
                            <small class="text-muted">Clicks</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="wizard-step d-none" data-step="5">
                <h5>Review & Confirm</h5>
                <div class="row g-3" id="review-summary">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="text-muted text-uppercase small">Campaign</h6>
                                <p class="mb-1"><strong>Name:</strong> <span data-summary="name"></span></p>
                                <p class="mb-1"><strong>Objective:</strong> <span data-summary="objective"></span></p>
                                <p class="mb-1"><strong>Placements:</strong> <span data-summary="placements"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="text-muted text-uppercase small">Budget</h6>
                                <p class="mb-1"><strong>Type:</strong> <span data-summary="budget_type"></span></p>
                                <p class="mb-1"><strong>Amount:</strong> <span data-summary="budget_amount"></span></p>
                                <p class="mb-1"><strong>Schedule:</strong> <span data-summary="schedule"></span></p>
                                <p class="mb-1"><strong>Bidding:</strong> <span data-summary="bidding_model"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info mt-3 mb-0">Ensure all steps are valid before submitting. You can go back to adjust details.</div>
            </div>
        </form>
    </div>
    <div class="card-footer d-flex justify-content-between">
        <button class="btn btn-outline-secondary" id="wizard-prev" disabled>Back</button>
        <button class="btn btn-primary" id="wizard-next">Next</button>
        <button class="btn btn-success d-none" id="wizard-submit">{{ ($campaign['id'] ?? false) ? 'Save Changes' : 'Create Campaign' }}</button>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/advertisement/wizard.js') }}"></script>
@endpush
