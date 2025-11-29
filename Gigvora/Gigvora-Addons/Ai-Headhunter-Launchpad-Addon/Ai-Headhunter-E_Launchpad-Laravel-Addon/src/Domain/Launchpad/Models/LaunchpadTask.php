<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Launchpad\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaunchpadTask extends Model
{
    protected $fillable = [
        'launchpad_programme_id',
        'title',
        'description',
        'order',
        'estimated_hours',
    ];

    protected $casts = [
        'order' => 'integer',
        'estimated_hours' => 'integer',
    ];

    public function programme(): BelongsTo
    {
        return $this->belongsTo(LaunchpadProgramme::class, 'launchpad_programme_id');
    }
}
