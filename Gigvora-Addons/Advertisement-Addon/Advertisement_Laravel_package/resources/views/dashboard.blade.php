@extends('advertisement::layouts.app')

@section('title', 'Gigvora Ads Manager')

@push('scripts')
    <script src="{{ mix('js/advertisement/dashboard.js') }}" defer></script>
@endpush

@section('ads-page')
    <div class="ads-page">
        <div class="ads-page__header">
            <div>
                <h1 class="ads-page__title">Gigvora Ads Manager</h1>
                <p class="ads-page__subtitle">Manage campaigns, creatives, targeting, and affiliate payouts in one place.</p>
            </div>
            <div class="ads-actions">
                <a class="ads-button ads-button--primary" href="{{ route('advertisement.campaigns.create') }}">Create campaign</a>
                <a class="ads-button ads-button--ghost" href="{{ route('advertisement.campaigns.index') }}">Keyword planner</a>
            </div>
        </div>

        <div class="ads-grid ads-grid--thirds">
            <article class="ads-card">
                <h2 class="ads-card__title">Performance overview</h2>
                <p class="ads-card__body">Track spend, impressions, clicks, and conversions across all live placements.</p>
            </article>
            <article class="ads-card">
                <h2 class="ads-card__title">Forecast & planner</h2>
                <p class="ads-card__body">Model reach and budgets before campaigns go live using AI-assisted estimators.</p>
            </article>
            <article class="ads-card">
                <h2 class="ads-card__title">Affiliate payouts</h2>
                <p class="ads-card__body">Monitor referrals and payouts across web and mobile surfaces.</p>
            </article>
        </div>
    </div>
@endsection
