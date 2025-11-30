@extends('layouts.app')

@section('title', __('talent_ai::addons_talent_ai.launchpad.programmes'))

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/addons/talent_ai/talent_ai.css') }}">
@endpush

@push('scripts')
    <script src="{{ mix('js/addons/talent_ai/launchpad_progress.js') }}" defer></script>
@endpush

@section('content')
<div class="container py-4">
    <div class="talent-ai-page">
        <div class="talent-ai-header">
            <div>
                <p class="gv-eyebrow mb-1">@lang('talent_ai::addons_talent_ai.launchpad.programmes')</p>
                <h1 class="gv-heading text-lg mb-1">{{ get_phrase('Experience Launchpad catalog') }}</h1>
                <p class="text-sm gv-muted mb-0">{{ get_phrase('Discover structured programmes and track experience gained.') }}</p>
            </div>
            <a class="gv-btn gv-btn-primary" href="{{ url()->current().'/create' }}">
                <i class="fa-solid fa-plus me-1"></i>{{ get_phrase('Create programme') }}
            </a>
        </div>

        <div class="filter-bar">
            <input class="form-control" name="keyword" placeholder="{{ get_phrase('Search by title or category') }}">
            <select class="form-select" name="status">
                <option value="">{{ get_phrase('All statuses') }}</option>
                @foreach(\Gigvora\TalentAi\Domain\Shared\Enums\LaunchpadProgrammeStatus::cases() as $status)
                    <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
                @endforeach
            </select>
        </div>

    @if(($programmes ?? collect())->isEmpty())
        <div class="alert-muted">@lang('talent_ai::addons_talent_ai.common.no_results')</div>
    @else
        <div class="talent-ai-grid">
            @foreach($programmes as $programme)
                <div class="talent-ai-card gv-card">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <h3 class="text-base fw-semibold mb-1">{{ $programme->title }}</h3>
                            <p class="text-sm gv-muted mb-1">{{ $programme->category }}</p>
                            <p class="text-xs gv-muted mb-0">{{ $programme->estimated_hours }} {{ get_phrase('hrs') }} · {{ $programme->estimated_weeks }} {{ get_phrase('weeks') }}</p>
                        </div>
                        <span class="status-pill">{{ ucfirst($programme->status?->value ?? 'draft') }}</span>
                    </div>
                    <p class="mt-2 text-sm">{{ \Illuminate\Support\Str::limit($programme->description, 140) }}</p>
                    <div class="table-actions">
                        <a href="{{ url()->current().'/'.$programme->id }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Details') }}</a>
                        <form method="post" action="{{ route('addons.talent_ai.launchpad.programme.publish', $programme) }}">
                            @csrf
                            <button class="gv-btn gv-btn-primary gv-btn-sm" type="submit">{{ get_phrase('Publish') }}</button>
                        </form>
                        <form method="post" action="{{ route('addons.talent_ai.launchpad.programme.close', $programme) }}">
                            @csrf
                            <button class="gv-btn gv-btn-ghost gv-btn-sm" type="submit">{{ get_phrase('Close') }}</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-3">{{ $programmes->links() ?? '' }}</div>
    @endif
</div>
</div>
@endsection
