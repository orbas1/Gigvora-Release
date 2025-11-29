<?php

namespace Advertisement\Services;

use Advertisement\Models\Campaign;
use Advertisement\Models\Forecast;
use Advertisement\Models\Metric;

class ForecastingService
{
    public function run(Campaign $campaign): Forecast
    {
        $historical = $campaign->metrics()->latest('recorded_at')->first();
        $dailyBudget = max($campaign->budget / max(1, $campaign->start_date->diffInDays($campaign->end_date)), 1);
        $reach = $historical?->impressions ?? ($dailyBudget * 120);
        $clicks = $historical?->clicks ?? (int)($reach * 0.02);
        $conversions = $historical?->conversions ?? (int)($clicks * 0.05);
        $estimatedSpend = $historical?->spend ?? ($dailyBudget * 0.85);

        return $campaign->forecasts()->create([
            'reach' => $reach,
            'clicks' => $clicks,
            'conversions' => $conversions,
            'estimated_spend' => $estimatedSpend,
            'assumptions' => [
                'source' => $historical ? 'historical' : 'baseline',
                'daily_budget' => $dailyBudget,
            ],
        ]);
    }
}
