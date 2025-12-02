<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PodcastEpisodeTranscript extends Model
{
    use HasFactory;

    protected $fillable = [
        'podcast_episode_id',
        'language',
        'source',
        'content',
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

