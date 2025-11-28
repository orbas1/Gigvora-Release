<?php

return [
    'roles' => [
        'employer' => 'employer',
        'recruiter' => 'recruiter',
        'seeker' => 'job-seeker',
    ],
    'features' => [
        'ats' => true,
        'cv_builder' => true,
        'cover_letters' => true,
        'screening_questions' => true,
        'subscriptions' => true,
        'interviews' => true,
    ],
    'posting' => [
        'max_active_jobs' => 5,
        'default_expiration_days' => 30,
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
