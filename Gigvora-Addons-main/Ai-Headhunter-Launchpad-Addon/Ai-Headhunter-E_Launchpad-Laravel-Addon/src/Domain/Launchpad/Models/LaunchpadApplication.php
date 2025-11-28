<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Launchpad\Models;

use Gigvora\TalentAi\Domain\Shared\Enums\LaunchpadApplicationStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaunchpadApplication extends Model
{
    protected $fillable = [
        'launchpad_programme_id',
        'user_id',
        'status',
        'motivation',
        'reference_issued',
        'qualification_issued',
        'hours_gained',
        'weeks_gained',
    ];

    protected $casts = [
        'status' => LaunchpadApplicationStatus::class,
        'reference_issued' => 'boolean',
        'qualification_issued' => 'boolean',
        'hours_gained' => 'integer',
        'weeks_gained' => 'integer',
    ];

    public function programme(): BelongsTo
    {
        return $this->belongsTo(LaunchpadProgramme::class, 'launchpad_programme_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\\Models\\User');
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(LaunchpadInterview::class, 'launchpad_application_id');
    }
}
