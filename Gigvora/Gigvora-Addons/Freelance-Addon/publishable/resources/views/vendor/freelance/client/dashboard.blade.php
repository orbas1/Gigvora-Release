@extends('layouts.app')

@section('title', 'Client Dashboard')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Client Dashboard</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="client-dashboard">
    @component('vendor.freelance.components.dashboard_kpi_cards', ['kpis' => $kpis ?? []])
    @endcomponent

    <div class="row mt-4 g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Active Contracts</span>
                    <a href="/freelance/client/contracts" class="small">View all</a>
                </div>
                <div class="card-body">
                    @forelse($contracts ?? [] as $contract)
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <div class="fw-semibold">{{ $contract['title'] }}</div>
                                <small class="text-muted">{{ $contract['freelancer'] }}</small>
                            </div>
                            <span class="badge bg-light text-dark">{{ $contract['status'] }}</span>
                        </div>
                    @empty
                        <p class="text-muted">No active contracts.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Open disputes</span>
                    <a href="/freelance/disputes" class="small">Open centre</a>
                </div>
                <div class="card-body">
                    @forelse($disputes ?? [] as $dispute)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <div class="fw-semibold">{{ $dispute['contract'] }}</div>
                                <small class="text-muted">{{ $dispute['counterpart'] }}</small>
                            </div>
                            <span class="badge bg-warning text-dark">{{ $dispute['status'] }}</span>
                        </div>
                    @empty
                        <p class="text-muted">No disputes.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Recommended Freelancers</span>
            <a href="/search/freelancers" class="small">Search</a>
        </div>
        <div class="card-body">
            @forelse($freelancers ?? [] as $user)
                @component('vendor.freelance.components.user_badge', ['user' => $user])@endcomponent
                <hr>
            @empty
                <p class="text-muted">No recommendations yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
