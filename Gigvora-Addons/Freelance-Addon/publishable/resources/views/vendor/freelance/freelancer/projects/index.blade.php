@extends('freelance::layouts.freelance')

@section('title', 'Browse Projects')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Browse Projects</li>
    </ol>
</nav>
@endsection

@section('freelance-content')
<div class="container py-4" id="projects-browse" data-fetch-url="{{ $fetchUrl ?? '' }}">
    <div class="d-flex align-items-center mb-3">
        <h2 class="mb-0">Projects</h2>
        <div class="ms-auto d-flex gap-2">
            <input type="search" name="q" class="form-control" placeholder="Search projects">
            <button class="btn btn-outline-primary" id="search-projects">Search</button>
        </div>
    </div>
    @component('vendor.freelance.components.filter_bar', [
        'filters' => [
            ['label' => 'Category', 'name' => 'category', 'options' => $categories ?? []],
            ['label' => 'Budget', 'name' => 'budget', 'options' => ['' => 'Any', '0-500' => '$0-500', '500-1000' => '$500-1k', '1000+' => '$1k+']],
            ['label' => 'Type', 'name' => 'type', 'options' => ['' => 'Any', 'fixed' => 'Fixed', 'hourly' => 'Hourly']],
            ['label' => 'Client rating', 'name' => 'rating', 'options' => ['' => 'Any', '4+' => '4+ Stars', '5' => '5 Stars']],
        ],
        'showSearch' => false
    ])@endcomponent

    <div id="projects-list">
        @forelse($projects ?? [] as $project)
            @component('vendor.freelance.components.project_card', ['project' => $project])@endcomponent
        @empty
            <div class="alert alert-light">No projects match your filters.</div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/freelance/projectBrowse.js') }}"></script>
@endpush
