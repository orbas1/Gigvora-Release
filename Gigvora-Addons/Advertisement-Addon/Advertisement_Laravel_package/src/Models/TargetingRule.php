<?php

namespace Advertisement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetingRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'type',
        'value',
        'operator',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
