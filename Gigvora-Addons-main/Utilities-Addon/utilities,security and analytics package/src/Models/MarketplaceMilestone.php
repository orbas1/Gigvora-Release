<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketplaceMilestone extends BaseModel
{
    protected $table = 'pro_network_marketplace_milestones';

    protected $fillable = [
        'escrow_id',
        'title',
        'amount',
        'status',
        'due_at',
        'released_at',
        'refunded_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_at' => 'datetime',
        'released_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    public function escrow(): BelongsTo
    {
        return $this->belongsTo(MarketplaceEscrow::class, 'escrow_id');
    }
}
