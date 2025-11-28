<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\AiWorkspace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiUsageAggregate extends Model
{
    protected $fillable = [
        'user_id',
        'period_start',
        'period_end',
        'tokens_used',
        'sessions_count',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'tokens_used' => 'integer',
        'sessions_count' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\\Models\\User');
    }
}
