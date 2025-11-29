<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyEmployee extends BaseModel
{
    protected $table = 'pro_network_company_employees';

    protected $fillable = [
        'company_profile_id',
        'user_id',
        'role_title',
        'started_at',
        'ended_at',
        'is_current',
    ];

    protected $casts = [
        'started_at' => 'date',
        'ended_at' => 'date',
        'is_current' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class, 'company_profile_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
