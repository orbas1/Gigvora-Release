<?php

namespace Advertisement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'name',
        'daily_budget',
        'bid_amount',
        'status'
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function creatives()
    {
        return $this->hasMany(Creative::class);
    }
}
