@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3">
                <div class="card-body" id="webinar-video-container" style="min-height:320px; background:#f7f7f7;">
                    <div class="text-center text-muted">Embed your streaming widget here.</div>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header">Live Chat</div>
                <div class="card-body">
                    <textarea class="form-control" rows="3" placeholder="Share updates with attendees..."></textarea>
                    <button class="btn btn-primary mt-2">Send</button>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fw-semibold">{{ $webinar->title }}</div>
                            <div class="text-muted small">{{ $webinar->starts_at?->toDayDateTimeString() }}</div>
                        </div>
                        <span class="badge bg-danger">Live</span>
                    </div>
                    <p class="mt-2 text-muted small">Waiting room message: {{ $webinar->waiting_room_message ?? 'Stay engaged and participate in the Q&A.' }}</p>
                    <div class="small text-muted">Attendees: {{ $webinar->registrations()->count() }}</div>
                </div>
            </div>
            <div class="card shadow-sm">
                <div class="card-header">Resources</div>
                <div class="list-group list-group-flush">
                    <span class="list-group-item">Streaming Provider: {{ $webinar->stream_provider ?? 'Custom' }}</span>
                    @if($webinar->rtmp_endpoint)
                        <span class="list-group-item">RTMP: {{ $webinar->rtmp_endpoint }}</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
