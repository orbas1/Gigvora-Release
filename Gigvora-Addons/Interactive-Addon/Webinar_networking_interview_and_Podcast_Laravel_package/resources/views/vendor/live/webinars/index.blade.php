@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="mb-1">Webinars</h1>
            <p class="text-muted mb-0">Discover live, upcoming, and recorded webinars.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('wnip.webinars.index', ['upcoming' => 1]) }}">Host a Webinar</a>
    </div>

    <form method="get" class="card mb-3 shadow-sm p-3">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <input type="text" name="q" class="form-control" value="{{ $filters['q'] ?? '' }}" placeholder="Title or description">
            </div>
            <div class="col-md-2">
                <label class="form-label">Upcoming</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="upcoming" value="1" @checked($filters['upcoming'] ?? false)>
                    <label class="form-check-label">Only upcoming</label>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">Past</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="past" value="1" @checked($filters['past'] ?? false)>
                    <label class="form-check-label">Past sessions</label>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">Pricing</label>
                <select class="form-select" name="paid">
                    <option value="">Any</option>
                    <option value="0" @selected(($filters['paid'] ?? '')==='0')>Free</option>
                    <option value="1" @selected(($filters['paid'] ?? '')==='1')>Paid</option>
                </select>
            </div>
            <div class="col-md-2 text-end">
                <button class="btn btn-outline-secondary" type="submit">Apply</button>
            </div>
        </div>
    </form>

    <div class="row g-3">
        @forelse($webinars as $webinar)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <div class="badge bg-{{ $webinar->is_live ? 'danger' : 'secondary' }} mb-1">{{ $webinar->is_live ? 'Live' : ucfirst($webinar->status) }}</div>
                                <h5 class="card-title">{{ $webinar->title }}</h5>
                                <p class="card-subtitle text-muted">{{ optional($webinar->host)->name ?? 'Host' }}</p>
                            </div>
                            <span class="badge bg-light text-dark">{{ $webinar->is_paid ? 'Paid' : 'Free' }}</span>
                        </div>
                        <p class="text-muted small flex-grow-1">{{ \Illuminate\Support\Str::limit($webinar->description, 120) }}</p>
                        <div class="small text-muted mb-2">{{ optional($webinar->starts_at)->toDayDateTimeString() }}</div>
                        <a class="btn btn-outline-primary w-100" href="{{ route('wnip.webinars.show', $webinar) }}">View Details</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No webinars found.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $webinars->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-live-at]').forEach(function(el) {
        const startsAt = new Date(el.dataset.liveAt);
        const badge = el.querySelector('.live-badge');
        if (!badge) return;
        const tick = () => {
            const now = new Date();
            if (now >= startsAt) {
                badge.textContent = 'Live now';
                badge.classList.remove('bg-secondary');
                badge.classList.add('bg-danger');
            }
        };
        tick();
        setInterval(tick, 15000);
    });
</script>
@endpush
