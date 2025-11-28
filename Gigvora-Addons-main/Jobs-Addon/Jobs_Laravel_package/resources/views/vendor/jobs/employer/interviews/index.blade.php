@extends('layouts.app')

@section('title', 'Interviews')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/employer">Employer</a></li>
        <li class="breadcrumb-item active" aria-current="page">Interviews</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="interviews-list">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Interviews</h1>
        <a href="{{ route('employer.interviews.calendar') }}" class="btn btn-outline-primary">Calendar view</a>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Candidate</th>
                            <th>Role</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($interviews ?? []) as $interview)
                            <tr>
                                <td>{{ $interview->candidate->name ?? '' }}</td>
                                <td>{{ $interview->job->title ?? '' }}</td>
                                <td>{{ optional($interview->scheduled_at)->format('M d, Y h:i A') }}</td>
                                <td><span class="badge bg-light text-dark">{{ $interview->status ?? 'Scheduled' }}</span></td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-secondary reschedule" data-id="{{ $interview->id ?? '' }}">Reschedule</button>
                                        <button class="btn btn-outline-danger cancel" data-id="{{ $interview->id ?? '' }}">Cancel</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No interviews scheduled.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('resources/js/jobs/interviewCalendar.js') }}"></script>
@endpush
