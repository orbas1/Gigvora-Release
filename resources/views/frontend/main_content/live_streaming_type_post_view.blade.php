@php
    $stream = \App\Models\Live_streamings::where('publisher', $post->publisher ?? 'post')
        ->where('publisher_id', $post->post_id)
        ->first();
    $liveSummary = $stream ? app(\App\Services\LiveEngagementService::class)->summary($stream) : [
        'goal' => 0,
        'total' => 0,
        'progress' => 0,
        'leaderboard' => [],
        'viewer_goal' => 0,
        'viewer_count' => 0,
        'viewer_peak' => 0,
    ];
@endphp

<div class="gv-live-card" data-live-post="{{ $post->post_id }}">
    <div class="gv-live-card__header">
        <div>
            <p class="gv-pill gv-pill--danger mb-2">
                <span class="live-dot"></span> {{ get_phrase('Live now') }}
            </p>
            <h4>{{ get_phrase('Gigvora Live session') }}</h4>
            <p class="gv-muted">{{ get_phrase('Donations, polls, chat & utilities in one shell.') }}</p>
        </div>
        <img class="live-image" src="{{ asset('storage/images/live.png') }}" alt="">
    </div>

    <div class="gv-live-card__metrics">
        <div>
            <p class="gv-label mb-1">{{ get_phrase('Donation goal') }}</p>
            <div class="gv-progress">
                <span style="width: {{ $liveSummary['progress'] ?? 0 }}%"></span>
            </div>
            <small class="gv-muted">
                £{{ number_format($liveSummary['total'], 2) }}
                {{ get_phrase('of') }}
                £{{ number_format($liveSummary['goal'] ?? 0, 2) }}
            </small>
        </div>
        <div class="gv-live-card__stat">
            <p>{{ get_phrase('Viewers now') }}</p>
            <h3>{{ $liveSummary['viewer_count'] ?? 0 }}</h3>
            <small>{{ get_phrase('Goal') }}: {{ $liveSummary['viewer_goal'] ?? 0 }}</small>
        </div>
        <div class="gv-live-card__stat">
            <p>{{ get_phrase('Peak') }}</p>
            <h3>{{ $liveSummary['viewer_peak'] ?? 0 }}</h3>
            <small>{{ get_phrase('all time') }}</small>
        </div>
    </div>

    @if (!empty($liveSummary['leaderboard']))
        <div class="gv-live-card__leaderboard">
            <p class="gv-label mb-2">{{ get_phrase('Top supporters') }}</p>
            <ul>
                @foreach ($liveSummary['leaderboard'] as $leader)
                    <li>
                        <strong>{{ $leader['name'] }}</strong>
                        <span>£{{ number_format($leader['amount'], 2) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @php($liveAd = config('advertisement.enabled') ? app(\App\Services\AdvertisementSurfaceService::class)->forSlot('live_overlay') : null)
    @includeWhen($liveAd, 'advertisement::components.ad_feed_card', ['ad' => $liveAd])

    <div class="gv-feed-item-cta mt-3">
        <a class="gv-btn gv-btn-primary" href="{{ route('go.live', $post->post_id) }}">
            <i class="fa fa-video"></i> {{ get_phrase('Join live shell') }}
        </a>
        <a class="gv-btn gv-btn-ghost" href="{{ route('go.live', $post->post_id) }}#support">
            <i class="fa-solid fa-gift"></i> {{ get_phrase('Send support') }}
        </a>
    </div>
</div>

