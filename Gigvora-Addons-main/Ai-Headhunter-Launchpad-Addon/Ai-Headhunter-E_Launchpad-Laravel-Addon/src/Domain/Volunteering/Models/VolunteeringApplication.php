<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Volunteering\Models;

use Gigvora\TalentAi\Domain\Shared\Enums\VolunteeringApplicationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolunteeringApplication extends Model
{
    protected $fillable = [
        'volunteering_opportunity_id',
        'user_id',
        'status',
        'motivation',
        'hours_contributed',
    ];

    protected $casts = [
        'status' => VolunteeringApplicationStatus::class,
        'hours_contributed' => 'integer',
    ];

    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(VolunteeringOpportunity::class, 'volunteering_opportunity_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\\Models\\User');
    }
}
