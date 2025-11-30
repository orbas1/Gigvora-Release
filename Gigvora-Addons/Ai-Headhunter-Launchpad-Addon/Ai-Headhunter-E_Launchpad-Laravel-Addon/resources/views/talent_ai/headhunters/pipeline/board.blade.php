@extends('layouts.app')

@section('title', 'Pipeline Board')

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/addons/talent_ai/talent_ai.css') }}">
@endpush

@push('scripts')
    <script src="{{ mix('js/addons/talent_ai/pipeline_board.js') }}" defer></script>
@endpush

@section('content')
<div class="container py-4">
    <div class="talent-ai-page">
        <div class="talent-ai-header">
            <div>
                <p class="gv-eyebrow mb-1">{{ get_phrase('Pipeline') }}</p>
                <h1 class="gv-heading text-lg mb-1">{{ $mandate->title ?? get_phrase('Untitled mandate') }}</h1>
                <p class="text-sm gv-muted mb-0">{{ get_phrase('Drag candidates between stages and keep notes synced.') }}</p>
            </div>
            <a href="{{ url()->previous() }}" class="gv-btn gv-btn-ghost">
                <i class="fa-solid fa-arrow-left me-1"></i>@lang('talent_ai::addons_talent_ai.common.back')
            </a>
        </div>

    <div data-pipeline-feedback class="mb-3"></div>

    <div class="pipeline-board">
        @foreach($stages ?? \Gigvora\TalentAi\Domain\Shared\Enums\HeadhunterPipelineStage::cases() as $stage)
            @php
                $items = collect($pipeline[$stage->value] ?? []);
            @endphp
            <div class="pipeline-stage" data-stage="{{ $stage->value }}">
                <div class="pipeline-stage-header">
                    <strong>{{ ucfirst($stage->value) }}</strong>
                    <span class="talent-ai-badge">{{ $items->count() }}</span>
                </div>
                <div class="pipeline-items">
                    @forelse($items as $item)
                        <div class="pipeline-card" data-move-url="{{ route('addons.talent_ai.headhunter.pipeline.move', $item) }}" data-stage="{{ $stage->value }}" data-notes="{{ $item->notes }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $item->candidate?->name ?? 'Candidate' }}</div>
                                    <div class="text-muted small">{{ $item->candidate?->email }}</div>
                                </div>
                                <span class="status-pill" data-stage-label>{{ ucfirst($stage->value) }}</span>
                            </div>
                            <p class="mb-1" data-note-preview>{{ $item->notes ?? 'No notes yet' }}</p>
                            <div class="small text-muted">Moved {{ $item->moved_at?->diffForHumans() ?? 'recently' }}</div>
                        </div>
                    @empty
                        <div class="alert-muted">No candidates in this stage.</div>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>
</div>
@endsection
