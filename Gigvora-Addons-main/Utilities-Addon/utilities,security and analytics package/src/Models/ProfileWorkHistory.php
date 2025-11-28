<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileWorkHistory extends BaseModel
{
    protected $table = 'pro_network_profile_work_histories';

    protected $fillable = [
        'user_id',
        'title',
        'company_name',
        'employment_type',
        'location',
        'started_at',
        'ended_at',
        'is_current',
        'description',
    ];

    protected $casts = [
        'started_at' => 'date',
        'ended_at' => 'date',
        'is_current' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
