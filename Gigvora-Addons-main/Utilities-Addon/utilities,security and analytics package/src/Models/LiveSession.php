<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LiveSession extends BaseModel
{
    protected $table = 'pro_network_live_sessions';

    protected $fillable = [
        'host_id',
        'title',
        'description',
        'status',
        'guest_user_ids',
        'likes_count',
        'donations_total',
        'chat_channel',
        'started_at',
        'ended_at',
        'recording_path',
    ];

    protected $casts = [
        'guest_user_ids' => 'array',
        'donations_total' => 'decimal:2',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function host(): BelongsTo
    {
        return $this->belongsTo($this->userClass(), 'host_id');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(LiveSessionParticipant::class, 'live_session_id');
    }

    public function storyMetadata(): HasMany
    {
        return $this->hasMany(StoryMetadata::class, 'live_session_id');
    }
}
