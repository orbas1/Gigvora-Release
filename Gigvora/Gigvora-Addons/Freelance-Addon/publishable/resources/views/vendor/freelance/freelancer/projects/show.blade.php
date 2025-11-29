@extends('layouts.app')

@section('title', 'Project Detail')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/freelance/freelancer/projects">Projects</a></li>
        <li class="breadcrumb-item active" aria-current="page">Project Detail</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="project-detail" data-project-id="{{ $project['id'] ?? '' }}">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h2 class="mb-0">{{ $project['title'] ?? 'Project Title' }}</h2>
                        <span class="badge bg-light text-dark">{{ ucfirst($project['type'] ?? 'Fixed') }}</span>
                    </div>
                    <p class="text-muted">Budget: {{ $project['budget'] ?? '' }}</p>
                    <div class="project-description">
                        <p class="mb-0">{{ $project['description'] ?? 'Project description goes here.' }}</p>
                        <a href="#" class="small" id="toggle-description">Read more</a>
                    </div>
                    <div class="mt-3">
                        <h6>Client</h6>
                        @component('vendor.freelance.components.user_badge', ['user' => $project['client'] ?? []])@endcomponent
                    </div>
                    <div class="mt-3 small text-muted">Proposals: {{ $project['proposals_count'] ?? 0 }}</div>
                </div>
            </div>
            <div class="card">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        @if(!empty($project['proposal']))
                            <p class="mb-1">Your proposal: {{ $project['proposal']['amount'] }}</p>
                            <small class="text-muted">{{ $project['proposal']['status'] }}</small>
                        @else
                            <p class="mb-0">Ready to apply? Submit your best proposal.</p>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        <a class="btn btn-outline-secondary" href="/freelance/freelancer/projects">Save</a>
                        <a class="btn btn-primary" href="{{ $proposalUrl ?? '#' }}">{{ empty($project['proposal']) ? 'Submit Proposal' : 'Edit Proposal' }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h6>Milestone hints</h6>
                    <p class="text-muted mb-0">{{ $project['milestones_hint'] ?? 'Suggest milestones in your proposal for clarity.' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/freelance/projectDetail.js') }}"></script>
@endpush
