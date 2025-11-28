<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutualConnection extends BaseModel
{
    protected $table = 'pro_network_mutual_connections';

    protected $fillable = [
        'user_id',
        'target_user_id',
        'mutual_user_ids',
        'mutual_count',
        'calculated_at',
    ];

    protected $casts = [
        'mutual_user_ids' => 'array',
        'calculated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }

    public function target(): BelongsTo
    {
        return $this->belongsTo($this->userClass(), 'target_user_id');
    }
}
