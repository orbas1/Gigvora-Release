<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileBackgroundCheck extends BaseModel
{
    protected $table = 'pro_network_profile_background_checks';

    protected $fillable = [
        'user_id',
        'status',
        'provider',
        'reference',
        'checked_at',
        'expires_at',
        'notes',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
