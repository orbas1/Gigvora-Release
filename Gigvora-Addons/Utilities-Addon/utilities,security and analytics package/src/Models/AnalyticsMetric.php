<?php

namespace ProNetwork\Models;

class AnalyticsMetric extends BaseModel
{
    protected $table = 'pro_network_analytics_metrics';

    protected $fillable = [
        'entity_type',
        'entity_id',
        'metric',
        'value',
        'meta',
    ];

    protected $casts = [
        'value' => 'integer',
        'meta' => 'array',
    ];
}
