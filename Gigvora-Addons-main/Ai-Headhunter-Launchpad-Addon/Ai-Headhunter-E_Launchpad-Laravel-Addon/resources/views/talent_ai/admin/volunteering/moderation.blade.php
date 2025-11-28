@extends('layouts.admin')

@section('title', __('talent_ai::addons_talent_ai.menu.volunteering_moderation'))

@push('styles')
    @vite('resources/css/addons/talent_ai/talent_ai.css')
@endpush

@section('content')
<div class="talent-ai-page">
    <div class="talent-ai-header">
        <div>
            <h1 class="h4 mb-1">Volunteering moderation</h1>
            <p class="text-muted mb-0">Verify and publish volunteering opportunities.</p>
        </div>
    </div>

    @if(($opportunities ?? collect())->isEmpty())
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
                @foreach($opportunities as $opportunity)
                    <tr>
                        <td>{{ $opportunity->title }}</td>
                        <td>{{ $opportunity->creator?->name }}</td>
                        <td><span class="status-pill">{{ ucfirst($opportunity->status?->value ?? 'draft') }}</span></td>
                        <td class="table-actions">
                            <form method="post" action="{{ route('addons.talent_ai.volunteering.opportunity.publish', $opportunity) }}">
                                @csrf
                                <button class="btn btn-sm btn-success" type="submit">Approve</button>
                            </form>
                            <form method="post" action="{{ route('addons.talent_ai.volunteering.opportunity.close', $opportunity) }}">
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
