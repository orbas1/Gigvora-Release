<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgeVerification extends BaseModel
{
    protected $table = 'pro_network_age_verifications';

    protected $fillable = [
        'user_id',
        'status',
        'provider',
        'provider_reference',
        'verified_at',
        'rejected_at',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'verified_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }

    public function logs(): HasMany
    {
        return $this->hasMany(AgeVerificationLog::class, 'age_verification_id');
    }
}
