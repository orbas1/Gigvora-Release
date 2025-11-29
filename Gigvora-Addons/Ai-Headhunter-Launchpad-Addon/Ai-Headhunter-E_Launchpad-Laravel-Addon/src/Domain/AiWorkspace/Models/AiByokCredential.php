<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\AiWorkspace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiByokCredential extends Model
{
    protected $fillable = [
        'user_id',
        'provider',
        'api_key',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\\Models\\User');
    }
}
