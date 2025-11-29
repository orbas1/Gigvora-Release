<?php

return [
    'enabled' => env('ADS_ENABLED', true),
    'allow_author_view' => env('ADS_AUTHOR_VIEW_ENABLED', false),
    'approval_required' => env('ADS_APPROVAL_ENABLED', true),
    'default_budget' => env('ADS_DEFAULT_BUDGET', 50.00),
    'minimum_budget' => env('ADS_MINIMUM_BUDGET', 5.00),
    'bidding' => [
        'click_cost' => env('ADS_COST_CLICK', 0.50),
        'view_cost' => env('ADS_COST_VIEW', 0.10),
        'conversion_cost' => env('ADS_COST_CONVERSION', 1.50),
    ],
    'placements' => [
        'newsfeed', 'sidebar', 'profile', 'search', 'gigs', 'jobs', 'projects', 'podcasts', 'webinars', 'networking'
    ],
    'features' => [
        'forecasting' => env('ADS_FORECAST_ENABLED', true),
        'simulation' => env('ADS_SIMULATION_ENABLED', true),
        'keyword_pricing' => env('ADS_KEYWORD_PRICING_ENABLED', true),
        'affiliate' => env('ADS_AFFILIATE_ENABLED', true),
    ],
    'roles' => [
        'administrator' => ['approve_campaigns', 'manage_placements', 'view_all_reports'],
        'advertiser' => ['create_campaigns', 'manage_creatives', 'view_own_reports'],
        'analyst' => ['view_all_reports'],
    ],
    'limits' => [
        'daily_spend' => env('ADS_DAILY_SPEND_LIMIT', 500),
        'lifetime_spend' => env('ADS_LIFETIME_SPEND_LIMIT', 5000),
    ],
    'affiliate' => [
        'enabled' => env('AFFILIATE_ENABLED', true),
        'commission_rate' => env('AFFILIATE_COMMISSION_RATE', 0.1),
        'payout_threshold' => env('AFFILIATE_PAYOUT_THRESHOLD', 50),
        'cooldown_days' => env('AFFILIATE_PAYOUT_COOLDOWN', 7),
    ],
];
