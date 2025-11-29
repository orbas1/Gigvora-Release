<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NetworkMetric extends BaseModel
{
    protected $table = 'pro_network_network_metrics';

    protected $fillable = [
        'user_id',
        'first_degree_count',
        'second_degree_count',
        'third_degree_count',
        'mutual_count',
        'suggestions',
        'calculated_at',
    ];

    protected $casts = [
        'suggestions' => 'array',
        'calculated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
