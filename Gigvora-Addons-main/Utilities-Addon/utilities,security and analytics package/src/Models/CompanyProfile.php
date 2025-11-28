<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyProfile extends BaseModel
{
    protected $table = 'pro_network_company_profiles';

    protected $fillable = [
        'page_id',
        'headline',
        'industry',
        'location',
        'website',
        'metadata',
        'employee_count',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo($this->pageClass(), 'page_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(CompanyEmployee::class);
    }
}
