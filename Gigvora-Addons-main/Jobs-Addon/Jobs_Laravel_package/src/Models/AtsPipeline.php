<?php

namespace Jobs\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtsPipeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function stages()
    {
        return $this->hasMany(AtsStage::class)->orderBy('position');
    }
}
