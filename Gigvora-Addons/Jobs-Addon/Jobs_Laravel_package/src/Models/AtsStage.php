<?php

namespace Jobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtsStage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ats_pipeline_id',
        'name',
        'position',
        'color',
    ];

    public function pipeline()
    {
        return $this->belongsTo(AtsPipeline::class);
    }
}
