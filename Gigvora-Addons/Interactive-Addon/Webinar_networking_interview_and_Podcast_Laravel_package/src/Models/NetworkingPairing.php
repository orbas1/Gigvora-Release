<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NetworkingPairing extends Model
{
    use HasFactory;

    protected $fillable = [
        'networking_session_id',
        'participant_id',
        'partner_id',
        'round',
        'group_key',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function participant(): BelongsTo
    {
        return $this->belongsTo(NetworkingParticipant::class, 'participant_id');
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(NetworkingParticipant::class, 'partner_id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(NetworkingSession::class, 'networking_session_id');
    }
}
