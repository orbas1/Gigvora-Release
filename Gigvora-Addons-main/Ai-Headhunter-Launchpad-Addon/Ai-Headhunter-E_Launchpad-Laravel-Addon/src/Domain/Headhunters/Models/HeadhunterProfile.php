<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Headhunters\Models;

use Gigvora\TalentAi\Domain\Shared\Enums\HeadhunterProfileStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeadhunterProfile extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'bio',
        'industries',
        'skills',
        'approved_at',
    ];

    protected $casts = [
        'industries' => 'array',
        'skills' => 'array',
        'approved_at' => 'datetime',
        'status' => HeadhunterProfileStatus::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\\Models\\User');
    }

    public function mandates(): HasMany
    {
        return $this->hasMany(HeadhunterMandate::class);
    }

    public function candidates(): HasMany
    {
        return $this->hasMany(HeadhunterCandidate::class);
    }
}
