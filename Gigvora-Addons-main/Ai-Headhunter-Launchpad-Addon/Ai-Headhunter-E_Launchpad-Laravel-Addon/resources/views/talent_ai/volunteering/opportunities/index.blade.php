@extends('layouts.app')

@section('title', __('talent_ai::addons_talent_ai.volunteering.opportunities'))

@push('styles')
    @vite('resources/css/addons/talent_ai/talent_ai.css')
@endpush

@push('scripts')
    @vite('resources/js/addons/talent_ai/talent_ai.js')
@endpush

@section('content')
<div class="talent-ai-page">
    <div class="talent-ai-header">
        <div>
            <h1 class="h4 mb-1">@lang('talent_ai::addons_talent_ai.volunteering.opportunities')</h1>
            <p class="text-muted mb-0">Find or publish volunteering placements with clear expectations.</p>
        </div>
        <a class="btn btn-primary" href="{{ url()->current().'/create' }}">Post opportunity</a>
    </div>

    <div class="filter-bar" data-volunteering-filters>
        <input class="form-control" type="search" name="keyword" placeholder="Search by title or sector">
        <select class="form-select" name="sector">
            <option value="">All sectors</option>
            @foreach(($sectors ?? []) as $sector)
                <option value="{{ $sector }}">{{ $sector }}</option>
            @endforeach
        </select>
        <select class="form-select" name="status">
            <option value="">All statuses</option>
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
                <div class="talent-ai-card" data-volunteering-card data-sector="{{ $opportunity->sector }}" data-status="{{ $opportunity->status?->value }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="h6 mb-1">{{ $opportunity->title }}</h3>
                            <p class="text-muted mb-1">{{ $opportunity->sector }} · {{ $opportunity->location }}</p>
                            <p class="text-muted mb-0">Commitment: {{ $opportunity->commitment }}</p>
                        </div>
                        <span class="status-pill">{{ ucfirst($opportunity->status?->value ?? 'draft') }}</span>
                    </div>
                    <p class="mt-2">{{ \Illuminate\Support\Str::limit($opportunity->description, 140) }}</p>
                    <div class="table-actions">
                        <a class="btn btn-outline-primary btn-sm" href="{{ url()->current().'/'.$opportunity->id }}">Details</a>
                        <form method="post" action="{{ route('addons.talent_ai.volunteering.opportunity.publish', $opportunity) }}">
                            @csrf
                            <button class="btn btn-sm btn-success" type="submit">Publish</button>
                        </form>
                        <form method="post" action="{{ route('addons.talent_ai.volunteering.opportunity.close', $opportunity) }}">
                            @csrf
                            <button class="btn btn-sm btn-light" type="submit">Close</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-3">{{ $opportunities->links() ?? '' }}</div>
    @endif
</div>
@endsection
