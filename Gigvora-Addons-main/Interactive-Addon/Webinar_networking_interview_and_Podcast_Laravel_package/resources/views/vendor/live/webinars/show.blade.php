@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h1 class="mb-1">{{ $webinar->title }}</h1>
                    <p class="text-muted mb-1">Hosted by {{ optional($webinar->host)->name ?? 'Host' }}</p>
                    <p class="text-muted">{{ $webinar->starts_at?->toDayDateTimeString() }} • {{ $webinar->ends_at?->diffInMinutes($webinar->starts_at) }} mins</p>
                </div>
                <span class="badge bg-{{ $webinar->is_live ? 'danger' : 'secondary' }}">{{ $webinar->is_live ? 'Live' : ucfirst($webinar->status) }}</span>
            </div>

            <div class="card mb-3 shadow-sm">
                <div class="card-body">
                    <h5>Description</h5>
                    <p class="text-muted">{!! nl2br(e($webinar->description)) !!}</p>
                    <h6 class="mt-3">Agenda</h6>
                    <ul class="mb-0">
                        <li>Key talking points</li>
                        <li>Q&A and networking</li>
                        <li>Replay available after the session</li>
                    </ul>
                </div>
            </div>

            @if($webinar->recordings->isNotEmpty())
            <div class="card mb-3 shadow-sm">
                <div class="card-header">Past recordings</div>
                <div class="list-group list-group-flush">
                    @foreach($webinar->recordings as $recording)
                        <div class="list-group-item d-flex justify-content-between">
                            <div>
                                <div class="fw-semibold">{{ $recording->title ?? 'Replay' }}</div>
                                <div class="text-muted small">{{ $recording->duration ? $recording->duration . 's' : '' }}</div>
                            </div>
                            <a class="btn btn-link" href="{{ $recording->path }}">Watch</a>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div>
                            <div class="fw-semibold">{{ $webinar->is_paid ? 'Paid' : 'Free' }}</div>
                            <div class="text-muted small">{{ $webinar->registrations_count ?? $webinar->registrations->count() }} attendees registered</div>
                        </div>
                        @if($webinar->is_paid)
                            <span class="badge bg-warning text-dark">{{ number_format($webinar->price, 2) }}</span>
                        @endif
                    </div>

                    @auth
                        <form method="post" action="{{ route('wnip.webinars.register', $webinar) }}">
                            @csrf
                            <button class="btn btn-primary w-100" type="submit">{{ $registration ? 'Registered' : 'Register' }}</button>
                        </form>
                        <a class="btn btn-outline-secondary w-100 mt-2" href="{{ route('wnip.webinars.waiting', $webinar) }}">Join Waiting Room</a>
                    @else
                        <p class="text-muted">Sign in to register.</p>
                    @endauth
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="fw-semibold">Share</h6>
                    <div class="d-flex gap-2">
                        <a class="btn btn-sm btn-outline-secondary" href="https://twitter.com/intent/tweet?text={{ urlencode($webinar->title) }}" target="_blank">Twitter</a>
                        <a class="btn btn-sm btn-outline-secondary" href="mailto:?subject={{ urlencode($webinar->title) }}">Email</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
