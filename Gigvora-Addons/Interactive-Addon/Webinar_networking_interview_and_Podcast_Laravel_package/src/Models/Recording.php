<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Recording extends Model
{
    protected $fillable = [
        'recordable_id',
        'recordable_type',
        'path',
        'duration',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function recordable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}

