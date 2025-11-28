<?php

namespace Jobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Jobs\Models\Job;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'headline',
        'description',
        'website',
        'location',
        'logo_path',
        'cover_path',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class, 'company_id');
    }
}
