@extends('layouts.admin')

@section('title', 'Manage Webinars')

@section('content')
<div class="container-fluid py-4" id="admin-webinars">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Webinars</h1>
        <input type="search" class="form-control w-auto" placeholder="Search" />
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Title</th><th>Host</th><th>Date/Time</th><th>Status</th><th>Registrations</th><th></th></tr></thead>
            <tbody>
                <tr>
                    <td>Designing with AI</td><td>Alice</td><td>May 5</td><td><span class="badge bg-success">Live</span></td><td>320</td>
                    <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('wnip.admin.webinars.show', 1) ?? '#' }}">View</a></td>
                </tr>
                <tr>
                    <td>Scaling APIs</td><td>Bob</td><td>May 8</td><td><span class="badge bg-secondary">Scheduled</span></td><td>120</td>
                    <td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('wnip.admin.webinars.show', 2) ?? '#' }}">View</a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
