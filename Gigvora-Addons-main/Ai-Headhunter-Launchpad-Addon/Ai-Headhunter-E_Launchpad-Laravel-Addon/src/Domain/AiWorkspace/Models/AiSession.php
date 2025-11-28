<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\AiWorkspace\Models;

use Gigvora\TalentAi\Domain\Shared\Enums\AiSessionStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiSession extends Model
{
    protected $fillable = [
        'user_id',
        'tool',
        'status',
        'prompt_tokens',
        'completion_tokens',
        'credit_cost',
        'input',
        'output',
    ];

    protected $casts = [
        'status' => AiSessionStatus::class,
        'prompt_tokens' => 'integer',
        'completion_tokens' => 'integer',
        'credit_cost' => 'integer',
        'input' => 'array',
        'output' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\\Models\\User');
    }
}
