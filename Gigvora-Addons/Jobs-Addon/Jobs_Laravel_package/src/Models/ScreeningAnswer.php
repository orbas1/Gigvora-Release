<?php

namespace Jobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScreeningAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'screening_question_id',
        'answer',
    ];

    public function question()
    {
        return $this->belongsTo(ScreeningQuestion::class, 'screening_question_id');
    }
}
