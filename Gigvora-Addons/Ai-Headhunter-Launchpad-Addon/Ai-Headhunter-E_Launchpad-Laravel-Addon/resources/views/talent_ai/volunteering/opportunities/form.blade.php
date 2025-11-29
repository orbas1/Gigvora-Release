@extends('layouts.app')

@section('title', ($opportunity->exists ?? false) ? 'Edit opportunity' : 'Post opportunity')

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/addons/talent_ai/talent_ai.css') }}">
@endpush

@section('content')
<div class="talent-ai-page">
    <div class="talent-ai-header">
        <div>
            <h1 class="h4 mb-1">{{ ($opportunity->exists ?? false) ? 'Edit opportunity' : 'Post opportunity' }}</h1>
            <p class="text-muted mb-0">Share clear expectations for volunteers.</p>
        </div>
        <a class="btn btn-light" href="{{ url()->previous() }}">@lang('talent_ai::addons_talent_ai.common.back')</a>
    </div>

    <div class="talent-ai-card">
        <form method="post" action="{{ ($opportunity->exists ?? false) ? route('addons.talent_ai.volunteering.opportunity.update', $opportunity) : route('addons.talent_ai.volunteering.opportunity.store') }}">
            @csrf
            @if($opportunity->exists ?? false)
                @method('PUT')
            @endif
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input class="form-control" name="title" value="{{ old('title', $opportunity->title ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sector</label>
                    <input class="form-control" name="sector" value="{{ old('sector', $opportunity->sector ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Location</label>
                    <input class="form-control" name="location" value="{{ old('location', $opportunity->location ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Commitment</label>
                    <input class="form-control" name="commitment" value="{{ old('commitment', $opportunity->commitment ?? '') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="4" required>{{ old('description', $opportunity->description ?? '') }}</textarea>
                </div>
                <div class="col-md-6 form-check">
                    <input class="form-check-input" type="checkbox" name="expenses_covered" value="1" @checked(old('expenses_covered', $opportunity->expenses_covered ?? false))>
                    <label class="form-check-label">Expenses covered</label>
                </div>
                <div class="col-md-6 form-check">
                    <input class="form-check-input" type="checkbox" name="verified" value="1" @checked(old('verified', $opportunity->verified ?? false))>
                    <label class="form-check-label">Verified</label>
                </div>
            </div>
            <div class="mt-3 d-flex gap-2">
                <button class="btn btn-primary" type="submit">@lang('talent_ai::addons_talent_ai.common.save')</button>
                <a class="btn btn-light" href="{{ url()->previous() }}">@lang('talent_ai::addons_talent_ai.common.cancel')</a>
            </div>
        </form>
    </div>
</div>
@endsection
