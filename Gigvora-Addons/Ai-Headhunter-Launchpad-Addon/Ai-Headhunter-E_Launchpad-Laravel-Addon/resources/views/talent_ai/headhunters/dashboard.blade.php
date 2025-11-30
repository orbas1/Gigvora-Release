@extends('layouts.app')

@section('title', __('talent_ai::addons_talent_ai.headhunters.dashboard_title'))

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
                <p class="gv-eyebrow mb-1">@lang('talent_ai::addons_talent_ai.headhunters.dashboard_title')</p>
                <h1 class="gv-heading text-lg mb-1">{{ get_phrase('Mandates & pipelines overview') }}</h1>
                <p class="text-sm gv-muted mb-0">{{ get_phrase('Track mandates, candidates, and AI insights from one workspace.') }}</p>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('addons.talent_ai.ai_workspace.index') }}" class="gv-btn gv-btn-primary">
                    <i class="fa-solid fa-robot me-1"></i>{{ get_phrase('AI workspace') }}
                </a>
                <a href="{{ url()->previous() }}" class="gv-btn gv-btn-ghost">
                    <i class="fa-solid fa-arrow-left me-1"></i>@lang('talent_ai::addons_talent_ai.common.back')
                </a>
            </div>
        </div>

        <div class="talent-ai-grid">
            <div class="talent-ai-card gv-card">
                <p class="text-sm gv-muted mb-1">@lang('talent_ai::addons_talent_ai.headhunters.mandates')</p>
                <p class="display-6 mb-0">{{ $mandates->count() ?? 0 }}</p>
                <p class="text-sm gv-muted mb-0">{{ get_phrase('Active mandates assigned to you.') }}</p>
            </div>
            <div class="talent-ai-card gv-card">
                <p class="text-sm gv-muted mb-1">@lang('talent_ai::addons_talent_ai.headhunters.pipeline')</p>
                <p class="display-6 mb-0">{{ $pipelineSummary['active'] ?? 0 }}</p>
                <p class="text-sm gv-muted mb-0">{{ get_phrase('Active candidates across all stages.') }}</p>
            </div>
            <div class="talent-ai-card gv-card">
                <p class="text-sm gv-muted mb-1">@lang('talent_ai::addons_talent_ai.headhunters.candidates')</p>
                <p class="display-6 mb-0">{{ $candidates->count() ?? 0 }}</p>
                <p class="text-sm gv-muted mb-0">{{ get_phrase('Profiles sourced or matched to mandates.') }}</p>
            </div>
        </div>

        <div class="talent-ai-card gv-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="gv-heading text-base mb-0">{{ get_phrase('Open mandates') }}</h3>
                <small class="text-sm gv-muted">{{ get_phrase('Status updates reflect instantly across utilities.') }}</small>
            </div>
            @if(($mandates ?? collect())->isEmpty())
                <div class="alert-muted">@lang('talent_ai::addons_talent_ai.common.no_results')</div>
            @else
                <table class="talent-ai-table">
                    <thead>
                        <tr>
                            <th>{{ get_phrase('Title') }}</th>
                            <th>{{ get_phrase('Location') }}</th>
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
                                    <a class="gv-btn gv-btn-ghost gv-btn-sm" href="{{ $mandate->id ? url()->current().'/mandates/'.$mandate->id : '#' }}">@lang('talent_ai::addons_talent_ai.common.view')</a>
                                    <form method="post" action="{{ route('addons.talent_ai.headhunter.mandate.update', $mandate) }}">
                                        @csrf
                                        @method('PUT')
                                        <button class="gv-btn gv-btn-primary gv-btn-sm" type="submit">{{ get_phrase('Refresh') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="talent-ai-card gv-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="gv-heading text-base mb-0">@lang('talent_ai::addons_talent_ai.headhunters.ai_suggestions')</h3>
                <small class="text-sm gv-muted">{{ get_phrase('Surface high-fit candidates and outreach ideas.') }}</small>
            </div>
            @if(empty($suggestions))
                <div class="alert-muted">{{ get_phrase('No AI suggestions yet. Run a search to populate insights.') }}</div>
            @else
                <div class="talent-ai-grid">
                    @foreach($suggestions as $suggestion)
                        <div class="talent-ai-card gv-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="text-sm fw-semibold mb-0">{{ $suggestion['title'] ?? 'Candidate' }}</h4>
                                <span class="talent-ai-badge">{{ $suggestion['score'] ?? 'New' }}</span>
                            </div>
                            <p class="mb-2 text-sm gv-muted">{{ $suggestion['summary'] ?? '' }}</p>
                            @if(!empty($suggestion['actions']))
                                <div class="table-actions">
                                    @foreach($suggestion['actions'] as $action)
                                        <a href="{{ $action['url'] ?? '#' }}" class="gv-btn gv-btn-primary gv-btn-sm">{{ $action['label'] ?? 'Open' }}</a>
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
