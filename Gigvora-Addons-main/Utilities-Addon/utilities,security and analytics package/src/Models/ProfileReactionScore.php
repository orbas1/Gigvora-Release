<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileReactionScore extends BaseModel
{
    protected $table = 'pro_network_profile_reaction_scores';

    protected $fillable = [
        'user_id',
        'like_score',
        'dislike_count',
        'reaction_breakdown',
    ];

    protected $casts = [
        'reaction_breakdown' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo($this->userClass());
    }
}
