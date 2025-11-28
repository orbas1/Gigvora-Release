<?php

namespace Jobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jobs\Models\JobApplication;

class CandidateProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'headline',
        'bio',
        'location',
        'skills',
        'experience_years',
        'resume_path',
        'portfolio_url',
    ];

    protected $casts = [
        'skills' => 'array',
    ];

    public function applications()
    {
        return $this->hasMany(JobApplication::class, 'candidate_id');
    }
}
