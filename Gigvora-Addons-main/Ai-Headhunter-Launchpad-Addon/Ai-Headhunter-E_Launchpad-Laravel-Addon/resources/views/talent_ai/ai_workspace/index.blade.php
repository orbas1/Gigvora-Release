@extends('layouts.app')

@section('title', __('talent_ai::addons_talent_ai.ai_workspace.title'))

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
            <h1 class="h4 mb-1">@lang('talent_ai::addons_talent_ai.ai_workspace.title')</h1>
            <p class="text-muted mb-0">Run curated AI tools for profiles, outreach, marketing, and more.</p>
        </div>
        <a class="btn btn-light" href="{{ url()->previous() }}">@lang('talent_ai::addons_talent_ai.common.back')</a>
    </div>

    <div class="ai-workspace-grid">
        @php
            $tools = [
                ['key' => 'cv_writer', 'title' => __('talent_ai::addons_talent_ai.ai_workspace.cv_writer'), 'endpoint' => route('api.addons.talent_ai.ai.cv_writer')],
                ['key' => 'outreach', 'title' => __('talent_ai::addons_talent_ai.ai_workspace.outreach'), 'endpoint' => route('api.addons.talent_ai.ai.outreach')],
                ['key' => 'calendar', 'title' => __('talent_ai::addons_talent_ai.ai_workspace.calendar'), 'endpoint' => route('api.addons.talent_ai.ai.social_calendar')],
                ['key' => 'coach', 'title' => __('talent_ai::addons_talent_ai.ai_workspace.coach'), 'endpoint' => route('api.addons.talent_ai.ai.coach')],
                ['key' => 'repurpose', 'title' => __('talent_ai::addons_talent_ai.ai_workspace.repurpose'), 'endpoint' => route('api.addons.talent_ai.ai.repurpose')],
                ['key' => 'interview', 'title' => __('talent_ai::addons_talent_ai.ai_workspace.interview'), 'endpoint' => route('api.addons.talent_ai.ai.interview_prep')],
                ['key' => 'images', 'title' => __('talent_ai::addons_talent_ai.ai_workspace.images'), 'endpoint' => route('api.addons.talent_ai.ai.image_canvas')],
                ['key' => 'writer', 'title' => __('talent_ai::addons_talent_ai.ai_workspace.writer'), 'endpoint' => route('api.addons.talent_ai.ai.writer')],
                ['key' => 'video', 'title' => __('talent_ai::addons_talent_ai.ai_workspace.video'), 'endpoint' => route('api.addons.talent_ai.ai.writer')],
                ['key' => 'marketing', 'title' => __('talent_ai::addons_talent_ai.ai_workspace.marketing'), 'endpoint' => route('api.addons.talent_ai.ai.marketing_bot')],
            ];
        @endphp

        @foreach($tools as $tool)
            <div class="ai-tool-card">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="h6 mb-0">{{ $tool['title'] }}</h3>
                    <span class="status-pill">AI</span>
                </div>
                <form class="ai-tool-form" data-endpoint="{{ $tool['endpoint'] }}">
                    <textarea name="prompt" class="form-control mb-2" rows="3" placeholder="Describe what you need"></textarea>
                    <button class="btn btn-primary" type="submit">@lang('talent_ai::addons_talent_ai.ai_workspace.run_ai')</button>
                </form>
                <div data-ai-output class="mt-2 alert-muted">Awaiting input...</div>
            </div>
        @endforeach
    </div>
</div>
@endsection
