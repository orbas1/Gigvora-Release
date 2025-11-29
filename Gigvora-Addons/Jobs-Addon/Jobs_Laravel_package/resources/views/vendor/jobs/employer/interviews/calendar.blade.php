@extends('layouts.app')

@section('title', 'Interview Calendar')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/employer">Employer</a></li>
        <li class="breadcrumb-item"><a href="{{ route('employer.interviews.index') }}">Interviews</a></li>
        <li class="breadcrumb-item active" aria-current="page">Calendar</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="interview-calendar-page">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Interview Calendar</h1>
        <button class="btn btn-primary" id="new-slot">Schedule Interview</button>
    </div>
    @include('vendor.jobs.components.calendar_widget', ['events' => $events ?? []])
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/jobs/interviewCalendar.js') }}"></script>
@endpush
