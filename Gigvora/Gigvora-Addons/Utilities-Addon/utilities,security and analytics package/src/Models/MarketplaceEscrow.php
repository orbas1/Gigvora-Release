<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketplaceEscrow extends BaseModel
{
    protected $table = 'pro_network_marketplace_escrows';

    protected $fillable = [
        'order_id',
        'status',
        'amount',
        'currency',
        'delivery_method',
        'delivery_notes',
        'escrow_reference',
        'held_at',
        'released_at',
        'refunded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'held_at' => 'datetime',
        'released_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo($this->orderClass(), 'order_id');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(MarketplaceMilestone::class, 'escrow_id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(MarketplaceTransaction::class, 'escrow_id');
    }

    public function disputes(): HasMany
    {
        return $this->hasMany(MarketplaceDispute::class, 'escrow_id');
    }
}
