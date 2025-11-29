<?php

namespace Jobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'candidate_id',
        'cover_letter_id',
        'cv_template_id',
        'screening_score',
        'status',
        'notes',
        'resume_path',
        'applied_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function candidate()
    {
        return $this->belongsTo(CandidateProfile::class, 'candidate_id');
    }

    public function coverLetter()
    {
        return $this->belongsTo(CoverLetter::class);
    }

    public function cv()
    {
        return $this->belongsTo(CvTemplate::class, 'cv_template_id');
    }

    public function stageAssignments()
    {
        return $this->hasMany(AtsStageAssignment::class);
    }

    public function screeningAnswers()
    {
        return $this->hasMany(ScreeningAnswer::class);
    }

    public function interviews()
    {
        return $this->hasMany(InterviewSchedule::class);
    }
}
