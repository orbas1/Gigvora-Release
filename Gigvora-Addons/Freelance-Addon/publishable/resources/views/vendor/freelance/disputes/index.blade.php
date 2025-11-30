@extends('freelance::layouts.freelance')

@section('title', 'Dispute Centre')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Disputes</li>
    </ol>
</nav>
@endsection

@section('freelance-content')
<div class="container py-4" id="dispute-centre">
    <ul class="nav nav-tabs mb-3" id="dispute-tabs">
        <li class="nav-item"><a class="nav-link active" data-status="open" href="#">Open</a></li>
        <li class="nav-item"><a class="nav-link" data-status="resolved" href="#">Resolved</a></li>
    </ul>
    <div class="list-group">
        @forelse($disputes ?? [] as $dispute)
            <a href="{{ $dispute['url'] ?? '#' }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-start">
                <div>
                    <div class="fw-semibold">{{ $dispute['contract'] }}</div>
                    <small class="text-muted">{{ $dispute['counterpart'] }} · {{ $dispute['opened'] }}</small>
                </div>
                <span class="badge bg-light text-dark">{{ $dispute['status'] }}</span>
            </a>
        @empty
            <div class="list-group-item">No disputes.</div>
        @endforelse
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/freelance/disputeCentre.js') }}"></script>
@endpush
