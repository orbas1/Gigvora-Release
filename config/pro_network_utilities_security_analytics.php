<?php

return [
    'features' => [
        'connections_graph' => env('PRO_NETWORK_FEATURE_CONNECTIONS_GRAPH', false),
        'recommendations' => env('PRO_NETWORK_FEATURE_RECOMMENDATIONS', false),
        'live_streaming_enhanced' => env('PRO_NETWORK_FEATURE_LIVE_STREAMING', false),
        'notifications_wrapper' => env('PRO_NETWORK_FEATURE_NOTIFICATIONS', false),
        'marketplace_escrow' => env('PRO_NETWORK_FEATURE_MARKETPLACE_ESCROW', false),
        'profile_professional_upgrades' => env('PRO_NETWORK_FEATURE_PROFILE_UPGRADES', false),
        'reactions_dislikes_scores' => env('PRO_NETWORK_FEATURE_REACTIONS', false),
        'stories_wrapper' => env('PRO_NETWORK_FEATURE_STORIES', false),
        'post_enhancements' => env('PRO_NETWORK_FEATURE_POSTS', false),
        'hashtags' => env('PRO_NETWORK_FEATURE_HASHTAGS', false),
        'music_library' => env('PRO_NETWORK_FEATURE_MUSIC', false),
        'bad_word_checker' => env('PRO_NETWORK_FEATURE_BAD_WORDS', false),
        'moderation_tools' => env('PRO_NETWORK_FEATURE_MODERATION', false),
        'file_scan' => env('PRO_NETWORK_FEATURE_FILE_SCAN', false),
        'db_encryption' => env('PRO_NETWORK_FEATURE_DB_ENCRYPTION', false),
        'storage_backends' => env('PRO_NETWORK_FEATURE_STORAGE', false),
        'account_types' => env('PRO_NETWORK_FEATURE_ACCOUNT_TYPES', false),
        'search_upgrade' => env('PRO_NETWORK_FEATURE_SEARCH', false),
        'chat_enhancements' => env('PRO_NETWORK_FEATURE_CHAT', false),
        'analytics_hub' => env('PRO_NETWORK_FEATURE_ANALYTICS', false),
        'security_hardening' => env('PRO_NETWORK_FEATURE_SECURITY', false),
        'age_verification' => env('PRO_NETWORK_FEATURE_AGE_VERIFICATION', false),
        'newsletters' => env('PRO_NETWORK_FEATURE_NEWSLETTERS', false),
        'invite_contributors' => env('PRO_NETWORK_FEATURE_INVITES', false),
        'multi_language_wrapper' => env('PRO_NETWORK_FEATURE_MULTI_LANGUAGE', false),
        'utilities_notifications_center' => env('PRO_NETWORK_FEATURE_UTILITIES_NOTIFICATIONS', false),
        'utilities_bookmarks' => env('PRO_NETWORK_FEATURE_UTILITIES_BOOKMARKS', false),
        'utilities_calendar' => env('PRO_NETWORK_FEATURE_UTILITIES_CALENDAR', false),
        'utilities_reminders' => env('PRO_NETWORK_FEATURE_UTILITIES_REMINDERS', false),
        'utilities_quick_tools' => env('PRO_NETWORK_FEATURE_UTILITIES_QUICK_TOOLS', false),
    ],

    'utilities' => [
        'prefixes' => [
            'web' => env('PRO_NETWORK_UTILITIES_WEB_PREFIX', 'utilities'),
            'api' => env('PRO_NETWORK_UTILITIES_API_PREFIX', 'api/utilities'),
        ],
        'middleware' => [
            'web' => ['web', 'auth', 'verified', 'locale'],
            'api' => ['api', 'auth:sanctum', 'locale'],
        ],
        'limits' => [
            'saved_items' => env('PRO_NETWORK_UTILITIES_SAVED_ITEMS_LIMIT', 200),
            'reminders_per_day' => env('PRO_NETWORK_UTILITIES_REMINDER_LIMIT', 20),
            'notification_batch' => env('PRO_NETWORK_UTILITIES_NOTIFICATION_BATCH', 50),
        ],
        'retention' => [
            'notifications_days' => env('PRO_NETWORK_UTILITIES_NOTIFICATION_RETENTION', 90),
            'activity_days' => env('PRO_NETWORK_UTILITIES_ACTIVITY_RETENTION', 180),
        ],
    ],

    'analytics' => [
        'driver' => env('PRO_NETWORK_ANALYTICS_DRIVER', 'log'),
        'driver_alias' => env('PRO_NETWORK_ANALYTICS_DRIVER_ALIAS', 'pro-network-analytics'),
        'forward' => env('PRO_NETWORK_ANALYTICS_FORWARD', false),
        'queue' => env('PRO_NETWORK_ANALYTICS_QUEUE', false),
    ],

    'storage' => [
        'default_disk' => env('PRO_NETWORK_STORAGE_DISK', 'local'),
        'r2_disk' => env('PRO_NETWORK_R2_DISK', 'r2'),
        'wasabi_disk' => env('PRO_NETWORK_WASABI_DISK', 'wasabi'),
        'local_disk' => env('PRO_NETWORK_LOCAL_DISK', 'local'),
        'signed_url_ttl' => env('PRO_NETWORK_SIGNED_TTL', 300),
        'encryption' => [
            'enabled' => env('PRO_NETWORK_STORAGE_ENCRYPTION', false),
            'key' => env('PRO_NETWORK_STORAGE_ENCRYPTION_KEY'),
        ],
    ],

    'virus_scanner' => [
        'endpoint' => env('PRO_NETWORK_VIRUS_SCAN_ENDPOINT'),
        'api_key' => env('PRO_NETWORK_VIRUS_SCAN_TOKEN'),
        'timeout' => env('PRO_NETWORK_VIRUS_SCAN_TIMEOUT', 5),
        'enabled' => env('PRO_NETWORK_VIRUS_SCAN_ENABLED', false),
    ],

    'age_verification' => [
        'provider' => env('PRO_NETWORK_AGE_PROVIDER', 'placeholder'),
        'api_key' => env('PRO_NETWORK_AGE_PROVIDER_KEY'),
        'callback_url' => env('PRO_NETWORK_AGE_CALLBACK'),
        'required_for' => [
            'live_streaming' => env('PRO_NETWORK_AGE_REQUIRED_LIVE', false),
            'marketplace_payouts' => env('PRO_NETWORK_AGE_REQUIRED_PAYOUTS', false),
        ],
    ],

    'kyc' => [
        'provider' => env('PRO_NETWORK_KYC_PROVIDER', 'placeholder'),
        'api_key' => env('PRO_NETWORK_KYC_KEY'),
        'webhook_secret' => env('PRO_NETWORK_KYC_WEBHOOK_SECRET'),
    ],

    'security' => [
        'brute_force' => [
            'max_attempts' => env('PRO_NETWORK_BRUTE_FORCE_MAX', 5),
            'decay_minutes' => env('PRO_NETWORK_BRUTE_FORCE_DECAY', 15),
            'block_minutes' => env('PRO_NETWORK_BRUTE_FORCE_BLOCK', 60),
        ],
        'rate_limits' => [
            'api' => env('PRO_NETWORK_RATE_LIMIT_API', '60,1'),
            'sensitive' => env('PRO_NETWORK_RATE_LIMIT_SENSITIVE', '10,1'),
        ],
        'ip_reputation' => [
            'enabled' => env('PRO_NETWORK_IP_REPUTATION_ENABLED', false),
            'block_on_bad_reputation' => env('PRO_NETWORK_IP_REPUTATION_BLOCK', false),
        ],
        'gdpr' => [
            'log_access' => env('PRO_NETWORK_GDPR_LOG_ACCESS', false),
            'retain_days' => env('PRO_NETWORK_GDPR_RETAIN_DAYS', 365),
        ],
    ],

    'models' => [
        'user' => \App\Models\User::class,
        'post' => \App\Models\Posts::class,
        'page' => \App\Models\Page::class,
        'group' => \App\Models\Group::class,
        'story' => \App\Models\Stories::class,
        'marketplace_order' => \App\Models\Marketplace::class,
    ],
];


