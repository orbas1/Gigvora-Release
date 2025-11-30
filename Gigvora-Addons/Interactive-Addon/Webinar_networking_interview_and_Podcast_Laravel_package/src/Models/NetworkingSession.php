<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Carbon\Carbon;
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

    public function getEndsAtAttribute(): ?Carbon
    {
        if (! $this->starts_at || ! $this->duration_seconds) {
            return null;
        }

        return $this->starts_at->copy()->addSeconds($this->duration_seconds);
    }

    public function getRotationCountAttribute(): ?int
    {
        if (! $this->duration_seconds || ! $this->rotation_interval) {
            return null;
        }

        return (int) floor($this->duration_seconds / max($this->rotation_interval, 1));
    }

    public function getIsJoinableAttribute(): bool
    {
        if (! $this->starts_at) {
            return false;
        }

        if (in_array($this->status, ['in_rotation', 'live', 'open'], true)) {
            return true;
        }

        return Carbon::now()->greaterThanOrEqualTo($this->starts_at);
    }

    public function getIsLiveAttribute(): bool
    {
        return in_array($this->status, ['in_rotation', 'live'], true);
    }

    public function participants(): HasMany
    {
        return $this->hasMany(NetworkingParticipant::class);
    }

    public function host(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'host_id');
    }
}

