<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <div>
            <div class="fw-semibold">Interview Calendar</div>
            <small class="text-muted">Select a date to schedule</small>
        </div>
        <div class="btn-group" role="group">
            <button class="btn btn-outline-secondary btn-sm" data-calendar-nav="prev">Prev</button>
            <button class="btn btn-outline-secondary btn-sm" data-calendar-nav="today">Today</button>
            <button class="btn btn-outline-secondary btn-sm" data-calendar-nav="next">Next</button>
        </div>
    </div>
    <div class="card-body">
        <div id="interview-calendar" class="calendar-widget" data-events='@json($events ?? [])'></div>
    </div>
</div>
