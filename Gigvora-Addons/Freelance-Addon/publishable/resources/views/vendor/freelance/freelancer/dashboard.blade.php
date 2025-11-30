@extends('freelance::layouts.freelance')

@section('title', 'Freelancer Dashboard')

@section('breadcrumbs')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Freelancer Dashboard</li>
    </ol>
</nav>
@endsection

@section('freelance-content')
<div class="space-y-4" id="freelancer-dashboard">
    @component('vendor.freelance.components.dashboard_kpi_cards', ['kpis' => $kpis ?? []])@endcomponent

    <div class="row g-3">
        <div class="col-lg-6">
            <article class="gv-card space-y-3 h-100">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="gv-eyebrow mb-1">{{ get_phrase('Open contracts') }}</p>
                        <h3 class="gv-heading text-xl mb-0">{{ get_phrase('Delivery queue') }}</h3>
                    </div>
                    <a href="{{ route('freelance.gigs.orders') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('View all') }}</a>
                </div>
                <div class="space-y-3">
                    @forelse(($contracts ?? []) as $contract)
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="mb-0 fw-semibold">{{ $contract['title'] }}</p>
                                @if (!empty($contract['link']))
                                    <a href="{{ $contract['link'] }}" class="gv-link text-sm">{{ get_phrase('Open workspace') }}</a>
                                @endif
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
                        <p class="gv-eyebrow mb-1">{{ get_phrase('Escrow overview') }}</p>
                        <h3 class="gv-heading text-xl mb-0">{{ get_phrase('Recent payouts') }}</h3>
                    </div>
                    <a href="{{ route('freelance.invoices.index') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Manage invoices') }}</a>
                </div>
                <div class="space-y-3">
                    @forelse(($escrow ?? []) as $item)
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="mb-0 fw-semibold">{{ $item['title'] }}</p>
                                <span class="gv-muted text-sm">{{ get_phrase('Escrow #:id', ['id' => $item['id']]) }}</span>
                            </div>
                            <div class="text-end">
                                <p class="mb-0 fw-semibold">
                                    {{ $item['amount'] ? (setting('_general.currency').' '.number_format($item['amount'], 2)) : get_phrase('—') }}
                                </p>
                                <span class="gv-pill gv-pill--success text-xs">{{ ucfirst($item['status'] ?? 'pending') }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="gv-muted mb-0">{{ get_phrase('No escrow activity yet.') }}</p>
                    @endforelse
                </div>
            </article>
        </div>
    </div>

    <article class="gv-card space-y-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <p class="gv-eyebrow mb-1">{{ get_phrase('Recommendations') }}</p>
                <h3 class="gv-heading text-xl mb-0">{{ get_phrase('Projects curated for you') }}</h3>
            </div>
            <a href="{{ route('freelance.projects.index') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Refresh') }}</a>
        </div>
        <div class="row g-3">
            @forelse(($recommendations ?? []) as $project)
                <div class="col-md-4">
                    <div class="gv-freelance-card h-100">
                        <div class="gv-freelance-card__eyebrow">{{ $project['type'] ?? get_phrase('Fixed') }}</div>
                        <h4 class="gv-freelance-card__title">{{ $project['title'] }}</h4>
                        <p class="gv-freelance-card__summary">{{ $project['summary'] }}</p>
                        <div class="gv-freelance-card__meta">
                            <span><i class="fa-regular fa-user"></i>{{ $project['owner'] }}</span>
                            @if (!empty($project['budget']))
                                <span><i class="fa-solid fa-coins"></i>{{ $project['budget'] }}</span>
                            @endif
                        </div>
                        <div class="gv-freelance-card__actions">
                            <a href="{{ $project['link'] }}" class="gv-btn gv-btn-primary gv-btn-sm">{{ get_phrase('Open brief') }}</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="gv-muted mb-0">{{ get_phrase('No recommendations yet. Explore projects to get matched.') }}</p>
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

@push('scripts')
<script type="module" src="{{ mix('js/freelance/freelancerDashboard.js') }}"></script>
@endpush
