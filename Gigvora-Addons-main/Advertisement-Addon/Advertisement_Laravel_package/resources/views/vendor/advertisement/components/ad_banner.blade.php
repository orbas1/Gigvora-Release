<div class="gigvora-ad gigvora-ad--banner my-3" data-ad-id="{{ $ad->id ?? '' }}" aria-label="Gigvora banner advertisement">
    <div class="gigvora-ad__shell d-flex align-items-center justify-content-between p-3 border rounded">
        <div class="gigvora-ad__body">
            <p class="text-muted small mb-1">{{ __('Sponsored') }}</p>
            <h6 class="mb-1">{{ $ad->title ?? 'Banner Ad' }}</h6>
            <p class="text-muted mb-0">{{ $ad->description ?? 'Promotional text appears here.' }}</p>
        </div>
        <a href="{{ $ad->url ?? '#' }}" class="btn btn-primary">{{ $ad->cta ?? __('Learn more') }}</a>
    </div>
</div>
