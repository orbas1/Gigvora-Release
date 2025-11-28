@extends('layouts.app')

@section('title', $programme->title ?? __('talent_ai::addons_talent_ai.launchpad.programmes'))

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
            <h1 class="h4 mb-1">{{ $programme->title }}</h1>
            <p class="text-muted mb-0">{{ $programme->category }} · {{ ucfirst($programme->status?->value ?? 'draft') }}</p>
        </div>
        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="{{ url()->current().'/edit' }}">Edit</a>
            <form method="post" action="{{ route('addons.talent_ai.launchpad.programme.publish', $programme) }}">
                @csrf
                <button class="btn btn-primary" type="submit">Publish</button>
            </form>
        </div>
    </div>

    <div class="talent-ai-grid">
        <div class="talent-ai-card">
            <h4 class="h6">About this programme</h4>
            <p>{{ $programme->description }}</p>
            <ul class="mb-0 list-unstyled">
                <li><strong>Duration:</strong> {{ $programme->estimated_weeks }} weeks · {{ $programme->estimated_hours }} hours</li>
                <li><strong>Reference offered:</strong> {{ $programme->reference_offered ? 'Yes' : 'No' }}</li>
                <li><strong>Qualification:</strong> {{ $programme->qualification_offered ? 'Yes' : 'No' }}</li>
                <li><strong>Pay reduction:</strong> {{ $programme->pay_reduction_percentage ?? 0 }}%</li>
            </ul>
        </div>
        <div class="talent-ai-card" data-launchpad-progress>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h4 class="h6 mb-0">@lang('talent_ai::addons_talent_ai.launchpad.tasks')</h4>
                <span data-progress-label class="text-muted small"></span>
            </div>
            <div class="progress-bar mb-2"><div class="progress" data-progress-bar></div></div>
            <ul class="list-unstyled mb-0">
                @foreach($programme->tasks ?? [] as $task)
                    <li class="mb-2 d-flex align-items-start gap-2">
                        <input class="form-check-input launchpad-task-checkbox" type="checkbox" value="{{ $task->id }}" data-update-url="{{ $progressUpdateUrl ?? '' }}">
                        <div>
                            <div class="fw-semibold">{{ $task->title }}</div>
                            <div class="text-muted small">{{ $task->description }}</div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="talent-ai-card">
            <h4 class="h6">Apply to this programme</h4>
            <form method="post" action="{{ route('addons.talent_ai.launchpad.application.store', $programme) }}">
                @csrf
                <div class="mb-2">
                    <label class="form-label">Why are you applying?</label>
                    <textarea name="motivation" class="form-control" rows="3" required></textarea>
                </div>
                <button class="btn btn-primary" type="submit">@lang('talent_ai::addons_talent_ai.common.apply')</button>
            </form>
        </div>
    </div>
</div>
@endsection
