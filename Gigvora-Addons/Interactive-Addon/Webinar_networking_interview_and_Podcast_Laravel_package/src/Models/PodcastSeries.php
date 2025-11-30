<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Config;

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

    public function followers(): BelongsToMany
    {
        $userModel = Config::get('auth.providers.users.model') ?? \App\Models\User::class;

        return $this->belongsToMany($userModel, 'podcast_series_followers')
            ->withTimestamps();
    }
}

