<?php

namespace ProNetwork\Models;

class ReactionAggregate extends BaseModel
{
    protected $table = 'pro_network_reaction_aggregates';

    protected $fillable = [
        'reactable_id',
        'reactable_type',
        'counts',
        'dislikes',
    ];

    protected $casts = [
        'counts' => 'array',
    ];

    public function reactable()
    {
        return $this->morphTo();
    }
}
