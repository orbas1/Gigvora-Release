<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'interview_id',
        'interview_slot_id',
        'interviewer_id',
        'criteria',
        'scores',
        'comments',
    ];

    protected $casts = [
        'criteria' => 'array',
        'scores' => 'array',
    ];

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function slot(): BelongsTo
    {
        return $this->belongsTo(InterviewSlot::class, 'interview_slot_id');
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'interviewer_id');
    }
}

