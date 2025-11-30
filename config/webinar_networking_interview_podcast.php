<?php

$packageConfigPath = base_path('Gigvora-Addons/Interactive-Addon/Webinar_networking_interview_and_Podcast_Laravel_package/config/webinar_networking_interview_podcast.php');

$packageDefaults = file_exists($packageConfigPath)
    ? require $packageConfigPath
    : [
        'enabled' => env('LIVE_ENABLED', true),
        'routes' => [
            'web' => [
                'prefix' => 'events',
                'middleware' => ['web', 'auth', 'verified', 'locale'],
            ],
            'api' => [
                'prefix' => 'api/live',
                'middleware' => ['api', 'auth:sanctum', 'verified'],
            ],
        ],
        'features' => [
            'webinars' => true,
            'networking' => true,
            'podcasts' => true,
            'interviews' => true,
        ],
        'permissions' => [
            'host' => ['create', 'update', 'start', 'end'],
            'moderator' => ['update', 'review', 'score', 'manage_attendees'],
            'attendee' => ['view', 'register', 'attend'],
            'interviewer' => ['score', 'comment', 'review'],
            'interviewee' => ['upload_files', 'join'],
        ],
    ];

$webMiddleware = array_filter(array_map('trim', explode(',', env('LIVE_WEB_MIDDLEWARE', 'web,auth,verified,locale'))));
$apiMiddleware = array_filter(array_map('trim', explode(',', env('LIVE_API_MIDDLEWARE', 'api,auth:sanctum,verified'))));

return array_replace_recursive($packageDefaults, [
    'enabled' => (bool) env('LIVE_ENABLED', true),
    'routes' => [
        'web' => [
            'prefix' => env('LIVE_WEB_PREFIX', 'events'),
            'middleware' => $webMiddleware,
        ],
        'api' => [
            'prefix' => env('LIVE_API_PREFIX', 'api/live'),
            'middleware' => $apiMiddleware,
        ],
    ],
    'navigation' => [
        'menu_label' => 'Live & Events',
        'webinar_route' => 'wnip.webinars.index',
        'networking_route' => 'wnip.networking.index',
        'podcast_route' => 'wnip.podcasts.index',
        'interview_route' => 'wnip.interviews.index',
    ],
]);

