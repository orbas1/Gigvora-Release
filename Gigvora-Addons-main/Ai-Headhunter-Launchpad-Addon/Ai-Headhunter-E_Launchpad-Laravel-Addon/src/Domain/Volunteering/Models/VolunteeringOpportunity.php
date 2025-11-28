<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Volunteering\Models;

use Gigvora\TalentAi\Domain\Shared\Enums\VolunteeringOpportunityStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VolunteeringOpportunity extends Model
{
    protected $fillable = [
        'organisation_id',
        'creator_id',
        'title',
        'sector',
        'location',
        'commitment',
        'expenses_covered',
        'verified',
        'status',
        'description',
    ];

    protected $casts = [
        'expenses_covered' => 'boolean',
        'verified' => 'boolean',
        'status' => VolunteeringOpportunityStatus::class,
    ];

    public function organisation(): BelongsTo
    {
        return $this->belongsTo('App\\Models\\Organisation', 'organisation_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo('App\\Models\\User', 'creator_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(VolunteeringApplication::class);
    }
}
