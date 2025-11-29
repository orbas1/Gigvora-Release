<?php

namespace Jobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobBookmark extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'user_id',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
