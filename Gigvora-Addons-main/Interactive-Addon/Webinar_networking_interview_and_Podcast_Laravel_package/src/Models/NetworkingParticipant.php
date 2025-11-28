<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NetworkingParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'networking_session_id',
        'user_id',
        'current_partner_id',
        'rotation_position',
        'joined_at',
        'status',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(NetworkingSession::class, 'networking_session_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function currentPartner(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'current_partner_id');
    }
}

