<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\AiWorkspace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiUserSubscription extends Model
{
    protected $fillable = [
        'user_id',
        'ai_subscription_plan_id',
        'renews_at',
        'status',
    ];

    protected $casts = [
        'renews_at' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\\Models\\User');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(AiSubscriptionPlan::class, 'ai_subscription_plan_id');
    }
}
