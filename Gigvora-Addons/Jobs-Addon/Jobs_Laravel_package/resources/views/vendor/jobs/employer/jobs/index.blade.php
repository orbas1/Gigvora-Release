@extends('layouts.app')

@section('title', 'My Jobs')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/employer">Employer</a></li>
        <li class="breadcrumb-item active" aria-current="page">Jobs</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container py-4" id="employer-jobs-list">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Jobs</h1>
        <a class="btn btn-primary" href="{{ route('employer.jobs.create') }}">Post a Job</a>
    </div>
    <div class="mb-3">
        <div class="d-flex flex-wrap gap-2">
            @foreach(['draft' => 'Draft', 'open' => 'Open', 'paused' => 'Paused', 'closed' => 'Closed'] as $value => $label)
                <button class="btn btn-sm btn-outline-secondary filter-status" data-status="{{ $value }}">{{ $label }}</button>
            @endforeach
        </div>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Ref ID</th>
                            <th>Created</th>
                            <th>Expires</th>
                            <th>Status</th>
                            <th>Applications</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($jobs ?? []) as $job)
                            <tr data-status="{{ $job->status ?? 'draft' }}">
                                <td>{{ $job->title ?? '' }}</td>
                                <td>{{ $job->reference ?? '#' }}</td>
                                <td>{{ optional($job->created_at)->format('M d, Y') }}</td>
                                <td>{{ optional($job->expires_at)->format('M d, Y') }}</td>
                                <td><span class="badge bg-light text-dark text-uppercase">{{ $job->status ?? 'Draft' }}</span></td>
                                <td>{{ $job->applications_count ?? 0 }}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('employer.jobs.show', $job->id ?? null) }}" class="btn btn-outline-primary">View</a>
                                        <a href="{{ route('employer.jobs.edit', $job->id ?? null) }}" class="btn btn-outline-secondary">Edit</a>
                                        <button class="btn btn-outline-danger close-job" data-job-id="{{ $job->id ?? '' }}">Close</button>
                                    </div>
                                    <div class="d-flex justify-content-end gap-1 mt-1">
                                        <button class="btn btn-link btn-sm duplicate-job" data-job-id="{{ $job->id ?? '' }}">Duplicate</button>
                                        <button class="btn btn-link btn-sm promote-job" data-job-id="{{ $job->id ?? '' }}">Promote</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">No jobs yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
