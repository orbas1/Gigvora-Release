<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileReference extends BaseModel
{
    protected $table = 'pro_network_profile_references';

    protected $fillable = [
        'user_id',
        'reference_user_id',
        'name',
        'relationship',
        'contact_email',
        'contact_phone',
        'statement',
        'verified_at',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }

    public function referenceUser(): BelongsTo
    {
        return $this->belongsTo($this->userClass(), 'reference_user_id');
    }
}
