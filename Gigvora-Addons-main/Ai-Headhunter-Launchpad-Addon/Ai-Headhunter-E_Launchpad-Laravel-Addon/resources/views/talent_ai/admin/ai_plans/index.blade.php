@extends('layouts.admin')

@section('title', __('talent_ai::addons_talent_ai.menu.plans'))

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
