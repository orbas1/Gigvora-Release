@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="mb-2">Current Partner</h5>
                    <p class="text-muted">Rotations automatically advance every {{ $session->rotation_interval }} seconds.</p>
                    <div class="alert alert-info">Share quick intros and add notes below.</div>
                    <textarea class="form-control" rows="4" placeholder="Notes about this connection..."></textarea>
                    <button class="btn btn-primary mt-2">Save Notes</button>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fw-semibold">{{ $session->title }}</div>
                            <div class="text-muted small">{{ $session->starts_at?->toDayDateTimeString() }}</div>
                        </div>
                        <span class="badge bg-success">Live</span>
                    </div>
                    <div class="mt-2 text-muted small">Participants: {{ $session->participants->count() }}</div>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header">Rotation Roster</div>
                <ul class="list-group list-group-flush">
                    @forelse($session->participants as $row)
                        <li class="list-group-item">Seat {{ $row->rotation_position }} • User {{ $row->user_id }}</li>
                    @empty
                        <li class="list-group-item text-muted">No attendees</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
