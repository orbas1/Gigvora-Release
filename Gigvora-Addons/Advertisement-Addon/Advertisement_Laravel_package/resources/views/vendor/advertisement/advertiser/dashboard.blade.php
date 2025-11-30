@extends('advertisement::layouts.app')

@section('title', 'Ads Performance Reports')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.dashboard') }}">Ads Manager</a></li>
        <li class="breadcrumb-item active" aria-current="page">Performance Reports</li>
    </ol>
</nav>
@endsection

@section('ads-page')
<div class="gv-ad-shell space-y-6">
    <div id="ads-dashboard-feedback" class="gv-alert gv-alert-warning d-none" role="status"></div>

    <header class="gv-card gv-ad-shell__header">
        <div>
            <p class="gv-eyebrow mb-1">{{ __('Insights') }}</p>
            <h1 class="gv-heading text-2xl mb-2">{{ __('Ads Performance Reports') }}</h1>
            <p class="gv-muted mb-0">{{ __('Monitor campaigns, inspect KPIs, and take quick actions without leaving the Gigvora shell.') }}</p>
        </div>
        <div class="gv-ad-shell__actions">
            <label class="gv-label mb-1">{{ __('Date range') }}</label>
            <div class="gv-input-combo">
                <input type="text" id="ads-date-range" class="gv-input" placeholder="{{ __('Select range') }}">
                <button class="gv-btn gv-btn-ghost gv-btn-sm" id="refresh-dashboard">{{ __('Refresh') }}</button>
            </div>
            <div class="gv-action-buttons">
                <a href="{{ route('advertisement.campaigns.create') }}" class="gv-btn gv-btn-primary">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> {{ __('New campaign') }}
                </a>
                <a href="{{ route('advertisement.campaigns.index') }}" class="gv-btn gv-btn-ghost">
                    {{ __('View campaigns') }}
                </a>
            </div>
        </div>
    </header>

    <section class="gv-grid gv-grid--metrics" id="ads-kpis">
        @php
            $stats = $kpis ?? [
                ['label' => 'Total Spend', 'value' => '$0', 'id' => 'spend'],
                ['label' => 'Impressions', 'value' => '0', 'id' => 'impressions'],
                ['label' => 'Clicks', 'value' => '0', 'id' => 'clicks'],
                ['label' => 'Conversions', 'value' => '0', 'id' => 'conversions'],
                ['label' => 'Avg CPC', 'value' => '$0.00', 'id' => 'cpc'],
                ['label' => 'Avg CPA', 'value' => '$0.00', 'id' => 'cpa'],
                ['label' => 'Avg CPM', 'value' => '$0.00', 'id' => 'cpm'],
            ];
        @endphp
        @foreach($stats as $stat)
            <article class="gv-kpi-card">
                <p class="gv-label">{{ $stat['label'] }}</p>
                <p class="gv-kpi-value" id="kpi-{{ $stat['id'] }}">{{ $stat['value'] }}</p>
            </article>
        @endforeach
    </section>

    <section class="gv-card space-y-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <p class="gv-label mb-1">{{ __('Performance trends') }}</p>
                <h3 class="gv-heading text-xl mb-0">{{ __('Spend, impressions, and clicks') }}</h3>
            </div>
            <select id="chart-metric" class="gv-input gv-input--sm w-auto">
                <option value="all">{{ __('All metrics') }}</option>
                <option value="spend">{{ __('Spend') }}</option>
                <option value="impressions">{{ __('Impressions') }}</option>
                <option value="clicks">{{ __('Clicks') }}</option>
            </select>
        </div>
        <div class="gv-chart">
            <canvas id="ads-performance-chart" height="120" class="w-100"></canvas>
        </div>
    </section>

    <section class="gv-card space-y-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <p class="gv-label mb-1">{{ __('Top campaigns') }}</p>
                <h3 class="gv-heading text-xl mb-0">{{ __('Pacing, CTR, and spend leaders') }}</h3>
            </div>
            <a href="{{ route('advertisement.campaigns.index') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ __('View all') }}</a>
        </div>
        <div class="gv-table-wrapper">
            <table class="gv-table">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Impressions') }}</th>
                        <th>{{ __('Clicks') }}</th>
                        <th>{{ __('CTR') }}</th>
                        <th>{{ __('Spend') }}</th>
                    </tr>
                </thead>
                <tbody id="top-campaigns-body">
                    @forelse(($topCampaigns ?? []) as $campaign)
                        <tr data-id="{{ $campaign['id'] ?? '' }}" class="campaign-row">
                            <td>
                                <div class="gv-table-title">{{ $campaign['name'] ?? 'Untitled' }}</div>
                                <small class="gv-muted">{{ $campaign['objective'] ?? __('Traffic') }}</small>
                            </td>
                            <td><span class="gv-chip gv-chip--success">{{ $campaign['status'] ?? 'Active' }}</span></td>
                            <td>{{ $campaign['impressions'] ?? 0 }}</td>
                            <td>{{ $campaign['clicks'] ?? 0 }}</td>
                            <td>{{ $campaign['ctr'] ?? '0%' }}</td>
                            <td>{{ $campaign['spend'] ?? '$0.00' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center gv-muted py-4">{{ __('No campaigns yet. Start by creating your first campaign.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>

<div class="modal fade" id="campaignDetailModal" tabindex="-1" aria-labelledby="campaignDetailLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="campaignDetailLabel">{{ __('Campaign details') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
            </div>
            <div class="modal-body" id="campaign-detail-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ mix('js/advertisement/dashboard.js') }}" defer></script>
@endpush
