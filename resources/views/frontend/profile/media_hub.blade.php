@php
    $collections = $media_collections ?? [];
    $reels = $collections['reels'] ?? collect();
    $photos = $collections['photos'] ?? collect();
    $videos = $collections['videos'] ?? collect();
    $livePosts = $collections['live'] ?? collect();
    $interactivePosts = $collections['interactive'] ?? collect();
@endphp

<div class="gv-media-hub space-y-5">
    <section class="gv-card space-y-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <p class="gv-eyebrow mb-1">{{ get_phrase('Spotlight') }}</p>
                <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Reels & short clips') }}</h3>
            </div>
            <a href="{{ route('profile.photos') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Open Photos & Reels') }}</a>
        </div>
        <div class="gv-media-rail">
            @forelse ($reels as $reel)
                <div class="gv-media-card gv-media-card--vertical">
                    <div class="gv-media-card__media">
                        <video muted loop playsinline src="{{ get_post_video($reel->file_name) }}"></video>
                        <button class="gv-media-card__cta" onclick="$(location).prop('href', '{{ route('single.post', $reel->post_id) }}')">
                            <i class="fa-solid fa-play"></i>
                        </button>
                    </div>
                    <div class="gv-media-card__meta">
                        <p class="text-xs gv-muted mb-1">{{ get_phrase('Reel') }}</p>
                        <span class="text-sm">{{ date_formatter($reel->created_at, 2) }}</span>
                    </div>
                </div>
            @empty
                <p class="gv-muted mb-0">{{ get_phrase('No reels yet. Capture your first story-style clip!') }}</p>
            @endforelse
        </div>
    </section>

    <section class="gv-card space-y-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <p class="gv-eyebrow mb-1">{{ get_phrase('Gallery') }}</p>
                <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Photos & reels grid') }}</h3>
            </div>
            <a href="{{ route('profile.photos') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Manage albums') }}</a>
        </div>
        <div class="gv-media-grid">
            @forelse ($photos as $photo)
                <a class="gv-media-grid__item" style="background-image: url('{{ get_post_image($photo->file_name) }}')" href="{{ route('single.post', $photo->post_id) }}">
                    <span class="sr-only">{{ get_phrase('View photo') }}</span>
                </a>
            @empty
                <p class="gv-muted mb-0">{{ get_phrase('No photos yet. Start sharing your work!') }}</p>
            @endforelse
        </div>
    </section>

    <section class="gv-card space-y-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <p class="gv-eyebrow mb-1">{{ get_phrase('Long-form') }}</p>
                <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Videos & shows') }}</h3>
            </div>
            <a href="{{ route('profile.videos') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('View video library') }}</a>
        </div>
        <div class="gv-media-rail gv-media-rail--wide">
            @forelse ($videos as $video)
                @php
                    $duration = $video->duration_seconds ? gmdate('i:s', $video->duration_seconds) : null;
                @endphp
                <div class="gv-media-card gv-media-card--horizontal">
                    <div class="gv-media-card__thumb">
                        <video muted playsinline src="{{ get_post_video($video->file_name) }}"></video>
                        @if ($duration)
                            <span class="gv-media-card__badge">{{ $duration }}</span>
                        @endif
                    </div>
                    <div class="gv-media-card__body">
                        <p class="text-sm gv-muted mb-1">{{ get_phrase('Video') }}</p>
                        <h4 class="text-base mb-2">{{ get_phrase('Watch now') }}</h4>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-xs gv-muted">{{ date_formatter($video->created_at, 2) }}</span>
                            <a href="{{ route('single.post', $video->post_id) }}" class="gv-btn gv-btn-primary gv-btn-sm">{{ get_phrase('Play') }}</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="gv-muted mb-0">{{ get_phrase('No long-form videos yet. Upload your first show!') }}</p>
            @endforelse
        </div>
    </section>

    <section class="gv-card space-y-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <p class="gv-eyebrow mb-1">{{ get_phrase('Live & interactive') }}</p>
                <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Live streams & webinars') }}</h3>
            </div>
            <a href="{{ Route::has('liveCenter.hub') ? route('liveCenter.hub') : route('event') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('Go to Live Center') }}</a>
        </div>
        <div class="gv-media-rail">
            @forelse ($livePosts as $live)
                @php
                    $meta = [];
                    if ($live->post_type === 'live_streaming') {
                        $meta = json_decode($live->description, true) ?? [];
                    }
                @endphp
                <div class="gv-media-card gv-media-card--live">
                    <div class="gv-media-card__meta">
                        <span class="gv-pill gv-pill--success">{{ get_phrase('Live') }}</span>
                        <p class="text-sm mb-1">{{ $meta['live_title'] ?? get_phrase('Live session') }}</p>
                        <span class="text-xs gv-muted">{{ date_formatter($live->created_at, 2) }}</span>
                    </div>
                    <a href="{{ route('single.post', $live->post_id) }}" class="gv-btn gv-btn-primary gv-btn-sm">{{ get_phrase('Open stream') }}</a>
                </div>
            @empty
                <p class="gv-muted mb-0">{{ get_phrase('No live sessions yet. Schedule one from the Live Center!') }}</p>
            @endforelse
        </div>
    </section>

    <section class="gv-card space-y-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <p class="gv-eyebrow mb-1">{{ get_phrase('Webinars & podcasts') }}</p>
                <h3 class="gv-heading text-lg mb-0">{{ get_phrase('Interactive catalog') }}</h3>
            </div>
            <a href="{{ Route::has('liveCenter.webinars') ? route('liveCenter.webinars') : route('event') }}" class="gv-btn gv-btn-ghost gv-btn-sm">{{ get_phrase('See schedule') }}</a>
        </div>
        <div class="gv-media-rail">
            @forelse ($interactivePosts as $interactive)
                <div class="gv-media-card gv-media-card--horizontal">
                    <div class="gv-media-card__body">
                        <p class="text-xs gv-muted mb-1 text-uppercase">{{ ucfirst(str_replace('_', ' ', $interactive->post_type)) }}</p>
                        <h4 class="text-base mb-2">{{ ellipsis(strip_tags($interactive->description ?? ''), 60) }}</h4>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-xs gv-muted">{{ date_formatter($interactive->created_at, 2) }}</span>
                            <a href="{{ route('single.post', $interactive->post_id) }}" class="gv-btn gv-btn-primary gv-btn-sm">{{ get_phrase('Open') }}</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="gv-muted mb-0">{{ get_phrase('No webinars or podcasts yet. Share an upcoming session!') }}</p>
            @endforelse
        </div>
    </section>
</div>

