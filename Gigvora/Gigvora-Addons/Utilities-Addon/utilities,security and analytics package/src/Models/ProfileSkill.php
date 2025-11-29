<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileSkill extends BaseModel
{
    protected $table = 'pro_network_profile_skills';

    protected $fillable = [
        'user_id',
        'name',
        'proficiency',
        'is_top_five',
        'weight',
    ];

    protected $casts = [
        'is_top_five' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
