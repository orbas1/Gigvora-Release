<div class="d-flex align-items-center justify-content-between mb-3">
    <div>
        <h2 class="h4 mb-1">{{ $title ?? 'Waiting Room' }}</h2>
        <p class="mb-0 text-muted">Hosted by {{ $host ?? 'Host' }} · Starts at {{ $start ?? '00:00' }}</p>
    </div>
    <div class="text-end">
        <span class="badge bg-primary">{{ $status ?? 'Starting soon' }}</span>
        <div class="small text-muted mt-1">Timezone adjusted automatically</div>
    </div>
</div>
