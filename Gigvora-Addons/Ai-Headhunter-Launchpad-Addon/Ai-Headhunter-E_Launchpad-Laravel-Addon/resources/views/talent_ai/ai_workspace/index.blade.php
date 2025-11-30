@extends('layouts.app')

@section('title', __('talent_ai::addons_talent_ai.ai_workspace.title'))

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/addons/talent_ai/talent_ai.css') }}">
@endpush

@push('scripts')
    <script src="{{ mix('js/addons/talent_ai/ai_workspace.js') }}" defer></script>
@endpush

@section('content')
<div class="container py-4">
    <div class="talent-ai-page">
        <div class="talent-ai-header">
            <div>
                <p class="gv-eyebrow mb-1">@lang('talent_ai::addons_talent_ai.ai_workspace.title')</p>
                <h1 class="gv-heading text-lg mb-1">{{ get_phrase('Run curated AI tools for profiles, outreach, marketing, and more.') }}</h1>
                <p class="text-sm gv-muted mb-0">{{ get_phrase('Usage is rate-limited based on your subscription or BYOK configuration.') }}</p>
            </div>
            <a class="gv-btn gv-btn-ghost" href="{{ url()->previous() }}">
                <i class="fa-solid fa-arrow-left me-1"></i>@lang('talent_ai::addons_talent_ai.common.back')
            </a>
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
            <div class="ai-tool-card gv-card">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="text-base fw-semibold mb-0">{{ $tool['title'] }}</h3>
                    <span class="talent-ai-badge">AI</span>
                </div>
                <form class="ai-tool-form" data-endpoint="{{ $tool['endpoint'] }}">
                    <textarea name="prompt" class="form-control mb-2" rows="3" placeholder="{{ get_phrase('Describe what you need') }}"></textarea>
                    <button class="gv-btn gv-btn-primary w-100" type="submit">@lang('talent_ai::addons_talent_ai.ai_workspace.run_ai')</button>
                </form>
                <div data-ai-output class="mt-2 alert-muted">{{ get_phrase('Awaiting input...') }}</div>
            </div>
        @endforeach
    </div>
</div>
</div>
@endsection
