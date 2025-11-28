<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class AccountType extends BaseModel
{
    protected $table = 'pro_network_account_types';

    protected $fillable = [
        'slug',
        'name',
        'description',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(UserAccountType::class, 'account_type_id');
    }
}
