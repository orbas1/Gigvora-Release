@extends('advertisement::layouts.app')

@section('title', 'Keyword Planner')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Keyword Planner</li>
    </ol>
</nav>
@endsection

@section('ads-page')
<div class="gv-card">
    <form id="keyword-planner-form" class="gv-filter-grid">
        <div>
            <label class="gv-label">{{ __('Seed keywords or URL') }}</label>
            <input type="text" name="query" id="keyword-query" class="gv-input" placeholder="{{ __('Enter keyword or URL') }}">
        </div>
        <div>
            <label class="gv-label">{{ __('Location') }}</label>
            <input type="text" name="location" class="gv-input" placeholder="{{ __('Global') }}">
        </div>
        <div>
            <label class="gv-label">{{ __('Language') }}</label>
            <input type="text" name="language" class="gv-input" placeholder="{{ __('Any') }}">
        </div>
        <div class="gv-filter-actions">
            <button class="gv-btn gv-btn-primary" type="submit">{{ __('Search') }}</button>
        </div>
    </form>
</div>

<div class="gv-card space-y-3">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <p class="gv-label mb-1">{{ __('Results') }}</p>
            <h5 class="gv-heading text-lg mb-0">{{ __('Estimated CPC, volume and conversions') }}</h5>
        </div>
        <button class="gv-btn gv-btn-ghost gv-btn-sm" id="export-keywords">{{ __('Export to campaign') }}</button>
    </div>
    <div class="gv-table-wrapper">
        <table class="gv-table" id="keyword-results-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all-keywords"></th>
                    <th>Keyword</th>
                    <th>Suggested CPC</th>
                    <th>Estimated CPA</th>
                    <th>Impressions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="5" class="text-center gv-muted py-4">{{ __('Start searching to see results.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/advertisement/keyword_planner.js') }}"></script>
@endpush
