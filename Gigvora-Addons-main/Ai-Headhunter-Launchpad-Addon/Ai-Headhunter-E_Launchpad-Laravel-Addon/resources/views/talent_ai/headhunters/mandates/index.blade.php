@extends('layouts.app')

@section('title', __('talent_ai::addons_talent_ai.headhunters.mandates'))

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
            <h1 class="h4 mb-1">@lang('talent_ai::addons_talent_ai.headhunters.mandates')</h1>
            <p class="text-muted mb-0">Manage retained searches, status, and outreach.</p>
        </div>
        @isset($profile)
            <form class="d-flex gap-2" method="post" action="{{ route('addons.talent_ai.headhunter.mandate.store', $profile) }}">
                @csrf
                <input type="text" name="title" class="form-control" placeholder="New mandate title" required>
                <button class="btn btn-primary" type="submit">Create</button>
            </form>
        @endisset
    </div>

    <div class="filter-bar">
        <input type="search" name="keyword" class="form-control" placeholder="Search mandates">
        <select class="form-select" name="status">
            <option value="">All statuses</option>
            @foreach(\Gigvora\TalentAi\Domain\Shared\Enums\HeadhunterMandateStatus::cases() as $status)
                <option value="{{ $status->value }}">{{ ucfirst($status->value) }}</option>
            @endforeach
        </select>
    </div>

    @if(($mandates ?? collect())->isEmpty())
        <div class="alert-muted">@lang('talent_ai::addons_talent_ai.common.no_results')</div>
    @else
        <table class="talent-ai-table">
            <thead>
                <tr>
                    <th>Role</th>
                    <th>Location</th>
                    <th>Fee</th>
                    <th>@lang('talent_ai::addons_talent_ai.common.status')</th>
                    <th>@lang('talent_ai::addons_talent_ai.common.actions')</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mandates as $mandate)
                    <tr>
                        <td class="fw-semibold">{{ $mandate->title }}</td>
                        <td>{{ $mandate->location }}</td>
                        <td>{{ $mandate->fee_amount ? '$'.$mandate->fee_amount : 'TBC' }}</td>
                        <td><span class="status-pill">{{ ucfirst($mandate->status?->value ?? 'draft') }}</span></td>
                        <td class="table-actions">
                            <a class="btn btn-sm btn-outline-primary" href="{{ $mandate->id ? url()->current().'/'.$mandate->id : '#' }}">View</a>
                            <form method="post" action="{{ route('addons.talent_ai.headhunter.mandate.update', $mandate) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button class="btn btn-sm btn-light" type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-3">
            {{ $mandates->links() ?? '' }}
        </div>
    @endif
</div>
@endsection
