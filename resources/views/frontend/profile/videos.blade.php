<div class="gv-card v_video_tabs space-y-4">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <p class="gv-eyebrow mb-1">{{ get_phrase('Library') }}</p>
            <h3 class="gv-heading text-xl mb-0">{{ get_phrase('Videos & broadcasts') }}</h3>
        </div>
        <a href="{{ Route::has('liveCenter.hub') ? route('liveCenter.hub') : route('event') }}" class="gv-btn gv-btn-ghost gv-btn-sm">
            <i class="fa-solid fa-podcast"></i> {{ get_phrase('Go live') }}
        </a>
    </div>
    <div class="gv-media-rail gv-media-rail--wide" id="allVideos">
        @include('frontend.profile.video_single')
    </div>
</div>

@if(!empty($live_posts ?? []))
    <div class="gv-card space-y-3 mt-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h4 class="gv-heading text-base mb-0">{{ get_phrase('Live streams, webinars & podcasts') }}</h4>
            <a href="{{ Route::has('liveCenter.webinars') ? route('liveCenter.webinars') : route('event') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('View schedule') }}</a>
        </div>
        <div class="gv-media-rail">
            @foreach($live_posts as $live)
                <div class="gv-media-card gv-media-card--horizontal">
                    <div class="gv-media-card__body">
                        <p class="text-xs gv-muted mb-1 text-uppercase">{{ ucfirst(str_replace('_', ' ', $live->post_type)) }}</p>
                        <h4 class="text-base mb-2">{{ ellipsis(strip_tags($live->description ?? ''), 70) }}</h4>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-xs gv-muted">{{ date_formatter($live->created_at, 2) }}</span>
                            <a href="{{ route('single.post', $live->post_id) }}" class="gv-btn gv-btn-primary gv-btn-sm">{{ get_phrase('Open') }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif