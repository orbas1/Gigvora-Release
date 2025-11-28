@extends('layouts.app')

@section('title', $mandate->title ?? __('talent_ai::addons_talent_ai.headhunters.mandates'))

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
            <h1 class="h4 mb-1">{{ $mandate->title }}</h1>
            <p class="text-muted mb-0">{{ $mandate->location }} · {{ ucfirst($mandate->status?->value ?? 'draft') }}</p>
        </div>
        <a class="btn btn-light" href="{{ url()->previous() }}">@lang('talent_ai::addons_talent_ai.common.back')</a>
    </div>

    <div class="talent-ai-grid">
        <div class="talent-ai-card">
            <h4 class="h6">Commercials</h4>
            <p class="mb-1">Fee model: {{ $mandate->fee_model ?? 'N/A' }}</p>
            <p class="mb-0">Fee amount: {{ $mandate->fee_amount ? '$'.$mandate->fee_amount : 'Not set' }}</p>
        </div>
        <div class="talent-ai-card">
            <h4 class="h6">Requirements</h4>
            @if(!empty($mandate->requirements))
                <ul class="mb-0">
                    @foreach($mandate->requirements as $req)
                        <li>{{ $req }}</li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted mb-0">No requirements captured.</p>
            @endif
        </div>
        <div class="talent-ai-card">
            <h4 class="h6">Actions</h4>
            <form method="post" action="{{ route('addons.talent_ai.headhunter.mandate.update', $mandate) }}">
                @csrf
                @method('PUT')
                <div class="mb-2">
                    <label class="form-label">Update status</label>
                    <select class="form-select" name="status">
                        @foreach(\Gigvora\TalentAi\Domain\Shared\Enums\HeadhunterMandateStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected($mandate->status === $status)>{{ ucfirst($status->value) }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary" type="submit">Save</button>
            </form>
        </div>
    </div>

    <div class="talent-ai-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="h5 mb-0">Pipeline overview</h3>
            <a href="{{ url()->current().'/pipeline' }}" class="btn btn-outline-primary btn-sm">Open board</a>
        </div>
        <div class="talent-ai-grid">
            @foreach(($pipeline ?? []) as $stage => $items)
                <div class="talent-ai-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>{{ ucfirst($stage) }}</strong>
                        <span class="talent-ai-badge">{{ count($items) }}</span>
                    </div>
                    <ul class="mt-2 mb-0">
                        @forelse($items as $item)
                            <li class="d-flex justify-content-between">{{ $item->candidate?->name ?? 'Candidate' }}<span class="text-muted">{{ $item->moved_at?->diffForHumans() }}</span></li>
                        @empty
                            <li class="text-muted">No candidates in this stage.</li>
                        @endforelse
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
