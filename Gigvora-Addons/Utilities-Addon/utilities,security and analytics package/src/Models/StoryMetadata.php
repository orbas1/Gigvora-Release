<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoryMetadata extends BaseModel
{
    protected $table = 'pro_network_story_metadata';

    protected $fillable = [
        'story_id',
        'overlays',
        'filters',
        'stickers',
        'links',
        'music_track_id',
        'live_session_id',
    ];

    protected $casts = [
        'overlays' => 'array',
        'filters' => 'array',
        'stickers' => 'array',
        'links' => 'array',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo($this->storyClass(), 'story_id');
    }

    public function musicTrack(): BelongsTo
    {
        return $this->belongsTo(MusicTrack::class, 'music_track_id');
    }

    public function liveSession(): BelongsTo
    {
        return $this->belongsTo(LiveSession::class, 'live_session_id');
    }
}
