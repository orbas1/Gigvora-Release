<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileEducationHistory extends BaseModel
{
    protected $table = 'pro_network_profile_education_histories';

    protected $fillable = [
        'user_id',
        'institution',
        'degree',
        'field',
        'started_at',
        'ended_at',
        'description',
    ];

    protected $casts = [
        'started_at' => 'date',
        'ended_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
