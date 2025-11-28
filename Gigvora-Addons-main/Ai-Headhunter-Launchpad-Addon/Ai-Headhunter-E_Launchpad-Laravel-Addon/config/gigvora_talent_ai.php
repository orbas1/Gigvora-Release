<?php

return [
    'enabled' => env('GIGVORA_TALENT_AI_ENABLED', false),

    'modules' => [
        'headhunters' => ['enabled' => env('GIGVORA_TALENT_AI_HEADHUNTERS_ENABLED', false)],
        'launchpad' => ['enabled' => env('GIGVORA_TALENT_AI_LAUNCHPAD_ENABLED', false)],
        'ai_workspace' => ['enabled' => env('GIGVORA_TALENT_AI_WORKSPACE_ENABLED', false)],
        'volunteering' => ['enabled' => env('GIGVORA_TALENT_AI_VOLUNTEERING_ENABLED', false)],
    ],

    'ai' => [
        'provider' => env('GIGVORA_TALENT_AI_PROVIDER', 'stub'),
        'byok' => [
            'enabled' => env('GIGVORA_TALENT_AI_BYOK_ENABLED', false),
        ],
        'platform_keys' => [
            'enabled' => env('GIGVORA_TALENT_AI_PLATFORM_KEYS_ENABLED', false),
        ],
        'pricing_tiers' => [
            'basic' => [
                'tokens' => [
                    'daily' => 20000,
                    'monthly' => 300000,
                ],
                'features' => ['cv_writer', 'outreach', 'coach'],
            ],
            'pro' => [
                'tokens' => [
                    'daily' => 60000,
                    'monthly' => 900000,
                ],
                'features' => ['cv_writer', 'outreach', 'coach', 'social_calendar', 'repurpose', 'interview_prep'],
            ],
            'enterprise' => [
                'tokens' => [
                    'daily' => 120000,
                    'monthly' => 2000000,
                ],
                'features' => ['*'],
            ],
        ],
        'cost_model' => [
            'per_token_usd' => 0.000002,
            'per_image_usd' => 0.03,
            'per_video_second_usd' => 0.02,
        ],
        'usage_limits' => [
            'basic' => ['daily' => 20, 'weekly' => 60, 'monthly' => 120],
            'pro' => ['daily' => 60, 'weekly' => 200, 'monthly' => 500],
            'enterprise' => ['daily' => 200, 'weekly' => 800, 'monthly' => 2000],
        ],
        'guardrails' => [
            'max_prompt_length' => 4000,
            'max_output_length' => 2000,
            'blocked_tasks' => [],
        ],
    ],

    'headhunters' => [
        'default_pipeline_stages' => [
            ['slug' => 'sourced', 'label' => 'Sourced'],
            ['slug' => 'screened', 'label' => 'Screened'],
            ['slug' => 'shortlisted', 'label' => 'Shortlisted'],
            ['slug' => 'interview', 'label' => 'Interview'],
            ['slug' => 'offer', 'label' => 'Offer'],
            ['slug' => 'placed', 'label' => 'Placed'],
        ],
        'roles' => [
            'admin' => ['manage_mandates', 'manage_pipeline', 'view_all'],
            'headhunter' => ['manage_mandates', 'manage_pipeline'],
        ],
        'fee_models' => [
            'retainer' => 'Retainer',
            'success' => 'Success Fee',
            'hybrid' => 'Hybrid',
        ],
    ],

    'launchpad' => [
        'default_categories' => ['Internship', 'Career Change', 'Returnship', 'Bootcamp'],
        'rules' => [
            'pay_reductions_allowed' => false,
            'experience_credit_behaviour' => 'hours',
            'approval_required' => true,
        ],
    ],

    'volunteering' => [
        'default_categories' => ['Community', 'Education', 'Environment', 'Health', 'Tech for Good'],
        'verification_required' => true,
        'moderation_enabled' => true,
    ],

    'analytics' => [
        'emit_events' => env('GIGVORA_TALENT_AI_ANALYTICS', false),
        'log_channels' => [
            'ai_errors' => env('GIGVORA_TALENT_AI_LOG_CHANNEL_ERRORS', 'stack'),
            'ai_abuse' => env('GIGVORA_TALENT_AI_LOG_CHANNEL_ABUSE', 'stack'),
            'ai_costs' => env('GIGVORA_TALENT_AI_LOG_CHANNEL_COSTS', 'stack'),
        ],
    ],
];
