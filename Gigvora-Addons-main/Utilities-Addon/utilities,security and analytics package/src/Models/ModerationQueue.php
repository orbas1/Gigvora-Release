<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModerationQueue extends BaseModel
{
    protected $table = 'pro_network_moderation_queue';

    protected $fillable = [
        'moderatable_id',
        'moderatable_type',
        'reason',
        'status',
        'flags',
        'actioned_by',
        'resolved_at',
        'notes',
    ];

    protected $casts = [
        'flags' => 'array',
        'resolved_at' => 'datetime',
    ];

    public function moderatable()
    {
        return $this->morphTo();
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo($this->userClass(), 'actioned_by');
    }
}
