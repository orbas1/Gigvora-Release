<?php

return [
    'features' => [
        'enabled' => (bool) env('JOBS_ENABLED', true),
        'internal_applications' => (bool) env('JOBS_FEATURE_INTERNAL_APPLICATIONS', true),
        'external_applications' => (bool) env('JOBS_FEATURE_EXTERNAL_APPLICATIONS', true),
        'referrals' => (bool) env('JOBS_FEATURE_REFERRALS', false),
        'ats' => (bool) env('JOBS_FEATURE_ATS', true),
        'cv_builder' => (bool) env('JOBS_FEATURE_CV_BUILDER', true),
        'cover_letters' => (bool) env('JOBS_FEATURE_COVER_LETTERS', true),
        'screening_questions' => (bool) env('JOBS_FEATURE_SCREENING', true),
        'subscriptions' => (bool) env('JOBS_FEATURE_SUBSCRIPTIONS', true),
        'interviews' => (bool) env('JOBS_FEATURE_INTERVIEWS', true),
        'alerts' => (bool) env('JOBS_FEATURE_ALERTS', true),
        'recommendations' => (bool) env('JOBS_FEATURE_RECOMMENDATIONS', true),
    ],

    'roles' => [
        'job_seeker' => env('JOBS_ROLE_SEEKER', 'general'),
        'employer' => env('JOBS_ROLE_EMPLOYER', 'company'),
        'recruiter' => env('JOBS_ROLE_RECRUITER', 'recruiter'),
        'company_admin' => env('JOBS_ROLE_COMPANY_ADMIN', 'company_admin'),
        'platform_admin' => env('JOBS_ROLE_PLATFORM_ADMIN', 'admin'),
        'employer_access' => array_filter([
            env('JOBS_ROLE_EMPLOYER', 'company'),
            env('JOBS_ROLE_RECRUITER', 'recruiter'),
            env('JOBS_ROLE_COMPANY_ADMIN', 'company_admin'),
            'admin',
        ]),
    ],

    'posting' => [
        'max_active_jobs' => (int) env('JOBS_MAX_ACTIVE', 25),
        'default_expiration_days' => (int) env('JOBS_DEFAULT_EXPIRATION', 45),
    ],

    'defaults' => [
        'pagination' => (int) env('JOBS_PAGINATION', 15),
        'application_limit' => (int) env('JOBS_APPLICATION_LIMIT', 10),
        'statuses' => [
            'job' => ['draft', 'published', 'paused', 'closed', 'archived', 'internal', 'external'],
            'application' => ['applied', 'screening', 'shortlisted', 'interview', 'offer', 'hired', 'rejected', 'withdrawn'],
        ],
        'rate_limits' => [
            'web' => env('JOBS_RATE_LIMIT_WEB', '120,1'),
            'api' => env('JOBS_RATE_LIMIT_API', '120,1'),
        ],
        'company_profile_required' => (bool) env('JOBS_COMPANY_PROFILE_REQUIRED', true),
    ],

    'middleware' => [
        'web' => ['web', 'auth', 'verified', 'activity'],
        'web_protected' => ['web', 'auth', 'verified', 'activity', 'prevent-back-history'],
        'api' => ['api'],
        'api_protected' => ['api', 'auth:sanctum'],
    ],

    'prefixes' => [
        'web' => env('JOBS_WEB_PREFIX', 'jobs'),
        'api' => env('JOBS_API_PREFIX', 'api/jobs'),
    ],

    'integrations' => [
        'utilities' => [
            'notifications' => (bool) env('JOBS_UTILITIES_NOTIFICATIONS', true),
            'calendar' => (bool) env('JOBS_UTILITIES_CALENDAR', true),
            'bookmarks' => (bool) env('JOBS_UTILITIES_BOOKMARKS', true),
        ],
        'interactive' => [
            'interview_route' => env('JOBS_INTERACTIVE_INTERVIEW_ROUTE', 'wnip.interviews.index'),
        ],
        'profile' => [
            'history_component' => env('JOBS_PROFILE_HISTORY_COMPONENT', 'components.profile.history.jobs'),
        ],
    ],

    'events' => [
        'job_posted' => 'jobs.job_posted',
        'job_updated' => 'jobs.job_updated',
        'job_closed' => 'jobs.job_closed',
        'application_submitted' => 'jobs.application_submitted',
        'application_status_changed' => 'jobs.application_status_changed',
        'job_alert_triggered' => 'jobs.job_alert_triggered',
    ],
];

