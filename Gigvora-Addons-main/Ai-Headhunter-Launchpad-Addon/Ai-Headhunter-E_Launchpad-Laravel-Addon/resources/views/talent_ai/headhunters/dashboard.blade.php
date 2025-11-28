@extends('layouts.app')

@section('title', __('talent_ai::addons_talent_ai.headhunters.dashboard_title'))

@push('styles')
    @vite('resources/css/addons/talent_ai/talent_ai.css')
@endpush

@push('scripts')
    @vite('resources/js/addons/talent_ai/pipeline_board.js')
@endpush

@section('content')
<div class="container py-4">
    <div class="talent-ai-page">
        <div class="talent-ai-header">
            <div>
                <h1 class="h4 mb-1">@lang('talent_ai::addons_talent_ai.headhunters.dashboard_title')</h1>
            <p class="text-muted mb-0">Track mandates, candidates, and AI intel in one place.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ url()->previous() }}" class="btn btn-light">@lang('talent_ai::addons_talent_ai.common.back')</a>
        </div>
    </div>

    <div class="talent-ai-grid">
        <div class="talent-ai-card">
            <h4>@lang('talent_ai::addons_talent_ai.headhunters.mandates')</h4>
            <p class="display-6">{{ $mandates->count() ?? 0 }}</p>
            <p class="text-muted mb-0">Active mandates assigned to you.</p>
        </div>
        <div class="talent-ai-card">
            <h4>@lang('talent_ai::addons_talent_ai.headhunters.pipeline')</h4>
            <p class="display-6">{{ $pipelineSummary['active'] ?? 0 }}</p>
            <p class="text-muted mb-0">Active candidates across all stages.</p>
        </div>
        <div class="talent-ai-card">
            <h4>@lang('talent_ai::addons_talent_ai.headhunters.candidates')</h4>
            <p class="display-6">{{ $candidates->count() ?? 0 }}</p>
            <p class="text-muted mb-0">Profiles sourced or matched to mandates.</p>
        </div>
    </div>

    <div class="talent-ai-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="h5 mb-0">Open mandates</h3>
            <small class="text-muted">Status updates are reflected instantly.</small>
        </div>
        @if(($mandates ?? collect())->isEmpty())
            <div class="alert-muted">@lang('talent_ai::addons_talent_ai.common.no_results')</div>
        @else
            <table class="talent-ai-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Location</th>
                        <th>@lang('talent_ai::addons_talent_ai.common.status')</th>
                        <th>@lang('talent_ai::addons_talent_ai.common.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mandates as $mandate)
                        <tr>
                            <td class="fw-semibold">{{ $mandate->title }}</td>
                            <td>{{ $mandate->location }}</td>
                            <td><span class="status-pill">{{ ucfirst($mandate->status?->value ?? 'draft') }}</span></td>
                            <td class="table-actions">
                                <a class="btn btn-sm btn-outline-primary" href="{{ $mandate->id ? url()->current().'/mandates/'.$mandate->id : '#' }}">@lang('talent_ai::addons_talent_ai.common.view')</a>
                                <form method="post" action="{{ route('addons.talent_ai.headhunter.mandate.update', $mandate) }}">
                                    @csrf
                                    @method('PUT')
                                    <button class="btn btn-sm btn-light" type="submit">Refresh</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="talent-ai-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="h5 mb-0">@lang('talent_ai::addons_talent_ai.headhunters.ai_suggestions')</h3>
            <small class="text-muted">Surface high-fit candidates and outreach ideas.</small>
        </div>
        @if(empty($suggestions))
            <div class="alert-muted">No AI suggestions yet. Run an AI search to populate insights.</div>
        @else
            <div class="talent-ai-grid">
                @foreach($suggestions as $suggestion)
                    <div class="talent-ai-card">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="h6 mb-0">{{ $suggestion['title'] ?? 'Candidate' }}</h4>
                            <span class="talent-ai-badge">{{ $suggestion['score'] ?? 'New' }}</span>
                        </div>
                        <p class="mb-2 text-muted">{{ $suggestion['summary'] ?? '' }}</p>
                        @if(!empty($suggestion['actions']))
                            <div class="table-actions">
                                @foreach($suggestion['actions'] as $action)
                                    <a href="{{ $action['url'] ?? '#' }}" class="btn btn-sm btn-primary">{{ $action['label'] ?? 'Open' }}</a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
        </div>
    </div>
</div>
@endsection
