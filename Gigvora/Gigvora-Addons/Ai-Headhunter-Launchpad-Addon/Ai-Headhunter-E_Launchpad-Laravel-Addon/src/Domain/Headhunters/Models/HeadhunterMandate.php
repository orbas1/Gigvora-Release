<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Headhunters\Models;

use Gigvora\TalentAi\Domain\Shared\Enums\HeadhunterMandateStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeadhunterMandate extends Model
{
    protected $fillable = [
        'headhunter_profile_id',
        'organisation_id',
        'title',
        'location',
        'fee_model',
        'fee_amount',
        'status',
        'requirements',
    ];

    protected $casts = [
        'requirements' => 'array',
        'status' => HeadhunterMandateStatus::class,
        'fee_amount' => 'decimal:2',
    ];

    public function profile(): BelongsTo
    {
        return $this->belongsTo(HeadhunterProfile::class, 'headhunter_profile_id');
    }

    public function organisation(): BelongsTo
    {
        return $this->belongsTo('App\\Models\\Organisation', 'organisation_id');
    }

    public function pipelineItems(): HasMany
    {
        return $this->hasMany(HeadhunterPipelineItem::class);
    }
}
