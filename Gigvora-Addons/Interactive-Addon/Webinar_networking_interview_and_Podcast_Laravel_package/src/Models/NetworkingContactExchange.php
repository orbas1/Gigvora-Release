<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NetworkingContactExchange extends Model
{
    use HasFactory;

    protected $fillable = [
        'networking_session_id',
        'user_id',
        'partner_id',
        'starred',
        'follow_up_at',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'starred' => 'boolean',
        'follow_up_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(NetworkingSession::class, 'networking_session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'partner_id');
    }
}

