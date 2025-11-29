<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HashtagAssignment extends BaseModel
{
    protected $table = 'pro_network_hashtaggables';

    protected $fillable = [
        'hashtag_id',
        'hashtaggable_id',
        'hashtaggable_type',
    ];

    public function hashtag(): BelongsTo
    {
        return $this->belongsTo(Hashtag::class);
    }

    public function hashtaggable()
    {
        return $this->morphTo();
    }
}
