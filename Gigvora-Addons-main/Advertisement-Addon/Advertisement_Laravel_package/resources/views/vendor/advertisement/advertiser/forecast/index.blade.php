@extends('layouts.app')

@section('title', 'Forecast')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Forecast</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Adjust Inputs</h5>
            </div>
            <div class="card-body">
                <form id="forecast-form" class="d-flex flex-column gap-3">
                    <div>
                        <label class="form-label">Daily Budget</label>
                        <input type="range" class="form-range" name="budget" id="forecast-budget" min="10" max="1000" step="10" value="100">
                        <div class="d-flex justify-content-between text-muted small">
                            <span>$10</span><span id="forecast-budget-label">$100</span><span>$1000</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Duration (days)</label>
                        <input type="range" class="form-range" name="duration" id="forecast-duration" min="1" max="60" value="7">
                        <div class="d-flex justify-content-between text-muted small">
                            <span>1</span><span id="forecast-duration-label">7</span><span>60</span>
                        </div>
                    </div>
                    <div>
                        <label class="form-label">Target Campaign</label>
                        <select name="campaign_id" class="form-select">
                            <option value="">Use custom targeting</option>
                            @foreach(($campaignOptions ?? []) as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Forecast</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Forecast Results</h5>
                <button class="btn btn-success btn-sm" id="apply-forecast">Apply these settings to Campaign</button>
            </div>
            <div class="card-body">
                <div class="row g-3" id="forecast-results">
                    @foreach([
                        ['label' => 'Impressions', 'id' => 'forecast-impressions'],
                        ['label' => 'Clicks', 'id' => 'forecast-clicks'],
                        ['label' => 'Conversions', 'id' => 'forecast-conversions'],
                        ['label' => 'Cost', 'id' => 'forecast-cost'],
                    ] as $metric)
                        <div class="col-md-6">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <p class="text-muted small mb-1">{{ $metric['label'] }}</p>
                                    <h4 class="fw-bold mb-0" id="{{ $metric['id'] }}">--</h4>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4">
                    <h6 class="mb-2">Projected Performance</h6>
                    <canvas id="forecast-chart" height="120" class="w-100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/advertisement/forecast.js') }}"></script>
@endpush
