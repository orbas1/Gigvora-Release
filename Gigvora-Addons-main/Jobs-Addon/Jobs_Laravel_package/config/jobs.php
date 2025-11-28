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
        'internal_applications' => true,
        'external_applications' => true,
        'referrals' => false,
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
            'job' => ['draft', 'published', 'closed', 'archived', 'internal', 'external'],
            'application' => ['applied', 'screening', 'interview', 'offer', 'rejected', 'hired', 'withdrawn'],
        ],
        'rate_limits' => [
            'web' => '60,1',
            'api' => '60,1',
        ],
        'company_profile_required' => true,
    ],
    'middleware' => [
        'web' => ['web', 'auth', 'verified', 'locale'],
        'web_protected' => ['web', 'auth', 'verified', 'locale'],
        'api' => ['api', 'auth:sanctum', 'locale'],
        'api_protected' => ['api', 'auth:sanctum', 'locale'],
    ],
    'prefixes' => [
        'web' => 'jobs',
        'api' => 'api/jobs',
    ],
    'events' => [
        'job_posted' => 'jobs.job_posted',
        'job_updated' => 'jobs.job_updated',
        'job_closed' => 'jobs.job_closed',
        'application_submitted' => 'jobs.application_submitted',
        'application_status_changed' => 'jobs.application_status_changed',
        'job_alert_triggered' => 'jobs.job_alert_triggered',
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
