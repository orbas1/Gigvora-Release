<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PodcastEpisode extends Model
{
    use HasFactory;

    protected $fillable = [
        'podcast_series_id',
        'title',
        'description',
        'scheduled_for',
        'published_at',
        'audio_path',
        'duration',
        'metadata',
        'is_public',
    ];

    protected $casts = [
        'metadata' => 'array',
        'scheduled_for' => 'datetime',
        'published_at' => 'datetime',
        'is_public' => 'boolean',
    ];

    public function series(): BelongsTo
    {
        return $this->belongsTo(PodcastSeries::class, 'podcast_series_id');
    }

    public function recordings(): MorphMany
    {
        return $this->morphMany(Recording::class, 'recordable');
    }
}

