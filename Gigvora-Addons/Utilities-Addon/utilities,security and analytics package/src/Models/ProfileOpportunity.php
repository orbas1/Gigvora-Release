<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileOpportunity extends BaseModel
{
    protected $table = 'pro_network_profile_opportunities';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'description',
        'rate',
        'currency',
        'status',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
