<?php

return [
    'roles' => [
        'employer' => 'employer',
        'recruiter' => 'recruiter',
        'seeker' => 'job-seeker',
        'company_admin' => 'company-admin',
        'platform_admin' => 'platform-admin',
    ],
    'features' => [
        'enabled' => true,
        'ats' => true,
        'cv_builder' => true,
        'cover_letters' => true,
        'screening_questions' => true,
        'subscriptions' => true,
        'interviews' => true,
        'alerts' => true,
        'recommendations' => true,
    ],
    'posting' => [
        'max_active_jobs' => 5,
        'default_expiration_days' => 30,
    ],
    'defaults' => [
        'pagination' => 15,
        'application_limit' => 10,
        'statuses' => [
            'job' => ['draft', 'published', 'closed', 'archived'],
            'application' => ['applied', 'screening', 'interview', 'offer', 'rejected', 'hired', 'withdrawn'],
        ],
    ],
    'middleware' => [
        'web' => ['web', 'auth', 'verified'],
        'web_protected' => ['web', 'auth', 'verified'],
        'api' => ['api', 'auth:sanctum'],
        'api_protected' => ['api', 'auth:sanctum'],
    ],
    'prefixes' => [
        'web' => 'jobs',
        'api' => 'api',
    ],
    'plans' => [
        'free' => [
            'label' => 'Free',
            'job_credits' => 1,
            'features' => ['basic_listing'],
        ],
        'pro' => [
            'label' => 'Pro',
            'job_credits' => 10,
            'features' => ['featured_listing', 'cv_search', 'ats'],
        ],
    ],
];
