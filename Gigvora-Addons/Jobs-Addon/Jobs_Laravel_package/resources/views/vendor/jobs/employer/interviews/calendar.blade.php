@extends('layouts.app')

@section('title', get_phrase('Interview calendar'))

@section('page-header')
    <div class="flex items-center justify-between flex-wrap gap-2">
        <h1 class="text-2xl font-semibold text-[var(--gv-color-neutral-900)] mb-0">{{ get_phrase('Interview calendar') }}</h1>
        <button class="gv-btn gv-btn-primary" id="new-slot">
            <i class="fa-solid fa-plus me-2"></i>{{ get_phrase('Schedule interview') }}
        </button>
    </div>
@endsection

@section('content')
    @include('vendor.jobs.components.calendar_widget', ['events' => $events ?? []])
@endsection

@push('scripts')
    <script type="module" src="{{ mix('resources/js/jobs/interviewCalendar.js') }}"></script>
@endpush