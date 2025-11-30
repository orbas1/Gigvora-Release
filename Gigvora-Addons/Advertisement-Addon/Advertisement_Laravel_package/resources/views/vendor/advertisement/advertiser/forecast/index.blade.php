@extends('advertisement::layouts.app')

@section('title', 'Forecast')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Forecast</li>
    </ol>
</nav>
@endsection

@section('ads-page')
<div class="gv-forecast-grid">
    <section class="gv-card h-100">
        <div class="space-y-4">
            <div>
                <p class="gv-label mb-1">{{ __('Budget') }}</p>
                <label class="gv-label">{{ __('Daily budget') }}</label>
                <input type="range" class="form-range" name="budget" id="forecast-budget" min="10" max="1000" step="10" value="100">
                <div class="d-flex justify-content-between gv-muted small">
                    <span>$10</span><span id="forecast-budget-label">$100</span><span>$1000</span>
                </div>
            </div>
            <div>
                <label class="gv-label">{{ __('Duration (days)') }}</label>
                <input type="range" class="form-range" name="duration" id="forecast-duration" min="1" max="60" value="7">
                <div class="d-flex justify-content-between gv-muted small">
                    <span>1</span><span id="forecast-duration-label">7</span><span>60</span>
                </div>
            </div>
            <div>
                <label class="gv-label">{{ __('Target campaign') }}</label>
                <select name="campaign_id" class="gv-input" form="forecast-form">
                    <option value="">{{ __('Use custom targeting') }}</option>
                    @foreach(($campaignOptions ?? []) as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <form id="forecast-form" class="mt-4">
            @csrf
            <button type="submit" class="gv-btn gv-btn-primary w-100">{{ __('Update forecast') }}</button>
        </form>
    </section>

    <section class="gv-card space-y-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <p class="gv-label mb-1">{{ __('Forecast results') }}</p>
                <h5 class="gv-heading text-lg mb-0">{{ __('Projected performance') }}</h5>
            </div>
            <button class="gv-btn gv-btn-ghost gv-btn-sm" id="apply-forecast">{{ __('Apply to campaign') }}</button>
        </div>
        <div class="gv-grid-two" id="forecast-results">
            @foreach([
                ['label' => 'Impressions', 'id' => 'forecast-impressions'],
                ['label' => 'Clicks', 'id' => 'forecast-clicks'],
                ['label' => 'Conversions', 'id' => 'forecast-conversions'],
                ['label' => 'Cost', 'id' => 'forecast-cost'],
            ] as $metric)
                <article class="gv-kpi-card">
                    <p class="gv-label">{{ __($metric['label']) }}</p>
                    <p class="gv-kpi-value" id="{{ $metric['id'] }}">--</p>
                </article>
            @endforeach
        </div>
        <div class="gv-chart">
            <canvas id="forecast-chart" height="120" class="w-100"></canvas>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/advertisement/forecast.js') }}"></script>
@endpush
