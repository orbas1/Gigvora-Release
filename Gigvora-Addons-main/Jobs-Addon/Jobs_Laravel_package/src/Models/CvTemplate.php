<?php

namespace Jobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'title',
        'content',
        'is_default',
    ];

    protected $casts = [
        'content' => 'array',
        'is_default' => 'boolean',
    ];
}
