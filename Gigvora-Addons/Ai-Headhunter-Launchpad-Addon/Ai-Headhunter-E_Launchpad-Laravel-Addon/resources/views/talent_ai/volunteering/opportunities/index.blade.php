@extends('layouts.app')

@section('title', __('talent_ai::addons_talent_ai.volunteering.opportunities'))

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/addons/talent_ai/talent_ai.css') }}">
@endpush

@push('scripts')
    <script src="{{ mix('js/addons/talent_ai/volunteering_filters.js') }}" defer></script>
@endpush

@section('content')
<div class="container py-4">
    <div class="talent-ai-page">
        <div class="talent-ai-header">
            <div>
                <p class="gv-eyebrow mb-1">@lang('talent_ai::addons_talent_ai.volunteering.opportunities')</p>
                <h1 class="gv-heading text-lg mb-1">{{ get_phrase('Verified volunteering opportunities') }}</h1>
                <p class="text-sm gv-muted mb-0">{{ get_phrase('Find or publish volunteering placements with clear expectations.') }}</p>
            </div>
            <a class="gv-btn gv-btn-primary" href="{{ url()->current().'/create' }}">
                <i class="fa-solid fa-plus me-1"></i>{{ get_phrase('Post opportunity') }}
            </a>
        </div>

        <div class="filter-bar" data-volunteering-filters>
            <input class="form-control" type="search" name="keyword" placeholder="{{ get_phrase('Search by title or sector') }}">
            <select class="form-select" name="sector">
                <option value="">{{ get_phrase('All sectors') }}</option>
                @foreach(($sectors ?? []) as $sector)
                    <option value="{{ $sector }}">{{ $sector }}</option>
                @endforeach
            </select>
            <select class="form-select" name="status">
                <option value="">{{ get_phrase('All statuses') }}</option>
                @foreach(\Gigvora\TalentAi\Domain\Shared\Enums\VolunteeringOpportunityStatus::cases() as $status)
                    <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
                @endforeach
            </select>
        </div>

    @if(($opportunities ?? collect())->isEmpty())
        <div class="alert-muted">@lang('talent_ai::addons_talent_ai.common.no_results')</div>
    @else
        <div class="talent-ai-grid">
            @foreach($opportunities as $opportunity)
                <div class="talent-ai-card gv-card" data-volunteering-card data-sector="{{ $opportunity->sector }}" data-status="{{ $opportunity->status?->value }}">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <h3 class="text-base fw-semibold mb-1">{{ $opportunity->title }}</h3>
                            <p class="text-sm gv-muted mb-1">{{ $opportunity->sector }} · {{ $opportunity->location }}</p>
                            <p class="text-xs gv-muted mb-0">{{ get_phrase('Commitment') }}: {{ $opportunity->commitment }}</p>
                        </div>
                        <span class="status-pill">{{ ucfirst($opportunity->status?->value ?? 'draft') }}</span>
                    </div>
                    <p class="mt-2 text-sm">{{ \Illuminate\Support\Str::limit($opportunity->description, 140) }}</p>
                    <div class="table-actions">
                        <a class="gv-btn gv-btn-ghost gv-btn-sm" href="{{ url()->current().'/'.$opportunity->id }}">{{ get_phrase('Details') }}</a>
                        <form method="post" action="{{ route('addons.talent_ai.volunteering.opportunity.publish', $opportunity) }}">
                            @csrf
                            <button class="gv-btn gv-btn-primary gv-btn-sm" type="submit">{{ get_phrase('Publish') }}</button>
                        </form>
                        <form method="post" action="{{ route('addons.talent_ai.volunteering.opportunity.close', $opportunity) }}">
                            @csrf
                            <button class="gv-btn gv-btn-ghost gv-btn-sm" type="submit">{{ get_phrase('Close') }}</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-3">{{ $opportunities->links() ?? '' }}</div>
    @endif
</div>
</div>
@endsection
