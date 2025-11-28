<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\AiWorkspace\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiSubscriptionPlan extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'limits',
        'price',
    ];

    protected $casts = [
        'limits' => 'array',
        'price' => 'decimal:2',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(AiUserSubscription::class);
    }
}
