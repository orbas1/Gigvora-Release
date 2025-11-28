<?php

return [
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

    'pricing' => [
        'default_currency' => 'USD',
        'default_ticket_price' => 0,
        'paid_events_enabled' => true,
        'subscription_support' => true,
    ],

    'streaming' => [
        'provider' => env('WEBINAR_STREAM_PROVIDER', 'custom'),
        'rtmp_endpoint' => env('WEBINAR_RTMP_ENDPOINT'),
        'zoom' => [
            'enabled' => env('WEBINAR_ZOOM_ENABLED', false),
            'api_key' => env('WEBINAR_ZOOM_KEY'),
            'api_secret' => env('WEBINAR_ZOOM_SECRET'),
        ],
        'teams' => [
            'enabled' => env('WEBINAR_TEAMS_ENABLED', false),
            'tenant' => env('WEBINAR_TEAMS_TENANT'),
            'client_id' => env('WEBINAR_TEAMS_CLIENT_ID'),
            'client_secret' => env('WEBINAR_TEAMS_CLIENT_SECRET'),
        ],
    ],

    'networking' => [
        'rotation_intervals' => [120, 300],
        'max_participants' => 100,
        'allow_contact_sharing' => true,
    ],

    'interviews' => [
        'default_scoring_matrix' => [
            'culture_fit' => 5,
            'communication' => 5,
            'technical' => 5,
            'problem_solving' => 5,
        ],
        'allow_file_uploads' => true,
        'calendar_integration' => true,
    ],

    'recording' => [
        'enabled' => true,
        'storage_disk' => env('WEBINAR_MEDIA_DISK', 'public'),
        'path' => 'events/media',
    ],

    'seeder_host_id' => env('WNIP_SEEDER_HOST_ID', 1),
];

