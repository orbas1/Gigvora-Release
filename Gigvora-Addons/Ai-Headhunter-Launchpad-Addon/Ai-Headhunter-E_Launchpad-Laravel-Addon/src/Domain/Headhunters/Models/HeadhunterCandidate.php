<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Headhunters\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeadhunterCandidate extends Model
{
    protected $fillable = [
        'headhunter_profile_id',
        'user_id',
        'name',
        'email',
        'phone',
        'skills',
        'experience',
    ];

    protected $casts = [
        'skills' => 'array',
        'experience' => 'array',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(HeadhunterProfile::class, 'headhunter_profile_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo('App\\Models\\User');
    }

    public function pipelineItems(): HasMany
    {
        return $this->hasMany(HeadhunterPipelineItem::class, 'headhunter_candidate_id');
    }
}
