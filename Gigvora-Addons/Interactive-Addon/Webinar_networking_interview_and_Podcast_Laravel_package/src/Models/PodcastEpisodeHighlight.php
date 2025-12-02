<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PodcastEpisodeHighlight extends Model
{
    use HasFactory;

    protected $fillable = [
        'podcast_episode_id',
        'title',
        'description',
        'starts_at_seconds',
        'ends_at_seconds',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function episode(): BelongsTo
    {
        return $this->belongsTo(PodcastEpisode::class, 'podcast_episode_id');
    }
}

