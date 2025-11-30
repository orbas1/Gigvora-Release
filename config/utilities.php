<?php

return [
    'notifications' => [
        'enabled' => env('PRO_NETWORK_FEATURE_UTILITIES_NOTIFICATIONS', false),
        'batch_limit' => env('PRO_NETWORK_UTILITIES_NOTIFICATION_BATCH', 50),
    ],
    'bookmarks' => [
        'enabled' => env('PRO_NETWORK_FEATURE_UTILITIES_BOOKMARKS', false),
        'limit' => env('PRO_NETWORK_UTILITIES_SAVED_ITEMS_LIMIT', 200),
    ],
    'calendar' => [
        'enabled' => env('PRO_NETWORK_FEATURE_UTILITIES_CALENDAR', false),
        'reminder_limit' => env('PRO_NETWORK_UTILITIES_REMINDER_LIMIT', 20),
    ],
    'quick_tools' => [
        'enabled' => env('PRO_NETWORK_FEATURE_UTILITIES_QUICK_TOOLS', false),
    ],
    'composer' => [
        'emoji_packs' => [
            [
                'id' => 'essentials',
                'label' => 'Essentials',
                'items' => ['😀', '😁', '😂', '😊', '😍', '🤩', '😎', '😇', '🤗', '🤝'],
            ],
            [
                'id' => 'work',
                'label' => 'Work vibes',
                'items' => ['💼', '📅', '📣', '🧠', '🧑‍💻', '🎯', '💡', '🚀'],
            ],
        ],
        'gif' => [
            'enabled' => env('PRO_NETWORK_UTILITIES_GIF_ENABLED', false),
            'provider' => env('PRO_NETWORK_UTILITIES_GIF_PROVIDER', 'tenor'),
            'api_key' => env('PRO_NETWORK_UTILITIES_GIF_API_KEY'),
            'endpoint' => env('PRO_NETWORK_UTILITIES_GIF_ENDPOINT', 'https://tenor.googleapis.com/v2/search'),
            'limit' => env('PRO_NETWORK_UTILITIES_GIF_LIMIT', 12),
        ],
        'sticker_packs' => [
            [
                'id' => 'momentum',
                'label' => 'Momentum',
                'items' => [
                    ['id' => 'boost', 'emoji' => '🚀', 'label' => 'Boost'],
                    ['id' => 'coffee', 'emoji' => '☕️', 'label' => 'Coffee break'],
                    ['id' => 'focus', 'emoji' => '🎧', 'label' => 'Deep focus'],
                    ['id' => 'ship', 'emoji' => '🛳️', 'label' => 'Ship it'],
                ],
            ],
            [
                'id' => 'celebrate',
                'label' => 'Celebrate',
                'items' => [
                    ['id' => 'confetti', 'emoji' => '🎉', 'label' => 'Confetti'],
                    ['id' => 'trophy', 'emoji' => '🏆', 'label' => 'Trophy'],
                    ['id' => 'medal', 'emoji' => '🎖️', 'label' => 'Medal'],
                    ['id' => 'sparkles', 'emoji' => '✨', 'label' => 'Sparkles'],
                ],
            ],
        ],
    ],
];


