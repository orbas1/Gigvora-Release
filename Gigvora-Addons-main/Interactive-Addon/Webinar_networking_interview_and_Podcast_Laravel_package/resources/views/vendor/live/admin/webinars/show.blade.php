@extends('layouts.admin')

@section('title', 'Webinar Detail')

@section('content')
<div class="container-fluid py-4" id="admin-webinar-detail">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Designing with AI</h1>
            <div class="text-muted">Host: Alice • May 5</div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-danger">Force Close</button>
            <button class="btn btn-outline-success">Approve Recording</button>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">Registrations</div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Name</th><th>Email</th><th>Status</th></tr></thead>
                        <tbody>
                            <tr><td>Jane</td><td>jane@example.com</td><td>Registered</td></tr>
                            <tr><td>Mike</td><td>mike@example.com</td><td>Attended</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Attendance logs</div>
                <div class="card-body">
                    <ul class="mb-0" id="attendance-logs">
                        <li>Jane joined 10:02</li>
                        <li>Mike left 10:45</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6>Recording status</h6>
                    <p class="text-muted">Processing</p>
                    <button class="btn btn-outline-primary w-100">Approve</button>
                    <button class="btn btn-outline-secondary w-100 mt-2">Reject</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
