<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Domain\Headhunters\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeadhunterInterview extends Model
{
    protected $fillable = [
        'headhunter_pipeline_item_id',
        'scheduled_by',
        'scheduled_at',
        'status',
        'summary',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function pipelineItem(): BelongsTo
    {
        return $this->belongsTo(HeadhunterPipelineItem::class, 'headhunter_pipeline_item_id');
    }

    public function scheduler(): BelongsTo
    {
        return $this->belongsTo('App\\Models\\User', 'scheduled_by');
    }
}
