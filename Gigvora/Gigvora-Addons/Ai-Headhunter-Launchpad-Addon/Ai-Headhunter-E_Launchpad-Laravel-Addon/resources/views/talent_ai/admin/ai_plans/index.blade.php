@extends('layouts.app')

@section('title', __('talent_ai::addons_talent_ai.menu.plans'))

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/addons/talent_ai/talent_ai.css') }}">
@endpush

@push('scripts')
    <script src="{{ mix('js/addons/talent_ai/admin_settings.js') }}" defer></script>
@endpush

@section('content')
<div class="container py-4">
    <div class="talent-ai-card mb-3">
        <ul class="nav nav-pills flex-column flex-lg-row gap-2">
            @include('talent_ai::admin.partials.menu')
        </ul>
    </div>
    <div class="talent-ai-header">
        <div>
            <h1 class="h4 mb-1">AI Subscription Plans</h1>
            <p class="text-muted mb-0">Define usage caps and pricing for AI access.</p>
        </div>
    </div>

    <div class="talent-ai-card">
        <h3 class="h6 mb-2">Existing plans</h3>
        @if(($plans ?? collect())->isEmpty())
            <div class="alert-muted">@lang('talent_ai::addons_talent_ai.common.no_results')</div>
        @else
            <table class="talent-ai-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Price</th>
                        <th>Limits</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plans as $plan)
                        <tr>
                            <td>{{ $plan->name }}</td>
                            <td>{{ $plan->slug }}</td>
                            <td>{{ $plan->price ?? 'Free' }}</td>
                            <td><code>{{ json_encode($plan->limits) }}</code></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @include('talent_ai::admin.ai_plans.form')
</div>
@endsection
