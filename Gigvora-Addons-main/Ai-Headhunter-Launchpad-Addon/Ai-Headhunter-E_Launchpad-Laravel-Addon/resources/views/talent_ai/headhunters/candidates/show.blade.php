@extends('layouts.app')

@section('title', $candidate->name ?? 'Candidate')

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
            <h1 class="h4 mb-1">{{ $candidate->name }}</h1>
            <p class="text-muted mb-0">{{ $candidate->email }} · {{ $candidate->phone }}</p>
        </div>
        <a class="btn btn-light" href="{{ url()->previous() }}">@lang('talent_ai::addons_talent_ai.common.back')</a>
    </div>

    <div class="talent-ai-grid">
        <div class="talent-ai-card">
            <h4 class="h6">Skills</h4>
            @if(!empty($candidate->skills))
                <div class="d-flex flex-wrap gap-2">
                    @foreach($candidate->skills as $skill)
                        <span class="talent-ai-badge">{{ $skill }}</span>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">No skills captured yet.</p>
            @endif
        </div>
        <div class="talent-ai-card">
            <h4 class="h6">Experience</h4>
            @if(!empty($candidate->experience))
                <ul class="mb-0">
                    @foreach($candidate->experience as $exp)
                        <li>{{ $exp['role'] ?? 'Role' }} at {{ $exp['company'] ?? 'Company' }} ({{ $exp['years'] ?? '?' }} yrs)</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted mb-0">Add experience highlights to strengthen the profile.</p>
            @endif
        </div>
        <div class="talent-ai-card">
            <h4 class="h6">Notes</h4>
            <p data-note-preview>{{ $candidate->notes ?? 'No notes yet.' }}</p>
            <form method="post" action="{{ route('addons.talent_ai.headhunter.candidate.update', $candidate) }}">
                @csrf
                @method('PUT')
                <textarea class="form-control mb-2" name="notes" rows="3" placeholder="Add screening notes">{{ old('notes', $candidate->notes ?? '') }}</textarea>
                <button class="btn btn-primary" type="submit">Save notes</button>
            </form>
        </div>
    </div>

    <div class="talent-ai-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="h5 mb-0">Pipeline placements</h3>
            <span class="talent-ai-badge">{{ $candidate->pipelineItems->count() ?? 0 }}</span>
        </div>
        <div class="talent-ai-grid">
            @forelse($candidate->pipelineItems as $item)
                <div class="talent-ai-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $item->mandate?->title ?? 'Mandate' }}</div>
                            <div class="text-muted small">{{ $item->mandate?->location }}</div>
                        </div>
                        <span class="status-pill">{{ ucfirst($item->stage?->value ?? 'sourced') }}</span>
                    </div>
                    <p class="mb-1">{{ $item->notes ?? 'No notes yet' }}</p>
                    <div class="small text-muted">Updated {{ $item->moved_at?->diffForHumans() ?? 'recently' }}</div>
                </div>
            @empty
                <div class="alert-muted">This candidate is not assigned to any mandate yet.</div>
            @endforelse
        </div>
    </div>

    <div class="talent-ai-card">
        <h3 class="h5 mb-2">@lang('talent_ai::addons_talent_ai.headhunters.ai_suggestions')</h3>
        <p class="text-muted">AI-generated positioning ideas, outreach snippets, and role matches.</p>
        @if(empty($aiSuggestions))
            <div class="alert-muted">No AI suggestions yet.</div>
        @else
            <ul class="mb-0">
                @foreach($aiSuggestions as $suggestion)
                    <li class="mb-2">
                        <strong>{{ $suggestion['title'] ?? 'Insight' }}:</strong>
                        <span>{{ $suggestion['body'] ?? '' }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
