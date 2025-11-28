<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAccountType extends BaseModel
{
    protected $table = 'pro_network_user_account_types';

    protected $fillable = [
        'user_id',
        'account_type_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }

    public function accountType(): BelongsTo
    {
        return $this->belongsTo(AccountType::class, 'account_type_id');
    }
}
