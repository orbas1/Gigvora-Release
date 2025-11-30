<?php

return [
    'enabled' => env('FREELANCE_ENABLED', true),

    'web' => [
        'prefix' => env('FREELANCE_WEB_PREFIX', 'freelance'),
        'middleware' => array_filter(array_map('trim', explode(',', env('FREELANCE_WEB_MIDDLEWARE', 'web,auth,verified')))),
    ],

    'api' => [
        'prefix' => env('FREELANCE_API_PREFIX', 'api/freelance'),
        'middleware' => array_filter(array_map('trim', explode(',', env('FREELANCE_API_MIDDLEWARE', 'api')))),
    ],

    'modules' => [
        'gigs' => env('FREELANCE_GIGS_ENABLED', true),
        'projects' => env('FREELANCE_PROJECTS_ENABLED', true),
        'packages' => env('FREELANCE_PACKAGES_ENABLED', true),
        'escrow' => env('FREELANCE_ESCROW_ENABLED', true),
        'disputes' => env('FREELANCE_DISPUTES_ENABLED', true),
    ],

    'commissions' => [
        'gig_fee_percent' => env('FREELANCE_GIG_COMMISSION', 10),
        'project_fee_percent' => env('FREELANCE_PROJECT_COMMISSION', 12.5),
        'escrow_flat_fee' => env('FREELANCE_ESCROW_FLAT_FEE', 0),
    ],

    'roles' => [
        'seller' => env('FREELANCE_ROLE_SELLER', 'seller'),
        'buyer' => env('FREELANCE_ROLE_BUYER', 'buyer'),
    ],

    'payments' => [
        'default_currency' => env('FREELANCE_DEFAULT_CURRENCY', 'USD'),
        'default_method' => env('FREELANCE_DEFAULT_PAYMENT_METHOD', 'stripe'),
        'gateways' => [
            'stripe' => [
                'display_name' => 'Stripe',
                'ipn_route' => null,
                'supports_wallet' => true,
            ],
            'paypal' => [
                'display_name' => 'PayPal',
                'ipn_route' => null,
                'supports_wallet' => false,
            ],
            'escrow' => [
                'display_name' => 'Manual Escrow',
                'ipn_route' => null,
                'supports_wallet' => false,
            ],
        ],
        'currencies' => [
            'USD' => ['symbol' => '$', 'precision' => 2],
            'EUR' => ['symbol' => '€', 'precision' => 2],
            'GBP' => ['symbol' => '£', 'precision' => 2],
            'INR' => ['symbol' => '₹', 'precision' => 2],
        ],
    ],

    'defaults' => [
        '_general' => [
            'currency' => env('FREELANCE_DEFAULT_CURRENCY', 'USD'),
            'default_payment_method' => env('FREELANCE_DEFAULT_PAYMENT_METHOD', 'stripe'),
        ],
        'payment' => [
            'payment_methods' => [
                'method_type' => 'others',
                'others' => [
                    'stripe' => ['status' => 'on'],
                    'paypal' => ['status' => 'on'],
                ],
                'escrow' => [
                    'account_email' => env('FREELANCE_ESCROW_ACCOUNT'),
                ],
            ],
            'commission_setting' => [
                'gig_fee_percent' => env('FREELANCE_GIG_COMMISSION', 10),
                'project_fee_percent' => env('FREELANCE_PROJECT_COMMISSION', 12.5),
            ],
        ],
        'packages' => [
            'single_project_credits' => env('FREELANCE_DEFAULT_PROJECT_CREDITS', 10),
        ],
    ],
];

