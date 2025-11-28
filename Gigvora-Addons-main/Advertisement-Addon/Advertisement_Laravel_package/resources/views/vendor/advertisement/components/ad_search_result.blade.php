<div class="ad-search-result py-3 border-bottom" data-ad-id="{{ $ad->id ?? '' }}">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <p class="text-muted small mb-1">Sponsored</p>
            <a href="{{ $ad->url ?? '#' }}" class="h6 d-block mb-1">{{ $ad->title ?? 'Search Ad Title' }}</a>
            <p class="mb-1 text-muted">{{ $ad->description ?? 'Ad description shown in search results.' }}</p>
            <span class="badge bg-light text-dark">{{ $ad->display_url ?? $ad->url ?? '' }}</span>
        </div>
        <span class="badge bg-success">{{ $ad->status ?? 'active' }}</span>
    </div>
</div>
