<div class="gigvora-ad gigvora-ad--search py-3 border-bottom" data-ad-id="{{ $ad->id ?? '' }}" aria-label="Gigvora sponsored search result">
    <div class="d-flex justify-content-between align-items-start gap-3">
        <div class="gigvora-ad__body">
            <p class="text-muted small mb-1">{{ __('Sponsored') }}</p>
            <a href="{{ $ad->url ?? '#' }}" class="h6 d-block mb-1">{{ $ad->title ?? 'Search Ad Title' }}</a>
            <p class="mb-1 text-muted">{{ $ad->description ?? 'Ad description shown in search results.' }}</p>
            <span class="badge bg-light text-dark">{{ $ad->display_url ?? $ad->url ?? '' }}</span>
        </div>
        <span class="gigvora-ad__status badge bg-success" aria-label="Ad status">{{ $ad->status ?? 'active' }}</span>
    </div>
</div>
