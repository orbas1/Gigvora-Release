<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileInterest extends BaseModel
{
    protected $table = 'pro_network_profile_interests';

    protected $fillable = [
        'user_id',
        'interest',
        'weight',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
