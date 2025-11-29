@extends('layouts.app')

@section('title', 'Gigvora Ads Manager')

@push('scripts')
    <script src="{{ mix('js/advertisement/dashboard.js') }}" defer></script>
@endpush

@section('content')
<div class="container">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div>
            <h1 class="mb-2">Gigvora Ads Manager</h1>
            <p class="text-muted mb-0">Manage campaigns, creatives, targeting, and affiliate payouts in one place.</p>
        </div>
        <div class="d-flex gap-2 mt-3 mt-md-0">
            <a class="btn btn-primary" href="#">Create campaign</a>
            <a class="btn btn-outline-secondary" href="#">Keyword planner</a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Performance overview</h5>
                    <p class="card-text text-muted">Track spend, impressions, clicks, and conversions across active Gigvora placements.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Forecasts & keyword planner</h5>
                    <p class="card-text text-muted">Model reach and budgets using the integrated forecast and planner widgets.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title">Affiliate payouts</h5>
                    <p class="card-text text-muted">Monitor referrals and payout statuses for web and mobile partner traffic.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
