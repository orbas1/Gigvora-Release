<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InviteContribution extends BaseModel
{
    protected $table = 'pro_network_invite_contributions';

    protected $fillable = [
        'inviter_id',
        'invitee_id',
        'post_id',
        'role',
        'status',
        'message',
    ];

    public function inviter(): BelongsTo
    {
        return $this->belongsTo($this->userClass(), 'inviter_id');
    }

    public function invitee(): BelongsTo
    {
        return $this->belongsTo($this->userClass(), 'invitee_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo($this->postClass(), 'post_id');
    }
}
