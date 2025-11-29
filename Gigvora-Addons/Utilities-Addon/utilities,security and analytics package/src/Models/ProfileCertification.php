<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileCertification extends BaseModel
{
    protected $table = 'pro_network_profile_certifications';

    protected $fillable = [
        'user_id',
        'name',
        'authority',
        'license_number',
        'verification_url',
        'issued_at',
        'expires_at',
        'description',
    ];

    protected $casts = [
        'issued_at' => 'date',
        'expires_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
