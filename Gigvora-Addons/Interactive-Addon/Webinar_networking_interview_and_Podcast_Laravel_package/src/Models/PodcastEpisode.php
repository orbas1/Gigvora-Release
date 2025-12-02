<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Jobi\WebinarNetworkingInterviewPodcast\Models\PodcastEpisodeTranscript;
use Jobi\WebinarNetworkingInterviewPodcast\Models\PodcastEpisodeHighlight;
use Jobi\WebinarNetworkingInterviewPodcast\Models\PodcastEpisodeEntitlement;

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
        'is_paid',
        'entitlement_type',
        'price_cents',
        'donation_suggested_cents',
    ];

    protected $casts = [
        'metadata' => 'array',
        'scheduled_for' => 'datetime',
        'published_at' => 'datetime',
        'is_public' => 'boolean',
        'is_paid' => 'boolean',
    ];

    public function series(): BelongsTo
    {
        return $this->belongsTo(PodcastSeries::class, 'podcast_series_id');
    }

    public function recordings(): MorphMany
    {
        return $this->morphMany(Recording::class, 'recordable');
    }

    public function transcripts(): HasMany
    {
        return $this->hasMany(PodcastEpisodeTranscript::class, 'podcast_episode_id');
    }

    public function highlights(): HasMany
    {
        return $this->hasMany(PodcastEpisodeHighlight::class, 'podcast_episode_id');
    }

    public function entitlements(): HasMany
    {
        return $this->hasMany(PodcastEpisodeEntitlement::class, 'podcast_episode_id');
    }

    public function isAccessibleTo(?Model $user): bool
    {
        if (!$this->is_paid) {
            return true;
        }

        if ($user === null) {
            return false;
        }

        return $this->entitlements()
            ->where('user_id', $user->getAuthIdentifier())
            ->where(function ($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->exists();
    }
}

