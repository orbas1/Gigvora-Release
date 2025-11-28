@extends('layouts.app')

@section('title', 'Interview Detail')

@section('content')
@php
    $interview = $interview ?? [
        'role' => 'Senior Engineer',
        'company' => 'Acme',
        'interviewers' => 'Alex, Priya',
        'datetime' => 'May 7, 10:00',
        'duration' => '60 mins',
        'type' => 'Video',
        'instructions' => 'Join 5 minutes early and test your mic.',
    ];
@endphp
<div class="container py-4" id="candidate-interview" data-waiting-url="{{ route('wnip.interviews.waiting', ['interview' => $interview['id'] ?? 1]) ?? '#' }}">
    <div class="row g-4">
        <div class="col-lg-8">
            <h1 class="mb-1">{{ $interview['role'] }} at {{ $interview['company'] }}</h1>
            <div class="text-muted mb-3">{{ $interview['datetime'] }} • {{ $interview['duration'] }} • {{ $interview['type'] }}</div>
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Interviewers</h6>
                    <p class="mb-0">{{ $interview['interviewers'] }}</p>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h6>Instructions</h6>
                    <p class="mb-0">{{ $interview['instructions'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="fw-semibold mb-2">Join</div>
                    <button class="btn btn-primary w-100" id="join-waiting">Join Waiting Room</button>
                    <button class="btn btn-outline-secondary w-100 mt-2" id="join-interview" disabled>Join Interview</button>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <h6>Attachments</h6>
                    <ul class="list-unstyled mb-0">
                        <li><a href="#">Job description</a></li>
                        <li><a href="#">Prep doc</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/interviewDashboard.js') }}"></script>
@endpush
