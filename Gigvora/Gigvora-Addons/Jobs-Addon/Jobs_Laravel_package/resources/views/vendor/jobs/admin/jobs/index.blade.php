@extends('layouts.admin')

@section('title', 'Moderate Jobs')

@section('breadcrumbs')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="/admin">Admin</a></li>
        <li class="breadcrumb-item active" aria-current="page">Jobs</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid py-4" id="admin-jobs-table">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Job moderation</h5>
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" id="filter-status">
                        <option value="">All statuses</option>
                        <option>Pending</option>
                        <option>Approved</option>
                        <option>Suspended</option>
                    </select>
                    <input type="text" class="form-control form-control-sm" id="filter-employer" placeholder="Employer">
                    <input type="date" class="form-control form-control-sm" id="filter-date">
                </div>
            </div>
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light"><tr><th>Job title</th><th>Employer</th><th>Posted</th><th>Status</th><th>Flags</th><th></th></tr></thead>
                    <tbody>
                        @forelse(($jobs ?? []) as $job)
                            <tr>
                                <td>{{ $job->title }}</td>
                                <td>{{ $job->employer->name ?? '' }}</td>
                                <td>{{ optional($job->created_at)->format('M d, Y') }}</td>
                                <td><span class="badge bg-light text-dark">{{ $job->status ?? 'pending' }}</span></td>
                                <td>{{ $job->flags_count ?? 0 }}</td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button class="btn btn-outline-success approve-job" data-id="{{ $job->id }}">Approve</button>
                                        <button class="btn btn-outline-danger reject-job" data-id="{{ $job->id }}">Reject</button>
                                        <button class="btn btn-outline-warning suspend-job" data-id="{{ $job->id }}">Suspend</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">No jobs found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
