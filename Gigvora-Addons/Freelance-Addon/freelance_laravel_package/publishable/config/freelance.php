<?php

return [
    'modules' => [
        'gigs' => true,
        'projects' => true,
        'escrow' => true,
        'disputes' => true,
        'project_management' => true,
        'gig_management' => true,
    ],

    'commissions' => [
        'gig_fee_percent' => 10,
        'project_fee_percent' => 12.5,
        'escrow_flat_fee' => 0,
    ],

    'default_roles' => [
        'freelancer' => 'freelancer',
        'client' => 'client',
    ],

    'features' => [
        'enable_livewire' => true,
        'publish_routes' => true,
        'publish_views' => true,
        'hourly_tracking' => true,
        'milestones' => true,
        'gig_packages' => true,
        'admin_escrow_management' => true,
    ],

    'search' => [
        'enabled' => true,
        'sync_to_feed' => true,
        'queue' => 'default',
        'index_prefix' => env('FREELANCE_SEARCH_PREFIX', 'freelance_'),
    ],

    'feed' => [
        'broadcast_events' => true,
        'log_activity' => true,
        'channels' => ['public', 'private'],
        'recommendations' => [
            'enabled' => true,
            'cache_ttl' => 3600,
            'min_score' => 0.35,
            'weighting' => [
                'skills' => 0.6,
                'recent_activity' => 0.3,
                'mutual_connections' => 0.1,
            ],
        ],
    ],

    'api' => [
        'middleware' => array_filter(explode(',', env('FREELANCE_API_MIDDLEWARE', 'api'))),
        'prefix' => env('FREELANCE_API_PREFIX', 'api/freelance'),
    ],

    'web' => [
        'middleware' => array_filter(explode(',', env('FREELANCE_WEB_MIDDLEWARE', 'web'))),
        'prefix' => env('FREELANCE_WEB_PREFIX', 'freelance'),
    ],
];
