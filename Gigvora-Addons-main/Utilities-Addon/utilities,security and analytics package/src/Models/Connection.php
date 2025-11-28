<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Connection extends BaseModel
{
    protected $table = 'pro_network_connection_caches';

    protected $fillable = [
        'user_id',
        'connection_id',
        'degree',
        'connection_path',
        'mutual_count',
        'strength',
        'calculated_at',
    ];

    protected $casts = [
        'connection_path' => 'array',
        'calculated_at' => 'datetime',
    ];

    public function scopeDirect($query)
    {
        return $query->where('degree', 1);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }

    public function connection(): BelongsTo
    {
        return $this->belongsTo($this->userClass(), 'connection_id');
    }
}
