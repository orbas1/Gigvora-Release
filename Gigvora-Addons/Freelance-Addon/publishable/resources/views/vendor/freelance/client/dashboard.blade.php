@extends('freelance::layouts.freelance')

@section('title', 'Client Dashboard')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Client Dashboard</li>
    </ol>
</nav>
@endsection

@section('freelance-content')
<div class="space-y-4" id="client-dashboard">
    @component('vendor.freelance.components.dashboard_kpi_cards', ['kpis' => $kpis ?? []])@endcomponent

    <div class="row g-3">
        <div class="col-lg-6">
            <article class="gv-card space-y-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="gv-eyebrow mb-1">{{ get_phrase('Active contracts') }}</p>
                        <h3 class="gv-heading text-xl mb-0">{{ get_phrase('Work in progress') }}</h3>
                    </div>
                    <a href="{{ route('freelance.client.contracts') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('View all') }}</a>
                </div>
                <div class="space-y-3">
                    @forelse(($contracts ?? []) as $contract)
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-0 fw-semibold">{{ $contract['title'] }}</p>
                            </div>
                            <span class="gv-pill">{{ $contract['status'] }}</span>
                        </div>
                    @empty
                        <p class="gv-muted mb-0">{{ get_phrase('No active contracts yet.') }}</p>
                    @endforelse
                </div>
            </article>
        </div>
        <div class="col-lg-6">
            <article class="gv-card space-y-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="gv-eyebrow mb-1">{{ get_phrase('Open disputes') }}</p>
                        <h3 class="gv-heading text-xl mb-0">{{ get_phrase('Resolution centre') }}</h3>
                    </div>
                    <a href="{{ route('freelance.disputes.index') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Open centre') }}</a>
                </div>
                <div class="space-y-3">
                    @forelse(($disputes ?? []) as $dispute)
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="mb-0 fw-semibold">{{ $dispute['contract'] }}</p>
                                <span class="gv-muted text-sm">{{ $dispute['counterpart'] }}</span>
                            </div>
                            <span class="gv-pill gv-pill--warning">{{ $dispute['status'] }}</span>
                        </div>
                    @empty
                        <p class="gv-muted mb-0">{{ get_phrase('No disputes at the moment.') }}</p>
                    @endforelse
                </div>
            </article>
        </div>
    </div>

    <article class="gv-card space-y-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <p class="gv-eyebrow mb-1">{{ get_phrase('Freelancer suggestions') }}</p>
                <h3 class="gv-heading text-xl mb-0">{{ get_phrase('Talent you may like') }}</h3>
            </div>
            <a href="{{ route('freelance.dashboard') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Open workspace') }}</a>
        </div>
        <div class="space-y-3">
            @forelse(($freelancers ?? []) as $user)
                <div class="gv-freelance-card gv-freelance-card--compact">
                    <div>
                        <p class="mb-0 fw-semibold">{{ $user['name'] }}</p>
                        <span class="gv-muted text-sm">{{ $user['tagline'] }}</span>
                    </div>
                    <a href="{{ $user['link'] }}" class="gv-btn gv-btn-outline">{{ get_phrase('View profile') }}</a>
                </div>
            @empty
                <p class="gv-muted mb-0">{{ get_phrase('No recommendations yet. Continue exploring projects.') }}</p>
            @endforelse
        </div>
    </article>

    @if(!empty($ads))
        <article class="gv-card">
            <p class="gv-eyebrow mb-2">{{ get_phrase('Sponsored') }}</p>
            @include('advertisement::components.ad_banner', ['ad' => $ads])
        </article>
    @endif
</div>
@endsection
