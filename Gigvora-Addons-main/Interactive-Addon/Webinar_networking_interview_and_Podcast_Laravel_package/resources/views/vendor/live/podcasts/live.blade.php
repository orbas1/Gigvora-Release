@extends('layouts.app')

@section('title', 'Live Podcast Recording')

@section('content')
<div class="container py-4" id="podcast-live" data-session-id="{{ $session['id'] ?? 1 }}">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Recording Session</h5>
                        <div class="text-end">
                            <div class="text-muted">Status</div>
                            <span class="badge bg-danger" id="recording-status">Live</span>
                        </div>
                    </div>
                    <div class="border rounded p-4 bg-dark text-white text-center">Audio/RTC container</div>
                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-primary" id="toggle-record">Record / Stop</button>
                        <button class="btn btn-outline-secondary" id="mute-guests">Mute Guests</button>
                        <div class="ms-auto text-muted" id="recording-timer">00:00</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Guests</h6>
                    <ul class="list-group" id="guest-list">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>Guest 1</span>
                            <span class="form-check form-switch mb-0"><input class="form-check-input" type="checkbox" checked></span>
                        </li>
                    </ul>
                </div>
            </div>
            @include('vendor.live.components.notes_sidebar')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/podcastPlayer.js') }}"></script>
@endpush
