@extends('layouts.app')

@section('title', get_phrase('Company profile'))

@section('page-header')
    <div class="space-y-1">
        <p class="gv-eyebrow mb-0">{{ get_phrase('Employer brand') }}</p>
        <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-0">{{ get_phrase('Company profile') }}</h1>
    </div>
@endsection

@section('content')
    <form method="post" action="{{ route('employer.company.update') }}" class="gv-card space-y-4">
        @csrf
        @method('put')
        <div class="grid gap-4 md:grid-cols-2">
            <div class="space-y-2">
                <label class="gv-label">{{ get_phrase('Company name') }}</label>
                <input class="gv-input" name="name" value="{{ $company->name }}" required>
            </div>
            <div class="space-y-2">
                <label class="gv-label">{{ get_phrase('Website') }}</label>
                <input class="gv-input" name="website" value="{{ $company->website }}">
            </div>
            <div class="space-y-2">
                <label class="gv-label">{{ get_phrase('Location') }}</label>
                <input class="gv-input" name="location" value="{{ $company->location }}">
            </div>
            <div class="space-y-2">
                <label class="gv-label">{{ get_phrase('Headline') }}</label>
                <input class="gv-input" name="headline" value="{{ $company->headline }}">
            </div>
        </div>
        <div class="space-y-2">
            <label class="gv-label">{{ get_phrase('About company') }}</label>
            <textarea class="gv-input min-h-[160px]" name="description">{{ $company->description }}</textarea>
        </div>
        <div class="flex justify-end">
            <button class="gv-btn gv-btn-primary" type="submit">{{ get_phrase('Save changes') }}</button>
        </div>
    </form>
@endsection