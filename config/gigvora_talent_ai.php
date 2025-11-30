<?php

$packageConfigPath = base_path('Gigvora-Addons/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-Laravel-Addon/config/gigvora_talent_ai.php');

$packageDefaults = file_exists($packageConfigPath)
    ? require $packageConfigPath
    : [
        'enabled' => env('GIGVORA_TALENT_AI_ENABLED', false),
        'modules' => [
            'headhunters' => ['enabled' => env('GIGVORA_TALENT_AI_HEADHUNTERS_ENABLED', false)],
            'launchpad' => ['enabled' => env('GIGVORA_TALENT_AI_LAUNCHPAD_ENABLED', false)],
            'ai_workspace' => ['enabled' => env('GIGVORA_TALENT_AI_WORKSPACE_ENABLED', false)],
            'volunteering' => ['enabled' => env('GIGVORA_TALENT_AI_VOLUNTEERING_ENABLED', false)],
        ],
        'ai' => [
            'provider' => env('GIGVORA_TALENT_AI_PROVIDER', 'stub'),
            'byok' => ['enabled' => env('GIGVORA_TALENT_AI_BYOK_ENABLED', false)],
        ],
    ];

return array_replace_recursive($packageDefaults, [
    'routes' => [
        'web_prefix' => 'addons/talent-ai',
        'api_prefix' => 'api/addons/talent-ai',
        'name_prefix' => 'addons.talent_ai.',
        'api_name_prefix' => 'api.addons.talent_ai.',
    ],
    'navigation' => [
        'menu_label' => 'Talent & AI',
        'children' => [
            'headhunters' => [
                'label' => 'Headhunters',
                'route' => 'addons.talent_ai.headhunters.dashboard',
                'module' => 'modules.headhunters.enabled',
            ],
            'launchpad' => [
                'label' => 'Experience Launchpad',
                'route' => 'addons.talent_ai.launchpad.programmes.index',
                'module' => 'modules.launchpad.enabled',
            ],
            'ai_workspace' => [
                'label' => 'AI Workspace',
                'route' => 'addons.talent_ai.ai_workspace.index',
                'module' => 'modules.ai_workspace.enabled',
            ],
            'volunteering' => [
                'label' => 'Volunteering',
                'route' => 'addons.talent_ai.volunteering.opportunities.index',
                'module' => 'modules.volunteering.enabled',
            ],
        ],
    ],
    'ai' => [
        'providers' => [
            'openai' => [
                'api_key' => env('GIGVORA_TALENT_AI_OPENAI_KEY'),
                'model' => env('GIGVORA_TALENT_AI_OPENAI_MODEL', 'gpt-4o-mini'),
            ],
        ],
    ],
    'analytics' => [
        'emit_events' => env('GIGVORA_TALENT_AI_ANALYTICS', false),
        'log_channels' => [
            'ai_errors' => env('GIGVORA_TALENT_AI_LOG_CHANNEL_ERRORS', 'stack'),
            'ai_abuse' => env('GIGVORA_TALENT_AI_LOG_CHANNEL_ABUSE', 'stack'),
            'ai_costs' => env('GIGVORA_TALENT_AI_LOG_CHANNEL_COSTS', 'stack'),
        ],
    ],
    'rate_limits' => [
        'ai_workspace' => [
            'per_minute' => env('GIGVORA_TALENT_AI_AI_WORKSPACE_PER_MINUTE', 10),
            'per_hour' => env('GIGVORA_TALENT_AI_AI_WORKSPACE_PER_HOUR', 100),
        ],
    ],
]);

