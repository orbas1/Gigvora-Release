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
        // Jobs
        'jobs.apply' => [
            'label' => 'Apply to jobs',
            'description' => 'Submit applications and track your own job pipeline.',
            'roles' => ['member', 'freelancer'],
            'addons' => ['jobs'],
        ],
        'jobs.create_posting' => [
            'label' => 'Create job postings',
            'description' => 'Create, publish, and close job postings for a company.',
            'roles' => ['recruiter', 'company_admin'],
            'addons' => ['jobs'],
        ],
        'jobs.manage_applicants' => [
            'label' => 'Manage job applicants',
            'description' => 'Screen, shortlist, and offer jobs to applicants.',
            'roles' => ['recruiter', 'company_admin'],
            'addons' => ['jobs'],
        ],
        'jobs.view_reporting' => [
            'label' => 'View job analytics',
            'description' => 'Access job funnel and conversion reporting.',
            'roles' => ['recruiter', 'company_admin', 'platform_admin'],
            'addons' => ['jobs'],
        ],

        // Freelance
        'freelance.workspace.access' => [
            'label' => 'Access freelance workspace',
            'description' => 'Open freelancer/client dashboards and snapshots.',
            'roles' => ['freelancer', 'member', 'company_admin'],
            'addons' => ['freelance'],
        ],
        'freelance.gig.manage' => [
            'label' => 'Manage gigs',
            'description' => 'Create gigs and proposals, award and accept contracts.',
            'roles' => ['freelancer'],
            'addons' => ['freelance'],
        ],
        'freelance.client.projects' => [
            'label' => 'Manage client projects',
            'description' => 'Create projects, review proposals, and fund escrows.',
            'roles' => ['company_admin', 'recruiter'],
            'addons' => ['freelance'],
        ],
        'freelance.disputes.moderate' => [
            'label' => 'Moderate freelance disputes',
            'description' => 'Review disputes and release or claw back funds.',
            'roles' => ['moderator', 'platform_admin'],
            'addons' => ['freelance'],
        ],
        'freelance.favourites.toggle' => [
            'label' => 'Toggle favourites',
            'description' => 'Save and unsave gigs or projects.',
            'roles' => ['freelancer', 'member', 'company_admin'],
            'addons' => ['freelance'],
        ],

        // Ads
        'ads.manage_campaigns' => [
            'label' => 'Manage ad campaigns',
            'description' => 'Create and manage ad or sponsorship campaigns.',
            'roles' => ['company_admin', 'recruiter'],
            'addons' => ['ads'],
        ],
        'ads.view_reports' => [
            'label' => 'View ad reports',
            'description' => 'Access ad performance, impressions, and spend.',
            'roles' => ['company_admin', 'recruiter', 'platform_admin'],
            'addons' => ['ads'],
        ],

        // Talent & AI
        'talent_ai.run_search' => [
            'label' => 'Run AI talent search',
            'description' => 'Search candidate pools and save results.',
            'roles' => ['recruiter', 'company_admin'],
            'addons' => ['talent_ai'],
        ],
        'talent_ai.configure_scoring' => [
            'label' => 'Configure scoring',
            'description' => 'Edit AI scoring matrices and sourcing filters.',
            'roles' => ['company_admin', 'platform_admin'],
            'addons' => ['talent_ai'],
        ],

        // Interactive
        'interactive.host_session' => [
            'label' => 'Host live sessions',
            'description' => 'Create and host webinars, networking, podcasts, and interviews.',
            'roles' => ['creator', 'company_admin'],
            'addons' => ['interactive'],
        ],
        'interactive.join_session' => [
            'label' => 'Join live sessions',
            'description' => 'Register for and join interactive sessions.',
            'roles' => ['member', 'freelancer', 'recruiter', 'company_admin'],
            'addons' => ['interactive'],
        ],
        'interactive.moderate_session' => [
            'label' => 'Moderate sessions',
            'description' => 'Manage chat, safety, and participant states.',
            'roles' => ['moderator', 'platform_admin'],
            'addons' => ['interactive'],
        ],

        // Utilities
        'utilities.manage_calendar' => [
            'label' => 'Manage calendar & reminders',
            'description' => 'Create reminders, sync calendars, and manage saved items.',
            'roles' => ['member', 'freelancer', 'recruiter', 'company_admin', 'creator'],
            'addons' => ['utilities'],
        ],
        'utilities.notifications' => [
            'label' => 'Notifications center',
            'description' => 'Access notifications, bookmarks, and follow-ups.',
            'roles' => ['member', 'freelancer', 'recruiter', 'company_admin', 'creator'],
            'addons' => ['utilities'],
        ],

        // AI addon
        'ai.generators.use' => [
            'label' => 'Use AI generators',
            'description' => 'Trigger AI actions such as descriptions, searches, and interview prep.',
            'roles' => ['freelancer', 'recruiter', 'company_admin', 'creator'],
            'addons' => ['ai', 'talent_ai'],
        ],

        // Platform / Admin
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
                'proposal_submitted' => 'analytics.freelance.proposal.submitted',
                'contract_created' => 'analytics.freelance.contract.created',
                'escrow_released' => 'analytics.freelance.escrow.released',
            ],
            'ads' => [
                'dashboard_view' => 'analytics.ads.dashboard.view',
                'campaign_created' => 'analytics.ads.campaign.created',
                'budget_updated' => 'analytics.ads.budget.updated',
                'report_viewed' => 'analytics.ads.report.viewed',
            ],
            'talent_ai' => [
                'admin_opened' => 'analytics.talent_ai.admin.opened',
                'search_run' => 'analytics.talent_ai.search.run',
                'candidate_saved' => 'analytics.talent_ai.candidate.saved',
            ],
            'admin' => [
                'settings_viewed' => 'analytics.admin.settings.viewed',
                'security_reviewed' => 'analytics.admin.security.reviewed',
                'role_changed' => 'analytics.admin.role.changed',
                'permission_changed' => 'analytics.admin.permission.changed',
            ],
            'jobs' => [
                'job_posted' => 'analytics.jobs.job.posted',
                'application_submitted' => 'analytics.jobs.application.submitted',
                'candidate_shortlisted' => 'analytics.jobs.candidate.shortlisted',
                'offer_sent' => 'analytics.jobs.offer.sent',
            ],
            'interactive' => [
                'session_created' => 'analytics.interactive.session.created',
                'session_joined' => 'analytics.interactive.session.joined',
                'recording_viewed' => 'analytics.interactive.recording.viewed',
            ],
            'utilities' => [
                'reminder_created' => 'analytics.utilities.reminder.created',
                'calendar_synced' => 'analytics.utilities.calendar.synced',
                'saved_item_added' => 'analytics.utilities.saved_item.added',
            ],
            'ai' => [
                'generator_used' => 'analytics.ai.generator.used',
                'prompt_submitted' => 'analytics.ai.prompt.submitted',
            ],
        ],
    ],
];
