<article class="gv-ad-banner" data-ad-id="{{ $ad->id ?? '' }}" aria-label="{{ __('Sponsored placement') }}">
    <div class="gv-ad-banner__copy">
        <p class="gv-ad-chip gv-ad-chip--ghost mb-2">{{ __('Sponsored') }}</p>
        <h4 class="gv-ad-banner__title">{{ $ad->title ?? __('Promoted update') }}</h4>
        <p class="gv-ad-banner__text">{{ $ad->description ?? __('Reach Gigvora profiles, pages, and groups with premium creatives styled by the design system.') }}</p>
        <div class="gv-ad-banner__meta">
            <span>{{ $ad->display_url ?? parse_url($ad->url ?? '', PHP_URL_HOST) }}</span>
            @if(!empty($ad->metrics['ctr']))
                <span>{{ __('CTR') }} {{ $ad->metrics['ctr'] }}</span>
            @endif
        </div>
    </div>
    <a href="{{ $ad->url ?? '#' }}" class="gv-btn gv-btn-gradient gv-btn-md">{{ $ad->cta ?? __('Boost now') }}</a>
</article>
