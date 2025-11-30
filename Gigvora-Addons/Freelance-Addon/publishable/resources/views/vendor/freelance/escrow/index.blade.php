@extends('freelance::layouts.freelance')

@section('title', 'Escrow Overview')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Escrow</li>
    </ol>
</nav>
@endsection

@section('freelance-content')
<div class="container py-4" id="escrow-overview">
    @foreach(['Awaiting Funding' => $awaiting ?? [], 'Active Escrows' => $active ?? [], 'Completed' => $completed ?? []] as $title => $items)
        <div class="card mb-3">
            <div class="card-header">{{ $title }}</div>
            <div class="card-body">
                @forelse($items as $escrow)
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <div class="fw-semibold">{{ $escrow['title'] }}</div>
                            <small class="text-muted">{{ $escrow['counterpart'] }}</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold">{{ $escrow['amount'] }}</div>
                            <span class="badge bg-light text-dark">{{ $escrow['status'] }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No records.</p>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
@endsection
