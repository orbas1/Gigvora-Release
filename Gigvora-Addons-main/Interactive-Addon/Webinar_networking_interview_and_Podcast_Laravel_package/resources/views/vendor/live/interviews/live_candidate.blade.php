@extends('layouts.app')

@section('title', 'Interview Live')

@section('content')
<div class="container-fluid py-3" id="interview-live" data-session-id="{{ $interview['id'] ?? 1 }}">
    <div class="row g-3">
        <div class="col-lg-9">
            <div class="card">
                <div class="card-body p-0">
                    <div class="ratio ratio-16x9 bg-dark text-white d-flex align-items-center justify-content-center">
                        Video container
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card mb-3">
                <div class="card-body d-grid gap-2">
                    <button class="btn btn-outline-secondary" id="toggle-mic">Mute</button>
                    <button class="btn btn-outline-secondary" id="toggle-camera">Camera Off</button>
                    <button class="btn btn-danger" id="leave-interview">Leave</button>
                </div>
            </div>
            @include('vendor.live.components.notes_sidebar')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/interviewDashboard.js') }}"></script>
@endpush
