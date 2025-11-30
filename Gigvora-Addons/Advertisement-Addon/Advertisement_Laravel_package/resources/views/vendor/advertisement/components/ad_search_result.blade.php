<div class="gv-ad-search" data-ad-id="{{ $ad->id ?? '' }}" aria-label="{{ __('Sponsored search result') }}">
    <div class="gv-ad-search__meta">
        <span class="gv-ad-chip gv-ad-chip--ghost">{{ __('Sponsored') }}</span>
        <span class="gv-ad-chip gv-ad-chip--muted">{{ ucfirst($ad->status ?? 'active') }}</span>
    </div>
    <a href="{{ $ad->url ?? '#' }}" class="gv-ad-search__title">{{ $ad->title ?? __('Featured result') }}</a>
    <p class="gv-ad-search__snippet">{{ $ad->description ?? __('Smart placements reach members looking for jobs, gigs, and services right from search results.') }}</p>
    <div class="gv-ad-search__footer">
        <span>{{ $ad->display_url ?? $ad->url ?? '' }}</span>
        @if(!empty($ad->metrics['ctr']))
            <span>{{ __('CTR') }} {{ $ad->metrics['ctr'] }}</span>
        @endif
    </div>
</div>
