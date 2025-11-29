<?php

namespace Jobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtsStageAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'ats_stage_id',
        'notes',
        'moved_by',
        'moved_at',
    ];

    protected $casts = [
        'moved_at' => 'datetime',
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function stage()
    {
        return $this->belongsTo(AtsStage::class, 'ats_stage_id');
    }
}
