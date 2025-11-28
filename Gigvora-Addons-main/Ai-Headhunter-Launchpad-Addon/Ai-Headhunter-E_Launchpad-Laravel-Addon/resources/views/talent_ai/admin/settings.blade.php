@extends('layouts.admin')

@section('title', __('talent_ai::addons_talent_ai.admin.settings_title'))

@push('styles')
    @vite('resources/css/addons/talent_ai/talent_ai.css')
@endpush

@push('scripts')
    @vite('resources/js/addons/talent_ai/talent_ai.js')
@endpush

@section('content')
<div class="talent-ai-page">
    @php($config = $config ?? [])
    <div class="talent-ai-header">
        <div>
            <h1 class="h4 mb-1">@lang('talent_ai::addons_talent_ai.admin.settings_title')</h1>
            <p class="text-muted mb-0">Control module access, providers, and guardrails.</p>
        </div>
    </div>

    <div class="talent-ai-card">
        <form data-admin-settings-form data-update-url="{{ route('addons.talent_ai.admin.config') }}">
            <h4 class="h6 mb-3">@lang('talent_ai::addons_talent_ai.admin.module_toggles')</h4>
            <div class="row g-3">
                @foreach(['headhunter' => 'Headhunters', 'launchpad' => 'Experience Launchpad', 'ai_workspace' => 'AI Workspace', 'volunteering' => 'Volunteering'] as $key => $label)
                    <div class="col-md-3 form-check">
                        <input class="form-check-input" type="checkbox" name="modules[{{ $key }}]" value="1" @checked(data_get($config, "modules.$key.enabled"))>
                        <label class="form-check-label">{{ $label }}</label>
                    </div>
                @endforeach
            </div>

            <hr>

            <h4 class="h6">@lang('talent_ai::addons_talent_ai.admin.ai_provider')</h4>
            <select class="form-select mb-2" name="ai[provider]">
                @foreach(['openai', 'anthropic', 'local', 'stub'] as $provider)
                    <option value="{{ $provider }}" @selected(data_get($config, 'ai.provider') === $provider)> {{ ucfirst($provider) }}</option>
                @endforeach
            </select>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="ai[byok][enabled]" value="1" @checked(data_get($config, 'ai.byok.enabled'))>
                <label class="form-check-label">Enable BYOK</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="ai[platform_keys][enabled]" value="1" @checked(data_get($config, 'ai.platform_keys.enabled'))>
                <label class="form-check-label">Platform-managed keys</label>
            </div>

            <hr>
            <h4 class="h6">@lang('talent_ai::addons_talent_ai.admin.safety')</h4>
            <p class="text-muted">No raw keys displayed. Summaries only:</p>
            <ul class="mb-3">
                <li>Daily limit: {{ data_get($config, 'ai.limits.daily') ?? 'n/a' }}</li>
                <li>Prompt max length: {{ data_get($config, 'ai.guardrails.max_prompt_length') ?? 'n/a' }}</li>
            </ul>

            <div class="d-flex gap-2 align-items-center">
                <button class="btn btn-primary" type="submit">@lang('talent_ai::addons_talent_ai.common.save')</button>
                <span class="text-muted" data-settings-feedback></span>
            </div>
        </form>
    </div>
</div>
@endsection
