<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketplaceDisputeMessage extends BaseModel
{
    protected $table = 'pro_network_marketplace_dispute_messages';

    protected $fillable = [
        'dispute_id',
        'user_id',
        'message',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    public function dispute(): BelongsTo
    {
        return $this->belongsTo(MarketplaceDispute::class, 'dispute_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
