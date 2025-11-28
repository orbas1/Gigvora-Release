@extends('layouts.admin')

@section('title', 'Interview Detail')

@section('content')
<div class="container-fluid py-4" id="admin-interview-detail">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h4 mb-1">Jamie Doe</h1>
            <div class="text-muted">Senior Engineer • May 7</div>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary">Override schedule</button>
            <button class="btn btn-outline-primary">Export scores</button>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">Slots</div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Interviewer</th><th>Time</th><th>Status</th></tr></thead>
                        <tbody>
                            <tr><td>Alex</td><td>10:00</td><td>Confirmed</td></tr>
                            <tr><td>Priya</td><td>10:30</td><td>Pending</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Scores</div>
                <div class="card-body">
                    <ul class="mb-0" id="score-summary">
                        <li>Communication: 4</li>
                        <li>Problem Solving: 5</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <h6>Validation</h6>
                    <p class="text-muted">Check required documents and criteria before confirming.</p>
                    <button class="btn btn-outline-primary w-100">Validate</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
