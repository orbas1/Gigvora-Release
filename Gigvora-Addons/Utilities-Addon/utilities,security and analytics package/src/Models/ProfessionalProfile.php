<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfessionalProfile extends BaseModel
{
    protected $table = 'pro_network_professional_profiles';

    protected $fillable = [
        'user_id',
        'headline',
        'tagline',
        'location',
        'top_skills',
        'available_for_work',
        'public_url',
        'share_hash',
        'connections_count',
        'activity_summary',
        'interests',
        'visibility',
    ];

    protected $casts = [
        'top_skills' => 'array',
        'available_for_work' => 'boolean',
        'interests' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }

    public function skills(): HasMany
    {
        return $this->hasMany(ProfileSkill::class, 'user_id', 'user_id');
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(ProfileCertification::class, 'user_id', 'user_id');
    }

    public function workHistories(): HasMany
    {
        return $this->hasMany(ProfileWorkHistory::class, 'user_id', 'user_id');
    }

    public function educationHistories(): HasMany
    {
        return $this->hasMany(ProfileEducationHistory::class, 'user_id', 'user_id');
    }

    public function references(): HasMany
    {
        return $this->hasMany(ProfileReference::class, 'user_id', 'user_id');
    }

    public function backgroundChecks(): HasMany
    {
        return $this->hasMany(ProfileBackgroundCheck::class, 'user_id', 'user_id');
    }

    public function interests(): HasMany
    {
        return $this->hasMany(ProfileInterest::class, 'user_id', 'user_id');
    }

    public function opportunities(): HasMany
    {
        return $this->hasMany(ProfileOpportunity::class, 'user_id', 'user_id');
    }
}
