<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Launchpad\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaunchpadInterview extends Model
{
    protected $fillable = [
        'launchpad_application_id',
        'scheduled_by',
        'scheduled_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(LaunchpadApplication::class, 'launchpad_application_id');
    }

    public function scheduler(): BelongsTo
    {
        return $this->belongsTo('App\\Models\\User', 'scheduled_by');
    }
}
