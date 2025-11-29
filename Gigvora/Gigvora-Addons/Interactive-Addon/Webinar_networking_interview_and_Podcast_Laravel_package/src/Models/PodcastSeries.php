<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class PodcastSeries extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'host_id',
        'cover_art_path',
        'is_public',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_public' => 'boolean',
    ];

    public function episodes(): HasMany
    {
        return $this->hasMany(PodcastEpisode::class);
    }

    public function recordings(): MorphMany
    {
        return $this->morphMany(Recording::class, 'recordable');
    }
}

