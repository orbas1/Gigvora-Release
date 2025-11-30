<?php

return [
    'defaults' => [
        'fallback_role' => 'member',
        'global_admin_roles' => ['platform_admin'],
        'actor_key' => 'user_id',
    ],

    'roles' => [
        'member' => [
            'label' => 'Member',
            'description' => 'Default member identity used across feed, profile, and utilities.',
        ],
        'freelancer' => [
            'label' => 'Freelancer',
            'description' => 'Independent talent with access to freelance dashboards, gigs, and contracts.',
        ],
        'recruiter' => [
            'label' => 'Recruiter',
            'description' => 'Hiring-focused role responsible for jobs, campaigns, and sourcing.',
        ],
        'company_admin' => [
            'label' => 'Company Admin',
            'description' => 'Owns company-wide administration for jobs, ads, and billing.',
        ],
        'creator' => [
            'label' => 'Creator',
            'description' => 'Hosts live sessions, publishes media, and collaborates with sponsors.',
        ],
        'moderator' => [
            'label' => 'Moderator',
            'description' => 'Handles abuse, safety escalations, and policy enforcement.',
        ],
        'platform_admin' => [
            'label' => 'Platform Admin',
            'description' => 'Full-platform administrator with override access.',
        ],
    ],

    'permissions' => [
        'manage_advertisement' => [
            'label' => 'Ads Manager',
            'description' => 'Create, review, and approve advertisements and sponsorships.',
            'roles' => ['company_admin', 'recruiter', 'platform_admin'],
            'addons' => ['ads'],
        ],
        'manage_talent_ai' => [
            'label' => 'Talent & AI Admin',
            'description' => 'Configure and operate the Talent & AI addon experiences.',
            'roles' => ['recruiter', 'company_admin', 'platform_admin'],
            'addons' => ['talent_ai'],
        ],
        'access_admin_panel' => [
            'label' => 'Admin Panel',
            'description' => 'Access cross-addon admin surfaces, dashboards, and runbooks.',
            'roles' => ['company_admin', 'moderator', 'platform_admin'],
        ],
        'manage_system_settings' => [
            'label' => 'System Settings',
            'description' => 'Change feature flags, environment toggles, and schema-altering settings.',
            'roles' => ['platform_admin'],
        ],
        'viewAnalytics' => [
            'label' => 'Analytics Oversight',
            'description' => 'Read-only visibility into analytics dashboards across addons.',
            'roles' => ['company_admin', 'recruiter', 'platform_admin'],
        ],
        'viewSecurity' => [
            'label' => 'Security Oversight',
            'description' => 'Review audit logs, GDPR tooling, and risk controls.',
            'roles' => ['moderator', 'platform_admin'],
        ],
        'moderate' => [
            'label' => 'Moderation',
            'description' => 'Perform safety actions such as suspensions, takedowns, and abuse review.',
            'roles' => ['moderator', 'platform_admin'],
        ],
    ],

    'analytics' => [
        'namespace' => 'analytics',
        'events' => [
            'navigation' => [
                'rendered' => 'analytics.navigation.rendered',
            ],
            'freelance' => [
                'dashboard_view' => 'analytics.freelance.dashboard.view',
                'role_switched' => 'analytics.freelance.role.switched',
                'favourite_toggled' => 'analytics.freelance.favourite.toggled',
            ],
            'ads' => [
                'dashboard_view' => 'analytics.ads.dashboard.view',
                'campaign_created' => 'analytics.ads.campaign.created',
            ],
            'talent_ai' => [
                'admin_opened' => 'analytics.talent_ai.admin.opened',
            ],
            'admin' => [
                'settings_viewed' => 'analytics.admin.settings.viewed',
                'security_reviewed' => 'analytics.admin.security.reviewed',
            ],
        ],
    ],
];
