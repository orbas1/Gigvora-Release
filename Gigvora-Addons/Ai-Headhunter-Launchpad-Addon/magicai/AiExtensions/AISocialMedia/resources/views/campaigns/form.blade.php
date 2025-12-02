@extends('panel.layout.app')
@section('title', __('Campaign'))

@section('content')
    <div class="page-body pt-6">
        <div class="container-xl">
            <div class="card max-w-3xl">
                <div class="card-body space-y-4">
                    <h2 class="text-lg font-semibold text-heading">{{ $item ? __('Edit Campaign') : __('Create Campaign') }}</h2>
                    <form
                        action="{{ route('dashboard.user.automation.campaign.campaignAddOrUpdateSave') }}"
                        method="post"
                    >
                        @csrf
                        <input
                            name="cam_id"
                            type="hidden"
                            value="{{ $item?->id }}"
                        >
                        <div class="mb-3">
                            <label class="form-label" for="cam_name">{{ __('Campaign Name') }}</label>
                            <input
                                class="form-control"
                                id="cam_name"
                                name="cam_name"
                                required
                                type="text"
                                value="{{ old('cam_name', $item?->name) }}"
                            >
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="cam_target">{{ __('Target Audience / Goal') }}</label>
                            <textarea
                                class="form-control"
                                id="cam_target"
                                name="cam_target"
                                required
                                rows="4"
                            >{{ old('cam_target', $item?->target_audience) }}</textarea>
                            <small class="form-hint">{{ __('Describe who the campaign should reach and the intended outcome.') }}</small>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="btn btn-primary" type="submit">{{ __('Save Campaign') }}</button>
                            <a class="btn" href="{{ route('dashboard.user.automation.campaign.list') }}">{{ __('Back to list') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
