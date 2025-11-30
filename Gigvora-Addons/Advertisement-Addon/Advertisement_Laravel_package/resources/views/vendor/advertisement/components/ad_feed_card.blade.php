@php
    $isVideo = ($ad->type ?? '') === 'video';
    $mediaUrl = $ad->media_url ?? null;
@endphp
<section class="gv-ad-card" data-ad-id="{{ $ad->id ?? '' }}" aria-label="{{ __('Sponsored feed card') }}">
    <div class="gv-ad-card__media {{ $isVideo ? 'gv-ad-card__media--video' : '' }}">
        @if($mediaUrl && $isVideo)
            <video src="{{ $mediaUrl }}" class="gv-ad-card__video" playsinline loop muted autoplay></video>
        @elseif($mediaUrl)
            <img src="{{ $mediaUrl }}" class="gv-ad-card__image" alt="{{ $ad->title ?? __('Sponsored asset') }}">
        @else
            <div class="gv-ad-card__media-fallback" aria-hidden="true">
                <span>AD</span>
            </div>
        @endif
    </div>
    <div class="gv-ad-card__body">
        <div class="gv-ad-card__eyebrow">
            <span class="gv-ad-chip">{{ __('Sponsored') }}</span>
            <span class="gv-ad-chip gv-ad-chip--muted">{{ ucfirst($ad->status ?? 'active') }}</span>
        </div>
        <div class="gv-ad-card__headline">
            <div>
                <h3 class="gv-ad-card__title">{{ $ad->title ?? __('Promoted story') }}</h3>
                <p class="gv-ad-card__summary">{{ $ad->description ?? __('Promote your launch, job, or event across Gigvora feed, reels, and live overlays.') }}</p>
            </div>
            @if(!empty($ad->metrics))
                <dl class="gv-ad-card__metrics">
                    @if(!empty($ad->metrics['ctr']))
                        <div>
                            <dt>CTR</dt>
                            <dd>{{ $ad->metrics['ctr'] }}</dd>
                        </div>
                    @endif
                    @if(!empty($ad->metrics['cvr']))
                        <div>
                            <dt>CVR</dt>
                            <dd>{{ $ad->metrics['cvr'] }}</dd>
                        </div>
                    @endif
                </dl>
            @endif
        </div>
        <div class="gv-ad-card__footer">
            <div class="gv-ad-card__context">
                <span class="gv-ad-card__url">{{ $ad->display_url ?? parse_url($ad->url ?? '', PHP_URL_HOST) }}</span>
                <span class="gv-ad-card__placement">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $ad->placement ?? 'feed')) }}</span>
            </div>
            <a href="{{ $ad->url ?? '#' }}" class="gv-btn gv-btn-primary gv-btn-sm">{{ $ad->cta ?? __('Learn more') }}</a>
        </div>
    </div>
</section>
