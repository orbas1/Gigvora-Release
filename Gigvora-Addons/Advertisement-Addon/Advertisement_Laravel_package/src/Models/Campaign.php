<?php

namespace Advertisement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'advertiser_id',
        'title',
        'start_date',
        'end_date',
        'budget',
        'bidding',
        'status',
        'spend',
        'placement',
        'objective',
        'targeting_reach',
        'approval_state',
    ];

    protected $appends = [
        'name',
        'daily_budget',
        'lifetime_budget',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class);
    }

    public function adGroups()
    {
        return $this->hasMany(AdGroup::class);
    }

    public function creatives()
    {
        return $this->hasMany(Creative::class);
    }

    public function targetingRules()
    {
        return $this->hasMany(TargetingRule::class);
    }

    public function metrics()
    {
        return $this->hasMany(Metric::class);
    }

    public function forecasts()
    {
        return $this->hasMany(Forecast::class);
    }

    public function getNameAttribute(): string
    {
        return $this->title;
    }

    public function getDailyBudgetAttribute(): float
    {
        $days = max($this->start_date?->diffInDays($this->end_date) ?? 1, 1);

        return round($this->budget / $days, 2);
    }

    public function getLifetimeBudgetAttribute(): float
    {
        return (float) $this->budget;
    }
}
