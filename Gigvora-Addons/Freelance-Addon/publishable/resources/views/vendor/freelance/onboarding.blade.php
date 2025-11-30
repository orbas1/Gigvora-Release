@extends('freelance::layouts.freelance')

@section('title', 'Freelance Onboarding')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Freelance Onboarding</li>
    </ol>
</nav>
@endsection

@section('freelance-content')
<div class="container py-4" id="freelance-onboarding" data-fetch-url="{{ $saveUrl ?? '#' }}">
    <div class="row mb-4">
        <div class="col-12">
            <div class="p-4 bg-light rounded">
                <h1 class="fw-bold">Start Freelancing · Hire Talent</h1>
                <p class="text-muted mb-0">Enable freelancer and client capabilities to unlock gigs, projects, escrow and more.</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4>Become a Freelancer</h4>
                            <ul class="text-muted small mb-0">
                                <li>Bid on projects and send proposals.</li>
                                <li>Publish gigs with clear pricing.</li>
                                <li>Track milestones and payouts.</li>
                            </ul>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input role-toggle" type="checkbox" id="freelancerToggle" data-role="freelancer" @checked(!empty($roles['freelancer']))>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Skills</label>
                        <input type="text" class="form-control" name="skills" value="{{ $profile['skills'] ?? '' }}" placeholder="e.g. UX Design, Laravel, Flutter">
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Hourly rate</label>
                            <input type="number" min="0" class="form-control" name="hourly_rate" value="{{ $profile['hourly_rate'] ?? '' }}" placeholder="40">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Timezone</label>
                            <input type="text" class="form-control" name="timezone" value="{{ $profile['timezone'] ?? '' }}" placeholder="UTC">
                        </div>
                    </div>
                    <div class="mt-3">
                        <label class="form-label">Short bio</label>
                        <textarea class="form-control" rows="3" name="bio" placeholder="Describe your experience">{{ $profile['bio'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4>Become a Client</h4>
                            <ul class="text-muted small mb-0">
                                <li>Post projects and receive proposals.</li>
                                <li>Hire confidently with escrow.</li>
                                <li>Manage milestones and disputes.</li>
                            </ul>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input role-toggle" type="checkbox" id="clientToggle" data-role="client" @checked(!empty($roles['client']))>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Team / Company</label>
                        <input type="text" class="form-control" name="company" value="{{ $profile['company'] ?? '' }}" placeholder="Acme Inc.">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Hiring needs</label>
                        <textarea class="form-control" rows="4" name="needs" placeholder="Describe the type of work you need">{{ $profile['needs'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-semibold">Profile completeness</span>
                        <span class="text-muted small" id="profile-percent">{{ $profile['percent'] ?? 0 }}%</span>
                    </div>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar" role="progressbar" style="width: {{ $profile['percent'] ?? 0 }}%" aria-valuenow="{{ $profile['percent'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">Complete skills, hourly rate, timezone and bio to unlock the marketplace.</small>
                </div>
            </div>
        </div>
        <div class="col-lg-4 d-flex align-items-center justify-content-lg-end mt-3 mt-lg-0">
            <button class="btn btn-primary btn-lg w-100" id="save-onboarding">Save &amp; Continue</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/freelance/freelanceOnboarding.js') }}"></script>
@endpush
