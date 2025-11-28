@extends('layouts.app')

@section('title', $opportunity->title ?? __('talent_ai::addons_talent_ai.volunteering.opportunities'))

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
            <h1 class="h4 mb-1">{{ $opportunity->title }}</h1>
            <p class="text-muted mb-0">{{ $opportunity->sector }} · {{ $opportunity->location }}</p>
        </div>
        <a class="btn btn-light" href="{{ url()->previous() }}">@lang('talent_ai::addons_talent_ai.common.back')</a>
    </div>

    <div class="talent-ai-grid">
        <div class="talent-ai-card">
            <h4 class="h6">Role summary</h4>
            <p>{{ $opportunity->description }}</p>
            <p class="mb-0 text-muted">Commitment: {{ $opportunity->commitment }} · Expenses: {{ $opportunity->expenses_covered ? 'Covered' : 'Not covered' }}</p>
        </div>
        <div class="talent-ai-card">
            <h4 class="h6">Status</h4>
            <form method="post" action="{{ route('addons.talent_ai.volunteering.opportunity.update', $opportunity) }}">
                @csrf
                @method('PUT')
                <select class="form-select mb-2" name="status">
                    @foreach(\Gigvora\TalentAi\Domain\Shared\Enums\VolunteeringOpportunityStatus::cases() as $status)
                        <option value="{{ $status->value }}" @selected($opportunity->status === $status)>{{ ucfirst($status->value) }}</option>
                    @endforeach
                </select>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="verified" value="1" @checked($opportunity->verified)>
                    <label class="form-check-label">Verified</label>
                </div>
                <button class="btn btn-primary" type="submit">Update</button>
            </form>
        </div>
        <div class="talent-ai-card">
            <h4 class="h6">Apply</h4>
            <form method="post" action="{{ route('addons.talent_ai.volunteering.application.store', $opportunity) }}">
                @csrf
                <div class="mb-2">
                    <label class="form-label">Motivation</label>
                    <textarea class="form-control" name="motivation" rows="3" required></textarea>
                </div>
                <button class="btn btn-primary" type="submit">@lang('talent_ai::addons_talent_ai.common.apply')</button>
            </form>
        </div>
    </div>
</div>
@endsection
