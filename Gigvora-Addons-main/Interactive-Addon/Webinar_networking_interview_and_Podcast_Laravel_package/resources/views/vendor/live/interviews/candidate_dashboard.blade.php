@extends('layouts.app')

@section('title', 'Interview Schedule')

@section('content')
<div class="container py-4" id="interview-dashboard">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="mb-1">Upcoming Interviews</h1>
            <div class="text-muted">Keep track of your interview schedule</div>
        </div>
        <button class="btn btn-outline-primary">Export</button>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="mb-3">Upcoming</h5>
                    @php
                        $upcoming = $upcoming ?? [
                            ['role' => 'Senior Engineer', 'company' => 'Acme', 'datetime' => 'May 7, 10:00', 'status' => 'Confirmed'],
                            ['role' => 'Product Manager', 'company' => 'Beta', 'datetime' => 'May 10, 14:00', 'status' => 'Pending'],
                        ];
                    @endphp
                    <div class="list-group" id="upcoming-list">
                        @foreach($upcoming as $item)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-semibold">{{ $item['role'] }} at {{ $item['company'] }}</div>
                                    <div class="text-muted small">{{ $item['datetime'] }}</div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-light text-dark">{{ $item['status'] }}</span>
                                    <a href="{{ route('wnip.interviews.show', ['interview' => $loop->index + 1]) ?? '#' }}" class="btn btn-outline-primary btn-sm">View Details</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">Past interviews</h5>
                    <p class="text-muted mb-0">Your previous interviews will appear here.</p>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            @include('vendor.live.components.calendar_widget')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/interviewDashboard.js') }}"></script>
@endpush
