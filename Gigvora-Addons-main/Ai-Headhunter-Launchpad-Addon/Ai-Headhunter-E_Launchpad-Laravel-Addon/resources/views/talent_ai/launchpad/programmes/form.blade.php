@extends('layouts.app')

@section('title', ($programme->exists ?? false) ? 'Edit programme' : 'Create programme')

@push('styles')
    @vite('resources/css/addons/talent_ai/talent_ai.css')
@endpush

@section('content')
<div class="talent-ai-page">
    <div class="talent-ai-header">
        <div>
            <h1 class="h4 mb-1">{{ ($programme->exists ?? false) ? 'Edit programme' : 'Create programme' }}</h1>
            <p class="text-muted mb-0">Define clear tasks, duration, and outcomes for learners.</p>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-light">@lang('talent_ai::addons_talent_ai.common.back')</a>
    </div>

    <div class="talent-ai-card">
        <form method="post" action="{{ ($programme->exists ?? false) ? route('addons.talent_ai.launchpad.programme.update', $programme) : route('addons.talent_ai.launchpad.programme.store') }}">
            @csrf
            @if($programme->exists ?? false)
                @method('PUT')
            @endif
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input class="form-control" name="title" value="{{ old('title', $programme->title ?? '') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Category</label>
                    <input class="form-control" name="category" value="{{ old('category', $programme->category ?? '') }}" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="4" required>{{ old('description', $programme->description ?? '') }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Estimated hours</label>
                    <input class="form-control" type="number" name="estimated_hours" value="{{ old('estimated_hours', $programme->estimated_hours ?? 0) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Estimated weeks</label>
                    <input class="form-control" type="number" name="estimated_weeks" value="{{ old('estimated_weeks', $programme->estimated_weeks ?? 0) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Pay reduction (%)</label>
                    <input class="form-control" type="number" step="0.1" name="pay_reduction_percentage" value="{{ old('pay_reduction_percentage', $programme->pay_reduction_percentage ?? 0) }}">
                </div>
                <div class="col-md-6 form-check">
                    <input class="form-check-input" type="checkbox" name="reference_offered" value="1" @checked(old('reference_offered', $programme->reference_offered ?? false))>
                    <label class="form-check-label">Reference offered</label>
                </div>
                <div class="col-md-6 form-check">
                    <input class="form-check-input" type="checkbox" name="qualification_offered" value="1" @checked(old('qualification_offered', $programme->qualification_offered ?? false))>
                    <label class="form-check-label">Qualification offered</label>
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
