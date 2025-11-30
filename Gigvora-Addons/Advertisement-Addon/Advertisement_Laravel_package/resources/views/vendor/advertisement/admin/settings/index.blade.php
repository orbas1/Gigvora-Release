@extends('admin.layouts.app')

@section('title', 'Ads Settings')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.admin.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Settings</li>
    </ol>
</nav>
@endsection

@section('ads-page')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Global Settings</h5>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-budgets" type="button" role="tab">Budgets & Limits</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-keyword" type="button" role="tab">Keyword Pricing</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-placements" type="button" role="tab">Placements</button></li>
            <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-compliance" type="button" role="tab">Compliance</button></li>
        </ul>
        <div class="tab-content pt-3">
            <div class="tab-pane fade show active" id="tab-budgets" role="tabpanel">
                <form class="d-flex flex-column gap-3 settings-section" data-section="budgets">
                    <div>
                        <label class="form-label">Default Daily Budget</label>
                        <input type="number" step="0.01" class="form-control" name="default_daily_budget" value="{{ $settings['default_daily_budget'] ?? '' }}">
                    </div>
                    <div>
                        <label class="form-label">Max Active Campaigns per Advertiser</label>
                        <input type="number" class="form-control" name="max_campaigns" value="{{ $settings['max_campaigns'] ?? '' }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
            <div class="tab-pane fade" id="tab-keyword" role="tabpanel">
                <form class="d-flex flex-column gap-3 settings-section" data-section="keyword">
                    <div>
                        <label class="form-label">Base CPC</label>
                        <input type="number" step="0.01" class="form-control" name="base_cpc" value="{{ $settings['base_cpc'] ?? '' }}">
                    </div>
                    <div>
                        <label class="form-label">Base CPA</label>
                        <input type="number" step="0.01" class="form-control" name="base_cpa" value="{{ $settings['base_cpa'] ?? '' }}">
                    </div>
                    <div>
                        <label class="form-label">Base CPM</label>
                        <input type="number" step="0.01" class="form-control" name="base_cpm" value="{{ $settings['base_cpm'] ?? '' }}">
                    </div>
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
            <div class="tab-pane fade" id="tab-placements" role="tabpanel">
                <form class="d-flex flex-column gap-2 settings-section" data-section="placements">
                    @foreach(['feed','profile','search','jobs','gigs','podcasts','webinars','networking'] as $placement)
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="placements[{{ $placement }}]" id="placement-{{ $placement }}" @checked($settings['placements'][$placement] ?? true)>
                            <label class="form-check-label" for="placement-{{ $placement }}">Enable {{ ucfirst($placement) }}</label>
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
            <div class="tab-pane fade" id="tab-compliance" role="tabpanel">
                <form class="d-flex flex-column gap-2 settings-section" data-section="compliance">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="require_manual_approval" id="require_manual_approval" @checked($settings['require_manual_approval'] ?? false)>
                        <label class="form-check-label" for="require_manual_approval">Require manual approval before serving</label>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="block_sensitive_keywords" id="block_sensitive_keywords" @checked($settings['block_sensitive_keywords'] ?? false)>
                        <label class="form-check-label" for="block_sensitive_keywords">Block sensitive keywords</label>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/advertisement/admin.js') }}"></script>
@endpush
