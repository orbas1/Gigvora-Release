<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Webinar extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'host_id',
        'starts_at',
        'ends_at',
        'is_live',
        'is_paid',
        'price',
        'waiting_room_message',
        'stream_provider',
        'rtmp_endpoint',
        'recording_path',
        'status',
        'metadata',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'metadata' => 'array',
        'is_live' => 'boolean',
        'is_paid' => 'boolean',
        'price' => 'float',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(WebinarRegistration::class);
    }

    public function recordings(): MorphMany
    {
        return $this->morphMany(Recording::class, 'recordable');
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'host_id');
    }
}

