@extends('layouts.app')

@section('title', 'Volunteer application')

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
            <h1 class="h4 mb-1">{{ $application->opportunity?->title }}</h1>
            <p class="text-muted mb-0">Volunteer: {{ $application->user?->name }} · {{ ucfirst($application->status?->value ?? 'pending') }}</p>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-light">@lang('talent_ai::addons_talent_ai.common.back')</a>
    </div>

    <div class="talent-ai-grid">
        <div class="talent-ai-card">
            <h4 class="h6">Motivation</h4>
            <p>{{ $application->motivation }}</p>
            <p class="text-muted mb-0">Hours contributed: {{ $application->hours_contributed ?? 0 }}</p>
        </div>
        <div class="talent-ai-card">
            <h4 class="h6">Status</h4>
            <form method="post" action="{{ route('addons.talent_ai.volunteering.application.status', $application) }}">
                @csrf
                <select class="form-select mb-2" name="status">
                    @foreach(\Gigvora\TalentAi\Domain\Shared\Enums\VolunteeringApplicationStatus::cases() as $status)
                        <option value="{{ $status->value }}" @selected($application->status === $status)>{{ ucfirst($status->value) }}</option>
                    @endforeach
                </select>
                <div class="mb-2">
                    <label class="form-label">Hours contributed</label>
                    <input class="form-control" type="number" name="hours_contributed" value="{{ $application->hours_contributed ?? 0 }}">
                </div>
                <button class="btn btn-primary" type="submit">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
