@extends('layouts.app')

@section('title', __('talent_ai::addons_talent_ai.menu.launchpad_moderation'))

@push('styles')
    @vite('resources/css/addons/talent_ai/talent_ai.css')
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
            <h1 class="h4 mb-1">Launchpad moderation</h1>
            <p class="text-muted mb-0">Review programmes before publishing.</p>
        </div>
    </div>

    @if(($programmes ?? collect())->isEmpty())
        <div class="alert-muted">@lang('talent_ai::addons_talent_ai.common.no_results')</div>
    @else
        <table class="talent-ai-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Creator</th>
                    <th>@lang('talent_ai::addons_talent_ai.common.status')</th>
                    <th>@lang('talent_ai::addons_talent_ai.common.actions')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($programmes as $programme)
                    <tr>
                        <td>{{ $programme->title }}</td>
                        <td>{{ $programme->creator?->name }}</td>
                        <td><span class="status-pill">{{ ucfirst($programme->status?->value ?? 'draft') }}</span></td>
                        <td class="table-actions">
                            <form method="post" action="{{ route('addons.talent_ai.launchpad.programme.publish', $programme) }}">
                                @csrf
                                <button class="btn btn-sm btn-success" type="submit">Approve</button>
                            </form>
                            <form method="post" action="{{ route('addons.talent_ai.launchpad.programme.close', $programme) }}">
                                @csrf
                                <button class="btn btn-sm btn-light" type="submit">Close</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
