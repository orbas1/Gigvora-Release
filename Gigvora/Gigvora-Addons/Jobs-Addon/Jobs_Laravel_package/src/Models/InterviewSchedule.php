<?php

namespace Jobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InterviewSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'scheduled_at',
        'location',
        'instructions',
        'meeting_link',
        'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];
}
