<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'interview_id',
        'interviewer_id',
        'interviewee_id',
        'starts_at',
        'ends_at',
        'status',
        'meeting_link',
        'metadata',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'interviewer_id');
    }

    public function interviewee(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'interviewee_id');
    }
}

