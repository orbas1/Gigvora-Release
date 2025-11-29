<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketplaceDispute extends BaseModel
{
    protected $table = 'pro_network_marketplace_disputes';

    protected $fillable = [
        'escrow_id',
        'raised_by',
        'reason',
        'status',
        'resolution_notes',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function escrow(): BelongsTo
    {
        return $this->belongsTo(MarketplaceEscrow::class, 'escrow_id');
    }

    public function raiser(): BelongsTo
    {
        return $this->belongsTo($this->userClass(), 'raised_by');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo($this->userClass(), 'resolved_by');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(MarketplaceDisputeMessage::class, 'dispute_id');
    }
}
