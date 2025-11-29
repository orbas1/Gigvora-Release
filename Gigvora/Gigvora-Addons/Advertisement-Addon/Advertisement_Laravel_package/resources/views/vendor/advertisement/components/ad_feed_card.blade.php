<div class="card gigvora-ad gigvora-ad--feed mb-3" data-ad-id="{{ $ad->id ?? '' }}" aria-label="Gigvora sponsored feed card">
    <div class="card-body d-flex gap-3 align-items-start">
        <div class="gigvora-ad__media flex-shrink-0" style="width: 64px; height: 64px; background: #f5f5f5;" aria-hidden="true"></div>
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-start">
                <div class="gigvora-ad__body">
                    <p class="text-muted small mb-1">{{ __('Sponsored') }}</p>
                    <h6 class="mb-1">{{ $ad->title ?? 'Ad Title' }}</h6>
                    <p class="text-muted mb-1">{{ $ad->description ?? 'Ad description goes here.' }}</p>
                    <a href="{{ $ad->url ?? '#' }}" class="fw-semibold">{{ $ad->cta ?? __('Learn more') }}</a>
                </div>
                <span class="gigvora-ad__status badge bg-light text-dark text-uppercase" aria-label="Ad status">{{ $ad->status ?? 'active' }}</span>
            </div>
        </div>
    </div>
</div>
