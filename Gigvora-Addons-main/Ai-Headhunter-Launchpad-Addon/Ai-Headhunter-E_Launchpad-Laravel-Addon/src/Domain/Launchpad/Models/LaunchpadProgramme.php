<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Launchpad\Models;

use Gigvora\TalentAi\Domain\Shared\Enums\LaunchpadProgrammeStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaunchpadProgramme extends Model
{
    protected $fillable = [
        'creator_id',
        'title',
        'category',
        'description',
        'estimated_hours',
        'estimated_weeks',
        'reference_offered',
        'qualification_offered',
        'pay_reduction_percentage',
        'status',
    ];

    protected $casts = [
        'reference_offered' => 'boolean',
        'qualification_offered' => 'boolean',
        'estimated_hours' => 'integer',
        'estimated_weeks' => 'integer',
        'pay_reduction_percentage' => 'decimal:2',
        'status' => LaunchpadProgrammeStatus::class,
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo('App\\Models\\User', 'creator_id');
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(LaunchpadTask::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(LaunchpadApplication::class);
    }
}
