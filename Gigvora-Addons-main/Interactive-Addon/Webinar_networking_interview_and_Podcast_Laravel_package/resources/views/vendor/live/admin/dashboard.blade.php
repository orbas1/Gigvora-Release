@extends('layouts.admin')

@section('title', 'Live & Events Dashboard')

@section('content')
<div
    class="container-fluid py-4"
    id="admin-live-dashboard"
    data-webinars-endpoint="{{ url('/api/wnip/webinars') }}"
    data-networking-endpoint="{{ url('/api/wnip/networking') }}"
    data-interviews-endpoint="{{ url('/api/wnip/interviews') }}"
>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Live & Events</h1>
            <p class="text-muted mb-0">Monitor webinars, networking, podcasts, and interviews</p>
        </div>
        <button class="btn btn-outline-secondary">Refresh</button>
    </div>

    <div class="row g-3 mb-4" id="admin-metrics">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Upcoming webinars</div>
                    <div class="display-6" data-metric="upcoming-webinars">--</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Networking events</div>
                    <div class="display-6" data-metric="networking-events">--</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Interviews this week</div>
                    <div class="display-6" data-metric="interviews-week">--</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Hours recorded</div>
                    <div class="display-6" data-metric="hours-recorded">--</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">Upcoming events</div>
                <div class="table-responsive">
                    <table class="table mb-0" id="admin-events-table">
                        <thead><tr><th>Type</th><th>Title</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
                        <tbody id="admin-events-body">
                            <tr><td colspan="5" class="text-muted">Loading live events...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-header">Issues & Logs</div>
                <div class="card-body">
                    <ul class="mb-0" id="issue-log">
                        <li>Recording failed for Webinar #13</li>
                        <li>Flagged behaviour in Networking #7</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">Attendance stats</div>
                <div class="card-body">
                    <div id="admin-metrics-chart" class="text-center text-muted">Charts placeholder</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module" src="{{ mix('js/live/adminLiveDashboard.js') }}"></script>
@endpush
