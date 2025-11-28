@extends('layouts.app')

@section('title', __('talent_ai::addons_talent_ai.menu.headhunter_management'))

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/addons/talent_ai/talent_ai.css') }}">
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
            <h1 class="h4 mb-1">Headhunters</h1>
            <p class="text-muted mb-0">Approve and manage headhunter profiles.</p>
        </div>
    </div>

    @if(($profiles ?? collect())->isEmpty())
        <div class="alert-muted">@lang('talent_ai::addons_talent_ai.common.no_results')</div>
    @else
        <table class="talent-ai-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Industries</th>
                    <th>@lang('talent_ai::addons_talent_ai.common.status')</th>
                    <th>@lang('talent_ai::addons_talent_ai.common.actions')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($profiles as $profile)
                    <tr>
                        <td>{{ $profile->user?->name }}</td>
                        <td>{{ implode(', ', $profile->industries ?? []) }}</td>
                        <td><span class="status-pill">{{ ucfirst($profile->status?->value ?? 'pending') }}</span></td>
                        <td>
                            <form method="post" action="{{ route('addons.talent_ai.headhunter.profile.update', $profile) }}" class="d-flex gap-2 align-items-center">
                                @csrf
                                @method('PUT')
                                <select class="form-select form-select-sm" name="status">
                                    @foreach(\Gigvora\TalentAi\Domain\Shared\Enums\HeadhunterProfileStatus::cases() as $status)
                                        <option value="{{ $status->value }}" @selected($profile->status === $status)>{{ ucfirst($status->value) }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-primary" type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
