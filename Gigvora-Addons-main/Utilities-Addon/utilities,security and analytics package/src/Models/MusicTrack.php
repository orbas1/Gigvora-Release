<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class MusicTrack extends BaseModel
{
    protected $table = 'pro_network_music_tracks';

    protected $fillable = [
        'title',
        'artist',
        'duration_seconds',
        'license',
        'storage_disk',
        'storage_path',
        'genre',
        'mood',
    ];

    public function stories(): HasMany
    {
        return $this->hasMany(StoryMetadata::class, 'music_track_id');
    }
}
