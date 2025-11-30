@foreach($all_videos as $video)
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
            <p class="text-xs gv-muted mb-1">{{ get_phrase('Video') }}</p>
            <h4 class="text-base mb-2">{{ get_phrase('Watch now') }}</h4>
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-xs gv-muted">{{ date_formatter($video->created_at, 2) }}</span>
                <div class="d-flex gap-2">
                    <a href="{{ route('single.post', $video->post_id) }}" class="gv-btn gv-btn-primary gv-btn-sm">
                        <i class="fa-solid fa-play"></i>
                    </a>
                    <button class="gv-btn gv-btn-ghost gv-btn-sm" onclick="confirmAction('<?php echo route('delete.mediafile', $video->id); ?>', true)">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach
