@extends('admin.layouts.app')

@section('title', 'Keyword Pricing')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('advertisement.admin.dashboard') }}">Ads Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Keyword Pricing</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Pricing Table</h5>
        <div class="d-flex align-items-center gap-2">
            <input type="text" class="form-control form-control-sm" placeholder="Filter keyword" id="keyword-filter" style="width: 200px;">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table align-middle" id="keyword-pricing-table">
            <thead>
                <tr>
                    <th>Keyword</th>
                    <th>Base CPC</th>
                    <th>CPA</th>
                    <th>CPM</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                @forelse(($keywords ?? []) as $keyword)
                    <tr data-id="{{ $keyword['id'] ?? 0 }}">
                        <td>{{ $keyword['keyword'] ?? '' }}</td>
                        <td contenteditable class="editable" data-field="cpc">{{ $keyword['cpc'] ?? '' }}</td>
                        <td contenteditable class="editable" data-field="cpa">{{ $keyword['cpa'] ?? '' }}</td>
                        <td contenteditable class="editable" data-field="cpm">{{ $keyword['cpm'] ?? '' }}</td>
                        <td>{{ $keyword['updated_at'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No keywords.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer" id="keyword-pagination">{{ $pagination ?? '' }}</div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/advertisement/admin.js') }}"></script>
@endpush
