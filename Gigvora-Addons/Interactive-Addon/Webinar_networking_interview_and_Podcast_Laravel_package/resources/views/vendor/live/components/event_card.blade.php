@php
    $cta = $cta ?? 'View Details';
@endphp
<div class="card h-100 shadow-sm">
    <div class="card-body d-flex flex-column">
        <div class="d-flex align-items-center mb-2">
            <div class="flex-grow-1">
                <h5 class="card-title mb-1">{{ $title ?? 'Event Title' }}</h5>
                <p class="card-subtitle text-muted small">{{ $host ?? 'Host name' }} • {{ $datetime ?? 'Date & Time' }}</p>
            </div>
            <span class="badge bg-light text-dark border">{{ $tag ?? 'Free' }}</span>
        </div>
        <p class="text-muted mb-3">{{ $description ?? 'Short description of the event goes here.' }}</p>
        <div class="d-flex align-items-center mt-auto">
            <span class="badge bg-{{ ($status ?? 'Scheduled') === 'Live' ? 'danger' : 'secondary' }} me-2">{{ $status ?? 'Scheduled' }}</span>
            <a href="{{ $href ?? '#' }}" class="btn btn-sm btn-primary ms-auto">{{ $cta }}</a>
        </div>
    </div>
</div>
