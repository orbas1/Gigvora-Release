<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PodcastEpisodeEntitlement extends Model
{
    use HasFactory;

    protected $fillable = [
        'podcast_episode_id',
        'user_id',
        'entitlement_type',
        'source',
        'granted_at',
        'expires_at',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'granted_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function episode(): BelongsTo
    {
        return $this->belongsTo(PodcastEpisode::class, 'podcast_episode_id');
    }
}

