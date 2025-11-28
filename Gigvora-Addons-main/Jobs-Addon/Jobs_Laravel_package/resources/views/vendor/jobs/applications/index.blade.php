@extends('layouts.app')

@section('title', 'My Applications')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/jobs">Jobs</a></li>
        <li class="breadcrumb-item active" aria-current="page">Applications</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="applications-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">My Applications</h1>
        <div class="text-muted small">Stay on top of every opportunity</div>
    </div>
    <ul class="nav nav-tabs" id="application-tabs" role="tablist">
        @foreach(['all' => 'All', 'in_progress' => 'In Progress', 'interview' => 'Interview', 'rejected' => 'Rejected', 'hired' => 'Hired'] as $key => $label)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-status="{{ $key }}" data-bs-toggle="tab" type="button" role="tab">{{ $label }}</button>
            </li>
        @endforeach
    </ul>
    <div class="card mt-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="applications-table">
                    <thead class="table-light">
                        <tr>
                            <th>Job title</th>
                            <th>Company</th>
                            <th>Applied</th>
                            <th>Status</th>
                            <th>Next step</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($applications ?? []) as $application)
                        <tr data-status="{{ $application->status ?? 'applied' }}">
                            <td>{{ $application->job->title ?? 'Role' }}</td>
                            <td>{{ $application->job->company->name ?? 'Company' }}</td>
                            <td>{{ optional($application->created_at)->format('M d, Y') }}</td>
                            <td><span class="badge bg-secondary text-uppercase">{{ $application->status ?? 'Applied' }}</span></td>
                            <td>{{ $application->next_step ?? 'Awaiting review' }}</td>
                            <td class="text-end"><a href="{{ route('applications.show', $application->id ?? null) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No applications yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
import initTabs from "{{ mix('resources/js/jobs/jobsSearch.js') }}";
</script>
@endpush
