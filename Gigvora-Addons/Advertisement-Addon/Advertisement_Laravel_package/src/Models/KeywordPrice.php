<?php

namespace Advertisement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeywordPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword',
        'search_volume',
        'competition_score',
        'quality_score',
        'ctr',
        'conversion_rate',
        'placement_multiplier',
        'currency',
        'last_synced_at',
        'cpc',
        'cpa',
        'cpm'
    ];

    protected $casts = [
        'search_volume' => 'integer',
        'competition_score' => 'float',
        'quality_score' => 'float',
        'ctr' => 'float',
        'conversion_rate' => 'float',
        'placement_multiplier' => 'float',
        'cpc' => 'float',
        'cpa' => 'float',
        'cpm' => 'float',
        'last_synced_at' => 'datetime',
    ];
}
