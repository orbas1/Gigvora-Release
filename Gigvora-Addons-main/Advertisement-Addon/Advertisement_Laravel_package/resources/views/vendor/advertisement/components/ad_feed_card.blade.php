<div class="card ad-feed-card mb-3" data-ad-id="{{ $ad->id ?? '' }}">
    <div class="card-body d-flex gap-3">
        <div class="flex-shrink-0" style="width: 64px; height: 64px; background: #f5f5f5;" aria-label="Ad thumbnail"></div>
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="text-muted small mb-1">Sponsored</p>
                    <h6 class="mb-1">{{ $ad->title ?? 'Ad Title' }}</h6>
                    <p class="text-muted mb-1">{{ $ad->description ?? 'Ad description goes here.' }}</p>
                    <a href="{{ $ad->url ?? '#' }}" class="fw-semibold">{{ $ad->cta ?? 'Learn more' }}</a>
                </div>
                <span class="badge bg-light text-dark text-uppercase">{{ $ad->status ?? 'active' }}</span>
            </div>
        </div>
    </div>
</div>
