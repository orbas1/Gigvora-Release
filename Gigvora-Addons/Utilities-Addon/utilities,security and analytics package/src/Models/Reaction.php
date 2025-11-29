<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use ProNetwork\Support\Enums\ReactionType;

class Reaction extends BaseModel
{
    protected $table = 'pro_network_reactions';

    protected $fillable = [
        'reactable_id',
        'reactable_type',
        'user_id',
        'type',
        'weight',
    ];

    protected $casts = [
        'type' => ReactionType::class,
    ];

    public function reactable()
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
