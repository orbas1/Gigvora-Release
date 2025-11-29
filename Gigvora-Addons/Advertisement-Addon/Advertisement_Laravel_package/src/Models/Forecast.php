<?php

namespace Advertisement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'reach',
        'clicks',
        'conversions',
        'estimated_spend',
        'assumptions',
    ];

    protected $casts = [
        'assumptions' => 'array',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
