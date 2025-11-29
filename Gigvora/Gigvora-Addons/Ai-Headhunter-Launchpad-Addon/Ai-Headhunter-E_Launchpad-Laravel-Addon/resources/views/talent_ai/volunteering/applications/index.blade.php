@extends('layouts.app')

@section('title', __('talent_ai::addons_talent_ai.volunteering.applications'))

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/addons/talent_ai/talent_ai.css') }}">
@endpush

@push('scripts')
    <script src="{{ mix('js/addons/talent_ai/talent_ai.js') }}" defer></script>
@endpush

@section('content')
<div class="talent-ai-page">
    <div class="talent-ai-header">
        <div>
            <h1 class="h4 mb-1">@lang('talent_ai::addons_talent_ai.volunteering.applications')</h1>
            <p class="text-muted mb-0">Track volunteer engagement and hours contributed.</p>
        </div>
    </div>

    @if(($applications ?? collect())->isEmpty())
        <div class="alert-muted">@lang('talent_ai::addons_talent_ai.common.no_results')</div>
    @else
        <table class="talent-ai-table">
            <thead>
                <tr>
                    <th>Opportunity</th>
                    <th>Volunteer</th>
                    <th>Hours</th>
                    <th>@lang('talent_ai::addons_talent_ai.common.status')</th>
                    <th>@lang('talent_ai::addons_talent_ai.common.actions')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $application)
                    <tr>
                        <td>{{ $application->opportunity?->title }}</td>
                        <td>{{ $application->user?->name }}</td>
                        <td>{{ $application->hours_contributed ?? 0 }}</td>
                        <td><span class="status-pill">{{ ucfirst($application->status?->value ?? 'pending') }}</span></td>
                        <td class="table-actions">
                            <a class="btn btn-sm btn-outline-primary" href="{{ url()->current().'/'.$application->id }}">Details</a>
                            <form method="post" action="{{ route('addons.talent_ai.volunteering.application.status', $application) }}">
                                @csrf
                                <select class="form-select form-select-sm" name="status">
                                    @foreach(\Gigvora\TalentAi\Domain\Shared\Enums\VolunteeringApplicationStatus::cases() as $status)
                                        <option value="{{ $status->value }}" @selected($application->status === $status)>{{ ucfirst($status->value) }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-light mt-1" type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">{{ $applications->links() ?? '' }}</div>
    @endif
</div>
@endsection
