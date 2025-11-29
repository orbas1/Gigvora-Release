@extends('layouts.admin')

@section('title', 'Interviews')

@section('content')
<div class="container-fluid py-4" id="admin-interviews">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Interviews</h1>
        <input class="form-control w-auto" placeholder="Search" />
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Candidate</th><th>Role</th><th>Date</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <tr><td>Jamie Doe</td><td>Senior Engineer</td><td>May 7</td><td><span class="badge bg-secondary">Scheduled</span></td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('wnip.admin.interviews.show', 1) ?? '#' }}">View</a></td></tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
