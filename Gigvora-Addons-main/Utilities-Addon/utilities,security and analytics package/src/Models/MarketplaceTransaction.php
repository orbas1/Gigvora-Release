<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketplaceTransaction extends BaseModel
{
    protected $table = 'pro_network_marketplace_transactions';

    protected $fillable = [
        'escrow_id',
        'user_id',
        'type',
        'amount',
        'currency',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function escrow(): BelongsTo
    {
        return $this->belongsTo(MarketplaceEscrow::class, 'escrow_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
