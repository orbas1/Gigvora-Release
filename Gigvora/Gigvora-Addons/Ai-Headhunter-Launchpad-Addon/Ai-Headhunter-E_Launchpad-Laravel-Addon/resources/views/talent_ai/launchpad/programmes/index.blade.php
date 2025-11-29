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
            <h1 class="h4 mb-1">@lang('talent_ai::addons_talent_ai.launchpad.programmes')</h1>
            <p class="text-muted mb-0">Discover structured programmes and track experience gained.</p>
        </div>
        <a class="btn btn-primary" href="{{ url()->current().'/create' }}">Create programme</a>
    </div>

    <div class="filter-bar">
        <input class="form-control" name="keyword" placeholder="Search by title or category">
        <select class="form-select" name="status">
            <option value="">All statuses</option>
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
                <div class="talent-ai-card">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="h6 mb-1">{{ $programme->title }}</h3>
                            <p class="mb-1 text-muted">{{ $programme->category }}</p>
                            <p class="mb-0 text-muted">{{ $programme->estimated_hours }} hrs · {{ $programme->estimated_weeks }} weeks</p>
                        </div>
                        <span class="status-pill">{{ ucfirst($programme->status?->value ?? 'draft') }}</span>
                    </div>
                    <p class="mt-2">{{ \Illuminate\Support\Str::limit($programme->description, 140) }}</p>
                    <div class="table-actions">
                        <a href="{{ url()->current().'/'.$programme->id }}" class="btn btn-outline-primary btn-sm">Details</a>
                        <form method="post" action="{{ route('addons.talent_ai.launchpad.programme.publish', $programme) }}">
                            @csrf
                            <button class="btn btn-sm btn-success" type="submit">Publish</button>
                        </form>
                        <form method="post" action="{{ route('addons.talent_ai.launchpad.programme.close', $programme) }}">
                            @csrf
                            <button class="btn btn-sm btn-light" type="submit">Close</button>
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
