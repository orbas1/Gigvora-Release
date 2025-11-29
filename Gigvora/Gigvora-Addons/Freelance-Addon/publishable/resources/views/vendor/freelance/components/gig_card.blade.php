<div class="card mb-3 gig-card">
    <div class="card-body d-flex justify-content-between align-items-start">
        <div>
            <h5 class="card-title mb-1">{{ $gig['title'] ?? 'Gig Title' }}</h5>
            <p class="text-muted mb-2">{{ $gig['category'] ?? 'Category' }} · {{ $gig['delivery_time'] ?? '3 days' }}</p>
            <div class="d-flex gap-2 align-items-center">
                <span class="badge bg-light text-dark">{{ ucfirst($gig['status'] ?? 'draft') }}</span>
                <span class="text-warning">★ {{ $gig['rating'] ?? '5.0' }}</span>
                <span class="text-muted">{{ $gig['orders_queue'] ?? 0 }} orders in queue</span>
            </div>
        </div>
        <div class="text-end">
            <div class="fw-bold h5 mb-0">{{ $gig['price'] ?? '$0' }}</div>
            <small class="text-muted">starting at</small>
        </div>
    </div>
    <div class="card-footer bg-white d-flex justify-content-end gap-2">
        <a href="{{ $gig['view_url'] ?? '#' }}" class="btn btn-outline-secondary btn-sm">View</a>
        <a href="{{ $gig['edit_url'] ?? '#' }}" class="btn btn-outline-primary btn-sm">Edit</a>
        <button class="btn btn-outline-warning btn-sm" data-action="toggle-status" data-id="{{ $gig['id'] ?? '' }}">{{ ($gig['status'] ?? '') === 'active' ? 'Pause' : 'Activate' }}</button>
    </div>
</div>
