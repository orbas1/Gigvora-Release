@extends('layouts.app')

@section('title', 'Application details')

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/addons/talent_ai/talent_ai.css') }}">
@endpush

@push('scripts')
    <script src="{{ mix('js/addons/talent_ai/talent_ai.js') }}" defer></script>
@endpush

@section('content')
<div class="talent-ai-page">
    <div class="talent-ai-header">
        <div>
            <h1 class="h4 mb-1">{{ $application->programme?->title }}</h1>
            <p class="text-muted mb-0">Applicant: {{ $application->user?->name }} · {{ ucfirst($application->status?->value ?? 'pending') }}</p>
        </div>
        <a class="btn btn-light" href="{{ url()->previous() }}">@lang('talent_ai::addons_talent_ai.common.back')</a>
    </div>

    <div class="talent-ai-grid">
        <div class="talent-ai-card">
            <h4 class="h6">Motivation</h4>
            <p>{{ $application->motivation }}</p>
            <p class="mb-0 text-muted">Hours gained: {{ $application->hours_gained ?? 0 }} · Weeks: {{ $application->weeks_gained ?? 0 }}</p>
        </div>
        <div class="talent-ai-card">
            <h4 class="h6">Status & outcomes</h4>
            <form method="post" action="{{ route('addons.talent_ai.launchpad.application.status', $application) }}">
                @csrf
                <div class="mb-2">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status">
                        @foreach(\Gigvora\TalentAi\Domain\Shared\Enums\LaunchpadApplicationStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected($application->status === $status)>{{ ucfirst($status->value) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="reference_issued" value="1" @checked($application->reference_issued)>
                    <label class="form-check-label">Reference issued</label>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="qualification_issued" value="1" @checked($application->qualification_issued)>
                    <label class="form-check-label">Qualification issued</label>
                </div>
                <button class="btn btn-primary" type="submit">Save</button>
            </form>
        </div>
        <div class="talent-ai-card">
            <h4 class="h6">Interviews</h4>
            @if(($application->interviews ?? collect())->isEmpty())
                <p class="text-muted mb-0">No interviews scheduled yet.</p>
            @else
                <ul class="mb-0">
                    @foreach($application->interviews as $interview)
                        <li class="mb-2">{{ $interview->scheduled_at?->toDayDateTimeString() }} · {{ $interview->status }}<br><span class="text-muted">{{ $interview->summary }}</span></li>
                    @endforeach
                </ul>
            @endif
            <form class="mt-2" method="post" action="{{ route('addons.talent_ai.launchpad.interview.store', $application) }}">
                @csrf
                <div class="mb-2">
                    <label class="form-label">Schedule interview</label>
                    <input class="form-control" type="datetime-local" name="scheduled_at" required>
                </div>
                <textarea class="form-control mb-2" name="summary" rows="2" placeholder="Agenda"></textarea>
                <button class="btn btn-outline-primary btn-sm" type="submit">Schedule</button>
            </form>
        </div>
    </div>
</div>
@endsection
