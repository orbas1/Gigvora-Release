@extends('layouts.admin')

@section('title', 'Networking Detail')

@section('content')
<div class="container-fluid py-4" id="admin-networking-detail">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Fintech founders</h1>
            <div class="text-muted">Host: Nia • May 4 • Speed (2 min)</div>
        </div>
        <button class="btn btn-outline-danger">Cancel session</button>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">Participants</div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Name</th><th>Role</th><th>City</th><th>Status</th></tr></thead>
                        <tbody>
                            <tr><td>Jane</td><td>PM</td><td>London</td><td>Joined</td></tr>
                            <tr><td>Mike</td><td>CTO</td><td>Berlin</td><td>Waiting</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Pairing logs</div>
                <div class="card-body">
                    <ul class="mb-0" id="pairing-logs">
                        <li>Round 1: Jane ⇄ Mike</li>
                        <li>Round 2: Jane ⇄ Sara</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header">Complaints / Reports</div>
                <div class="card-body">
                    <ul class="mb-0" id="complaints">
                        <li>No complaints</li>
                    </ul>
                </div>
            </div>
            <div class="card h-100">
                <div class="card-body">
                    <div class="fw-semibold mb-2">Adjustments</div>
                    <button class="btn btn-outline-primary w-100">Update capacity</button>
                    <button class="btn btn-outline-secondary w-100 mt-2">Re-run rotation</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
