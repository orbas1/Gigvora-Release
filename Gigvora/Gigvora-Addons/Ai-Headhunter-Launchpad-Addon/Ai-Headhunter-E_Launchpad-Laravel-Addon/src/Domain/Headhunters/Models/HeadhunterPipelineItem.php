<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Headhunters\Models;

use Gigvora\TalentAi\Domain\Shared\Enums\HeadhunterPipelineStage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeadhunterPipelineItem extends Model
{
    protected $fillable = [
        'headhunter_mandate_id',
        'headhunter_candidate_id',
        'stage',
        'notes',
        'moved_at',
    ];

    protected $casts = [
        'stage' => HeadhunterPipelineStage::class,
        'moved_at' => 'datetime',
    ];

    public function mandate(): BelongsTo
    {
        return $this->belongsTo(HeadhunterMandate::class, 'headhunter_mandate_id');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(HeadhunterCandidate::class, 'headhunter_candidate_id');
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(HeadhunterInterview::class, 'headhunter_pipeline_item_id');
    }
}
