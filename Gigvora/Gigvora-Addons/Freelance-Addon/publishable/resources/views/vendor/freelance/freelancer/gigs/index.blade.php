@extends('layouts.app')

@section('title', 'My Gigs')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item"><a href="/freelance/freelancer/dashboard">Freelancer</a></li>
        <li class="breadcrumb-item active" aria-current="page">My Gigs</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="gig-list" data-fetch-url="{{ $filterUrl ?? '' }}">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">My Gigs</h2>
        <a href="/freelance/freelancer/gigs/create" class="btn btn-primary">Create New Gig</a>
    </div>

    @component('vendor.freelance.components.filter_bar', [
        'filters' => [
            ['label' => 'Status', 'name' => 'status', 'options' => ['' => 'All', 'draft' => 'Draft', 'active' => 'Active', 'paused' => 'Paused', 'denied' => 'Denied'], 'value' => request('status')]
        ],
        'showSearch' => true,
        'search' => request('search')
    ])@endcomponent

    <div id="gig-list-container">
        @forelse($gigs ?? [] as $gig)
            @component('vendor.freelance.components.gig_card', ['gig' => $gig])@endcomponent
        @empty
            <div class="alert alert-light">No gigs yet. Start by creating one.</div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
    import initStatusFilter from '{{ mix('js/freelance/gigsIndex.js') }}';
    initStatusFilter();
</script>
@endpush
