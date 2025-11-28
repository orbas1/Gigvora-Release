<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsEvent extends BaseModel
{
    protected $table = 'pro_network_analytics_events';

    protected $fillable = [
        'event',
        'user_id',
        'properties',
        'ip',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
