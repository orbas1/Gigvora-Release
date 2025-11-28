<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'host_id',
        'scheduled_at',
        'duration_minutes',
        'is_panel',
        'location',
        'metadata',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'metadata' => 'array',
        'is_panel' => 'boolean',
    ];

    public function slots(): HasMany
    {
        return $this->hasMany(InterviewSlot::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(InterviewScore::class);
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'host_id');
    }
}

