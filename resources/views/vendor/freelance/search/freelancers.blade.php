@extends('freelance::layouts.freelance')

@section('freelance-content')
<div class="gv-main">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <p class="gv-pill-page-label">
                <span class="gv-pill-page-label-dot"></span>
                {{ __('Freelancers') }}
            </p>
            <h1 class="gv-main-heading">{{ __('Discover talent') }}</h1>
            <p class="gv-main-heading-sub">{{ __('Handpicked freelancers based on your filters.') }}</p>
        </div>
    </div>

    <form method="GET" class="gv-section mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="gv-label" for="search">{{ __('Keyword') }}</label>
                <input type="text" class="gv-input" name="search" id="search" value="{{ request('search') }}" placeholder="{{ __('Search by name or skill') }}">
            </div>
            <div class="col-md-3">
                <button class="gv-btn gv-btn-primary w-100" type="submit">{{ __('Filter') }}</button>
            </div>
        </div>
    </form>

    <div class="gv-card">
        @forelse($sellers as $seller)
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1">{{ $seller->full_name }}</h5>
                    <p class="mb-0 gv-muted">{{ $seller->tagline ?? __('No headline yet') }}</p>
                </div>
                <a href="{{ route('freelance.sellers.profile', $seller->slug) }}" class="gv-btn gv-btn-ghost">{{ __('View profile') }}</a>
            </div>
            @if (! $loop->last)
                <hr class="gv-divider">
            @endif
        @empty
            <p class="mb-0 gv-muted">{{ __('No freelancers matched your criteria.') }}</p>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $sellers->links('vendor.pagination.bootstrap-4') }}
    </div>
</div>
@endsection

