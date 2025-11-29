@extends('layouts.app')

@section('title', 'Project Detail')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/freelance/client/projects">Projects</a></li>
        <li class="breadcrumb-item active" aria-current="page">Project Detail</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="client-project-detail" data-project-id="{{ $project['id'] ?? '' }}">
    <div class="card mb-3">
        <div class="card-body">
            <h2 class="mb-1">{{ $project['title'] ?? '' }}</h2>
            <p class="text-muted">{{ $project['budget'] ?? '' }} · {{ ucfirst($project['type'] ?? 'fixed') }}</p>
            <p class="mb-0">{{ $project['description'] ?? '' }}</p>
        </div>
    </div>

    <ul class="nav nav-tabs mb-3" id="project-tabs">
        <li class="nav-item"><a class="nav-link active" data-tab="proposals" href="#">Proposals</a></li>
        <li class="nav-item"><a class="nav-link" data-tab="overview" href="#">Overview</a></li>
        <li class="nav-item"><a class="nav-link" data-tab="activity" href="#">Activity</a></li>
    </ul>

    <div id="tab-proposals">
        <div class="d-flex gap-2 mb-3">
            <select class="form-select form-select-sm" id="filter-bid">
                <option value="">Bid amount</option>
                <option value="low">Lowest first</option>
                <option value="high">Highest first</option>
            </select>
            <select class="form-select form-select-sm" id="filter-rating">
                <option value="">Rating</option>
                <option value="4+">4+ Stars</option>
                <option value="5">5 Stars</option>
            </select>
            <select class="form-select form-select-sm" id="filter-time">
                <option value="">Delivery time</option>
                <option value="quick">Fastest</option>
                <option value="long">Longest</option>
            </select>
        </div>
        <div id="proposal-cards">
            @forelse($proposals ?? [] as $proposal)
                <div class="card mb-3" data-proposal-id="{{ $proposal['id'] }}">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">{{ $proposal['freelancer'] }}</h5>
                                <p class="text-muted mb-1">Rating: {{ $proposal['rating'] }}</p>
                                <p class="mb-2">{{ $proposal['pitch'] }}</p>
                                <div class="small text-muted">Bid: {{ $proposal['amount'] }} · {{ $proposal['duration'] }} days</div>
                            </div>
                            <div class="d-grid gap-2">
                                <button class="btn btn-outline-primary btn-sm" data-action="view">View Proposal</button>
                                <button class="btn btn-outline-secondary btn-sm" data-action="shortlist">Shortlist</button>
                                <button class="btn btn-outline-danger btn-sm" data-action="reject">Reject</button>
                                <button class="btn btn-primary btn-sm" data-action="hire">Hire</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-light">No proposals yet.</div>
            @endforelse
        </div>
    </div>
    <div id="tab-overview" class="d-none">
        <div class="card">
            <div class="card-body">
                <p class="mb-0">{{ $project['description'] ?? 'No description.' }}</p>
            </div>
        </div>
    </div>
    <div id="tab-activity" class="d-none">
        <div class="card">
            <div class="card-body">
                <ul class="timeline list-unstyled mb-0">
                    @forelse($activity ?? [] as $item)
                        <li class="mb-2">{{ $item }}</li>
                    @empty
                        <li class="text-muted">No activity yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/freelance/projectProposals.js') }}"></script>
@endpush
