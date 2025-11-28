<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NetworkingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'host_id',
        'duration_seconds',
        'rotation_interval',
        'starts_at',
        'is_paid',
        'price',
        'status',
        'metadata',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'metadata' => 'array',
        'is_paid' => 'boolean',
        'price' => 'float',
    ];

    public function participants(): HasMany
    {
        return $this->hasMany(NetworkingParticipant::class);
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'host_id');
    }
}

