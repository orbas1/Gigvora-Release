<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFeatureFlag extends BaseModel
{
    protected $table = 'pro_network_user_feature_flags';

    protected $fillable = [
        'user_id',
        'feature',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
