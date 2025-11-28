<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Launchpad\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaunchpadApplicationTaskProgress extends Model
{
    protected $fillable = [
        'launchpad_application_id',
        'launchpad_task_id',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(LaunchpadApplication::class, 'launchpad_application_id');
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(LaunchpadTask::class, 'launchpad_task_id');
    }
}
