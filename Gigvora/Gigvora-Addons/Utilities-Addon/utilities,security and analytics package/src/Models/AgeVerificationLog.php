<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgeVerificationLog extends BaseModel
{
    protected $table = 'pro_network_age_verification_logs';

    protected $fillable = [
        'age_verification_id',
        'event',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function verification(): BelongsTo
    {
        return $this->belongsTo(AgeVerification::class, 'age_verification_id');
    }
}
