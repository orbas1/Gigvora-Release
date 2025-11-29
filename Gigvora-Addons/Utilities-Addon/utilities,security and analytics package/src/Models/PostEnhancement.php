<?php

namespace ProNetwork\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostEnhancement extends BaseModel
{
    protected $table = 'pro_network_post_enhancements';

    protected $fillable = [
        'post_id',
        'type',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo($this->postClass(), 'post_id');
    }
}
