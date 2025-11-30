@extends('advertisement::layouts.app')

@section('title', 'Campaigns')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Campaigns</li>
    </ol>
</nav>
@endsection

@section('ads-page')
<div class="gv-card space-y-2">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <p class="gv-label mb-1">{{ __('Campaigns') }}</p>
            <h1 class="gv-heading text-2xl mb-0">{{ __('Manage and filter all campaigns') }}</h1>
        </div>
        <a href="{{ route('advertisement.campaigns.create') }}" class="gv-btn gv-btn-primary">{{ __('Create campaign') }}</a>
    </div>
</div>

<div class="gv-card">
    <form id="campaign-filter-form" class="gv-filter-grid">
        <div>
            <label class="gv-label">{{ __('Search') }}</label>
            <input type="text" name="search" class="gv-input" placeholder="{{ __('Campaign name') }}" />
        </div>
        <div>
            <label class="gv-label">{{ __('Status') }}</label>
            <select name="status" class="gv-input">
                <option value="">{{ __('Any') }}</option>
                <option value="active">{{ __('Active') }}</option>
                <option value="paused">{{ __('Paused') }}</option>
                <option value="completed">{{ __('Completed') }}</option>
                <option value="draft">{{ __('Draft') }}</option>
            </select>
        </div>
        <div>
            <label class="gv-label">{{ __('Objective') }}</label>
            <select name="objective" class="gv-input">
                <option value="">{{ __('Any') }}</option>
                <option value="traffic">{{ __('Traffic') }}</option>
                <option value="conversions">{{ __('Conversions') }}</option>
                <option value="awareness">{{ __('Awareness') }}</option>
            </select>
        </div>
        <div>
            <label class="gv-label">{{ __('Date range') }}</label>
            <input type="text" name="date_range" class="gv-input" placeholder="{{ __('Select dates') }}" />
        </div>
        <div class="gv-filter-actions">
            <button type="reset" class="gv-btn gv-btn-ghost" id="campaign-filter-reset">{{ __('Reset') }}</button>
            <button type="submit" class="gv-btn gv-btn-primary">{{ __('Filter') }}</button>
        </div>
    </form>
</div>

<div class="gv-card">
    <div class="gv-table-wrapper">
        <table class="gv-table" id="campaigns-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Objective</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Impressions</th>
                    <th>Clicks</th>
                    <th>CTR</th>
                    <th>Spend</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($campaigns ?? []) as $campaign)
                    <tr data-id="{{ $campaign['id'] ?? '' }}">
                        <td>
                            <div class="gv-table-title">{{ $campaign['name'] ?? 'Untitled' }}</div>
                            <small class="gv-muted">{{ $campaign['placement'] ?? 'newsfeed' }}</small>
                        </td>
                        <td><span class="gv-chip gv-chip--success">{{ ucfirst($campaign['status'] ?? 'active') }}</span></td>
                        <td>{{ $campaign['objective'] ?? 'Traffic' }}</td>
                        <td>{{ $campaign['start_date'] ?? '-' }}</td>
                        <td>{{ $campaign['end_date'] ?? '-' }}</td>
                        <td>{{ $campaign['impressions'] ?? 0 }}</td>
                        <td>{{ $campaign['clicks'] ?? 0 }}</td>
                        <td>{{ $campaign['ctr'] ?? '0%' }}</td>
                        <td>{{ $campaign['spend'] ?? '$0.00' }}</td>
                        <td class="text-end">
                            <div class="gv-table-actions">
                                <a class="gv-btn gv-btn-text" href="{{ route('advertisement.campaigns.show', $campaign['id'] ?? 0) }}">{{ __('View') }}</a>
                                <button class="gv-btn gv-btn-text campaign-pause" data-action="toggle">{{ __('Pause/Resume') }}</button>
                                <button class="gv-btn gv-btn-text campaign-duplicate">{{ __('Duplicate') }}</button>
                                <button class="gv-btn gv-btn-text text-danger campaign-archive">{{ __('Archive') }}</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center gv-muted py-4">{{ __('No campaigns found.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-3" id="campaign-pagination">
        {{ $campaignsPagination ?? '' }}
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/advertisement/campaigns.js') }}"></script>
@endpush
