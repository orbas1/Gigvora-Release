@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="mb-1">{{ $session->title }}</h1>
            <p class="text-muted">{{ $session->starts_at?->toDayDateTimeString() }} • {{ $session->rotation_interval }}s rotations</p>
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5>Description</h5>
                    <p class="text-muted">{!! nl2br(e($session->description)) !!}</p>
                    <h6 class="mt-3">What to expect</h6>
                    <ul>
                        <li>Meet new peers every {{ $session->rotation_interval }} seconds</li>
                        <li>Auto-rotation and partner assignments</li>
                        <li>Exportable connection list</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="fw-semibold">{{ $session->participants->count() }} registered</div>
                        <span class="badge bg-light text-dark">{{ ucfirst($session->status) }}</span>
                    </div>
                    @auth
                        <form method="post" action="{{ route('wnip.networking.register', $session) }}">
                            @csrf
                            <button class="btn btn-primary w-100" type="submit">{{ $participant ? 'Registered' : 'Register' }}</button>
                        </form>
                        <a class="btn btn-outline-secondary w-100 mt-2" href="{{ route('wnip.networking.waiting', $session) }}">Waiting Room</a>
                    @else
                        <p class="text-muted">Sign in to join.</p>
                    @endauth
                    <p class="text-muted small mt-2">Hosted by {{ optional($session->host)->name ?? 'Host' }}</p>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header">Participants</div>
                <div class="list-group list-group-flush">
                    @forelse($session->participants as $row)
                        <div class="list-group-item">User #{{ $row->user_id }} {{ $row->status }}</div>
                    @empty
                        <div class="list-group-item text-muted">No participants yet.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
