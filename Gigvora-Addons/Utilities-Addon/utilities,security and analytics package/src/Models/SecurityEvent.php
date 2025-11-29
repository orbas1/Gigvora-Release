<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ProNetwork\Support\Enums\SecurityEventType;

class SecurityEvent extends BaseModel
{
    protected $table = 'pro_network_security_events';

    protected $fillable = [
        'user_id',
        'type',
        'ip',
        'user_agent',
        'severity',
        'context',
    ];

    protected $casts = [
        'type' => SecurityEventType::class,
        'context' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
