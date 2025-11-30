<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class UtilitiesQuickToolsService
{
    public const CONTEXT_GLOBAL = 'global';
    public const CONTEXT_FEED = 'feed';
    public const CONTEXT_PROFILE = 'profile';
    public const CONTEXT_JOBS = 'jobs';
    public const CONTEXT_JOB_DETAIL = 'job_detail';
    public const CONTEXT_FREELANCE = 'freelance';
    public const CONTEXT_INTERACTIVE = 'interactive';
    public const CONTEXT_PAGE = 'page';
    public const CONTEXT_GROUP = 'group';
    public const CONTEXT_MARKETPLACE = 'marketplace';
    public const CONTEXT_MARKETPLACE_MANAGER = 'marketplace_manager';
    public const CONTEXT_ADMIN = 'admin';
    public const CONTEXT_TALENT_AI = 'talent_ai';

    protected array $features;
    protected ?Request $request;

    public function __construct(?Request $request = null)
    {
        $this->request = $request;
        $this->features = config('pro_network_utilities_security_analytics.features', []);
    }

    public function detectContext(?Request $request = null): string
    {
        $request ??= $this->request ?? request();

        if (! $request) {
            return self::CONTEXT_GLOBAL;
        }

        $route = $request->route();

        if ($route && $route->named('jobs.show')) {
            return self::CONTEXT_JOB_DETAIL;
        }

        if ($route && $route->named('jobs.*')) {
            return self::CONTEXT_JOBS;
        }

        if ($route && $route->named('profile*')) {
            return self::CONTEXT_PROFILE;
        }

        if ($route && $route->named('freelance.*')) {
            return self::CONTEXT_FREELANCE;
        }

        if ($route && $route->named('addons.talent_ai.*')) {
            return self::CONTEXT_TALENT_AI;
        }

        if ($request->is('addons/talent-ai/*')) {
            return self::CONTEXT_TALENT_AI;
        }

        if ($route && ($route->named('liveCenter.*') || $route->named('wnip.*'))) {
            return self::CONTEXT_INTERACTIVE;
        }

        if ($request->is('live-center/*')) {
            return self::CONTEXT_INTERACTIVE;
        }

        if ($request->is('freelance/*')) {
            return self::CONTEXT_FREELANCE;
        }

        if ($route && $route->named('single.page*')) {
            return self::CONTEXT_PAGE;
        }

        if ($route && $route->named('single.group*')) {
            return self::CONTEXT_GROUP;
        }

        if ($request->is('jobs/*')) {
            return self::CONTEXT_JOBS;
        }

        if ($request->is('admin/*') || ($route && $route->named('advertisement.*'))) {
            return self::CONTEXT_ADMIN;
        }

        if ($route && $route->named('userproduct')) {
            return self::CONTEXT_MARKETPLACE_MANAGER;
        }

        if ($route && $route->named('allproducts')) {
            return self::CONTEXT_MARKETPLACE;
        }

        if ($request->is('marketplace/*') || $request->is('products') || $request->is('productdata')) {
            return self::CONTEXT_MARKETPLACE;
        }

        if ($route && $route->named('utilities.*')) {
            return self::CONTEXT_GLOBAL;
        }

        if ($route && $route->named('dashboard')) {
            return self::CONTEXT_FEED;
        }

        if (Str::contains($request->path(), 'feed') || $request->is('timeline')) {
            return self::CONTEXT_FEED;
        }

        return self::CONTEXT_GLOBAL;
    }

    public function actionsFor(?string $context = null): array
    {
        if (! Auth::check()) {
            return [];
        }

        $contextKey = $context ? strtolower($context) : $this->detectContext();
        $actions = array_merge($this->baseActions(), $this->contextualActions($contextKey));

        return collect($actions)
            ->filter()
            ->unique('id')
            ->values()
            ->all();
    }

    public function labelFor(string $context): string
    {
        return match ($context) {
            self::CONTEXT_FEED => get_phrase('Feed quick tools'),
            self::CONTEXT_PROFILE => get_phrase('Profile quick tools'),
            self::CONTEXT_JOBS => get_phrase('Jobs quick tools'),
            self::CONTEXT_JOB_DETAIL => get_phrase('Role follow-ups'),
            self::CONTEXT_FREELANCE => get_phrase('Freelance quick tools'),
            self::CONTEXT_INTERACTIVE => get_phrase('Live & events quick tools'),
            self::CONTEXT_PAGE => get_phrase('Page quick tools'),
            self::CONTEXT_GROUP => get_phrase('Group quick tools'),
            self::CONTEXT_MARKETPLACE => get_phrase('Marketplace quick tools'),
            self::CONTEXT_MARKETPLACE_MANAGER => get_phrase('Marketplace manager tools'),
            self::CONTEXT_ADMIN => get_phrase('Admin utilities'),
            self::CONTEXT_TALENT_AI => get_phrase('Talent & AI quick tools'),
            default => get_phrase('Utilities quick tools'),
        };
    }

    public function descriptionFor(string $context): string
    {
        return match ($context) {
            self::CONTEXT_FEED => get_phrase('Schedule reminders, launch polls, and jump to utilities while browsing the feed.'),
            self::CONTEXT_PROFILE => get_phrase('Enhance stories, explore hashtags, and manage reminders without leaving your profile.'),
            self::CONTEXT_JOBS => get_phrase('Manage job alerts, saved roles, interview timelines, and ATS shortcuts.'),
            self::CONTEXT_JOB_DETAIL => get_phrase('Bookmark this role, track interview prep, and create follow-up reminders.'),
            self::CONTEXT_FREELANCE => get_phrase('Open dashboards for gigs, projects, disputes, and escrow hand-offs.'),
            self::CONTEXT_INTERACTIVE => get_phrase('Save sessions, queue reminders, and jump into webinars, networking, or replays.'),
            self::CONTEXT_PAGE => get_phrase('Boost announcements, launch jobs/events, and open analytics for this page.'),
            self::CONTEXT_GROUP => get_phrase('Invite members, share jobs/events, and keep moderation tools handy.'),
            self::CONTEXT_MARKETPLACE => get_phrase('Bookmark listings, set restock alerts, and open dispute tooling.'),
            self::CONTEXT_MARKETPLACE_MANAGER => get_phrase('Create listings, monitor analytics, and jump to dispute/escrow tools.'),
            self::CONTEXT_ADMIN => get_phrase('Reach analytics, moderation, ads, and security tooling from one strip.'),
            self::CONTEXT_TALENT_AI => get_phrase('Jump between headhunter, Launchpad, volunteering, and AI workspace views.'),
            default => get_phrase('Access notifications, saved items, and reminders from anywhere.'),
        };
    }

    public function integrationLinks(): array
    {
        return [
            'jobsSaved' => $this->route('jobs.saved'),
            'freelanceDashboard' => $this->route('freelance.dashboard'),
            'liveHub' => $this->route('liveCenter.hub') ?? $this->route('wnip.webinars.index'),
            'utilitiesHub' => $this->route('utilities.hub'),
        ];
    }

    protected function baseActions(): array
    {
        return array_filter([
            $this->makeAction([
                'id' => 'utilities.notifications',
                'label' => get_phrase('Notifications center'),
                'description' => get_phrase('See invites and approvals from Jobs, Freelance, Live, and groups.'),
                'route' => $this->route('utilities.notifications.index'),
                'icon' => 'fa-regular fa-bell',
                'icon_key' => 'bell',
                'feature' => 'utilities_notifications_center',
            ]),
            $this->makeAction([
                'id' => 'utilities.saved',
                'label' => get_phrase('Saved items'),
                'description' => get_phrase('Review bookmarked posts, jobs, gigs, videos, and products.'),
                'route' => $this->route('utilities.saved.index'),
                'icon' => 'fa-regular fa-bookmark',
                'icon_key' => 'bookmark',
                'feature' => 'utilities_bookmarks',
            ]),
            $this->makeAction([
                'id' => 'utilities.calendar',
                'label' => get_phrase('Calendar & reminders'),
                'description' => get_phrase('Plan interviews, webinars, networking, and follow-up tasks.'),
                'route' => $this->route('utilities.calendar.index'),
                'icon' => 'fa-regular fa-calendar',
                'icon_key' => 'calendar',
                'feature' => 'utilities_calendar',
            ]),
            $this->makeAction([
                'id' => 'utilities.hub',
                'label' => get_phrase('Utilities hub'),
                'description' => get_phrase('Open the full Utilities hub for professional upgrades and tooling.'),
                'route' => $this->route('utilities.hub'),
                'icon' => 'fa-solid fa-bolt',
                'icon_key' => 'bolt',
            ]),
        ]);
    }

    protected function contextualActions(string $context): array
    {
        return match ($context) {
            self::CONTEXT_FEED => array_filter([
                $this->makeAction([
                    'id' => 'feed.poll',
                    'label' => get_phrase('Create poll'),
                    'description' => get_phrase('Survey your network directly from the feed.'),
                    'route' => $this->route('utilities.posts.poll'),
                    'icon' => 'fa-solid fa-square-poll-vertical',
                    'icon_key' => 'poll',
                    'feature' => 'post_enhancements',
                ]),
                $this->makeAction([
                    'id' => 'feed.thread',
                    'label' => get_phrase('Start a thread'),
                    'description' => get_phrase('Publish multi-step narratives with Utilities formatting.'),
                    'route' => $this->route('utilities.posts.thread'),
                    'icon' => 'fa-solid fa-bars-progress',
                    'icon_key' => 'thread',
                    'feature' => 'post_enhancements',
                ]),
                $this->makeAction([
                    'id' => 'feed.reminder',
                    'label' => get_phrase('Schedule reminder'),
                    'description' => get_phrase('Capture follow-ups for jobs, gigs, or posts you see.'),
                    'route' => $this->route('utilities.calendar.index') ? $this->route('utilities.calendar.index').'#feed' : null,
                    'icon' => 'fa-regular fa-clock',
                    'icon_key' => 'clock',
                    'feature' => 'utilities_calendar',
                ]),
                $this->makeAction([
                    'id' => 'feed.story',
                    'label' => get_phrase('Story enhancer'),
                    'description' => get_phrase('Launch the Instagram-style story creator.'),
                    'route' => $this->route('utilities.stories.create'),
                    'icon' => 'fa-solid fa-wand-magic-sparkles',
                    'icon_key' => 'wand',
                    'feature' => 'stories_wrapper',
                ]),
            ]),
            self::CONTEXT_PROFILE => array_filter([
                $this->makeAction([
                    'id' => 'profile.professional',
                    'label' => get_phrase('Upgrade profile'),
                    'description' => get_phrase('Manage headline, availability, and showcase cards.'),
                    'route' => $this->route('utilities.professional'),
                    'icon' => 'fa-solid fa-id-card-clip',
                    'icon_key' => 'id-card',
                    'feature' => 'profile_professional_upgrades',
                ]),
                $this->makeAction([
                    'id' => 'profile.hashtags',
                    'label' => get_phrase('Hashtag explorer'),
                    'description' => get_phrase('Discover trending skills and categories.'),
                    'route' => $this->route('utilities.hashtags'),
                    'icon' => 'fa-solid fa-hashtag',
                    'icon_key' => 'hashtag',
                    'feature' => 'hashtags',
                ]),
                $this->makeAction([
                    'id' => 'profile.story',
                    'label' => get_phrase('Story enhancer'),
                    'description' => get_phrase('Add reels, music, and overlays to stories.'),
                    'route' => $this->route('utilities.stories.create'),
                    'icon' => 'fa-solid fa-wand-magic-sparkles',
                    'icon_key' => 'wand',
                    'feature' => 'stories_wrapper',
                ]),
            ]),
            self::CONTEXT_JOBS => array_filter([
                $this->makeAction([
                    'id' => 'jobs.saved',
                    'label' => get_phrase('Saved jobs'),
                    'description' => get_phrase('Resume bookmarked roles and set reminders.'),
                    'route' => $this->route('jobs.saved'),
                    'icon' => 'fa-solid fa-briefcase',
                    'icon_key' => 'briefcase',
                ]),
                $this->makeAction([
                    'id' => 'jobs.alerts',
                    'label' => get_phrase('Job alerts & notifications'),
                    'description' => get_phrase('Tune Utilities alerts for roles and interviews.'),
                    'route' => $this->route('utilities.notifications.index')
                        ? $this->route('utilities.notifications.index').'#jobs'
                        : null,
                    'icon' => 'fa-regular fa-bell',
                    'icon_key' => 'bell',
                    'feature' => 'utilities_notifications_center',
                ]),
                $this->makeAction([
                    'id' => 'jobs.calendar',
                    'label' => get_phrase('Interview schedule'),
                    'description' => get_phrase('Send reminders for interviews and recruiter follow-ups.'),
                    'route' => $this->route('utilities.calendar.index')
                        ? $this->route('utilities.calendar.index').'#jobs'
                        : null,
                    'icon' => 'fa-regular fa-calendar',
                    'icon_key' => 'calendar',
                    'feature' => 'utilities_calendar',
                ]),
            ]),
            self::CONTEXT_JOB_DETAIL => array_filter([
                $this->makeAction([
                    'id' => 'job-detail.save',
                    'label' => get_phrase('Bookmark role'),
                    'description' => get_phrase('Store this role with other saved jobs.'),
                    'route' => $this->route('jobs.saved'),
                    'icon' => 'fa-regular fa-bookmark',
                    'icon_key' => 'bookmark',
                ]),
                $this->makeAction([
                    'id' => 'job-detail.followup',
                    'label' => get_phrase('Create follow-up reminder'),
                    'description' => get_phrase('Set a reminder to revisit this opportunity.'),
                    'route' => $this->route('utilities.calendar.index')
                        ? $this->route('utilities.calendar.index').'#follow-ups'
                        : null,
                    'icon' => 'fa-regular fa-clock',
                    'icon_key' => 'clock',
                    'feature' => 'utilities_calendar',
                ]),
            ]),
            self::CONTEXT_FREELANCE => array_filter([
                $this->makeAction([
                    'id' => 'freelance.dashboard',
                    'label' => get_phrase('Freelance dashboard'),
                    'description' => get_phrase('Jump back to gigs, projects, and contracts.'),
                    'route' => $this->route('freelance.dashboard'),
                    'icon' => 'fa-solid fa-handshake-angle',
                    'icon_key' => 'handshake',
                ]),
                $this->makeAction([
                    'id' => 'freelance.proposals',
                    'label' => get_phrase('Manage proposals'),
                    'description' => get_phrase('Review gig/project proposals and milestones.'),
                    'route' => $this->route('freelance.projects.index') ?? $this->route('freelance.projects.activity'),
                    'icon' => 'fa-solid fa-file-pen',
                    'icon_key' => 'file',
                ]),
                $this->makeAction([
                    'id' => 'freelance.disputes',
                    'label' => get_phrase('Escrow & disputes'),
                    'description' => get_phrase('Resolve disputes or release escrow milestones.'),
                    'route' => $this->route('freelance.disputes.index'),
                    'icon' => 'fa-solid fa-scale-balanced',
                    'icon_key' => 'scale',
                ]),
            ]),
            self::CONTEXT_INTERACTIVE => array_filter([
                $this->makeAction([
                    'id' => 'live.hub',
                    'label' => get_phrase('Live & events hub'),
                    'description' => get_phrase('Browse webinars, networking sessions, and replays.'),
                    'route' => $this->route('liveCenter.hub') ?? $this->route('wnip.webinars.index'),
                    'icon' => 'fa-solid fa-podcast',
                    'icon_key' => 'broadcast',
                ]),
                $this->makeAction([
                    'id' => 'live.reminders',
                    'label' => get_phrase('Session reminders'),
                    'description' => get_phrase('Schedule reminders for events you care about.'),
                    'route' => $this->route('utilities.calendar.index')
                        ? $this->route('utilities.calendar.index').'#live'
                        : null,
                    'icon' => 'fa-regular fa-calendar',
                    'icon_key' => 'calendar',
                    'feature' => 'utilities_calendar',
                ]),
                $this->makeAction([
                    'id' => 'live.saves',
                    'label' => get_phrase('Saved sessions'),
                    'description' => get_phrase('Review webinars or networking rooms you saved.'),
                    'route' => $this->route('utilities.saved.index')
                        ? $this->route('utilities.saved.index').'#live'
                        : null,
                    'icon' => 'fa-regular fa-bookmark',
                    'icon_key' => 'bookmark',
                    'feature' => 'utilities_bookmarks',
                ]),
            ]),
            self::CONTEXT_PAGE => array_filter([
                $this->makeAction([
                    'id' => 'page.jobs',
                    'label' => get_phrase('Post a job'),
                    'description' => get_phrase('Open a new role directly from this page.'),
                    'route' => $this->route('create.job') ?? $this->route('jobs.index'),
                    'icon' => 'fa-solid fa-briefcase',
                    'icon_key' => 'briefcase',
                ]),
                $this->makeAction([
                    'id' => 'page.event',
                    'label' => get_phrase('Host an event'),
                    'description' => get_phrase('Launch webinars or live sessions for your followers.'),
                    'route' => $this->route('liveCenter.hub') ?? url('/live-center/hub'),
                    'icon' => 'fa-solid fa-broadcast-tower',
                    'icon_key' => 'broadcast',
                ]),
                $this->makeAction([
                    'id' => 'page.analytics',
                    'label' => get_phrase('View analytics'),
                    'description' => get_phrase('Jump into Utilities analytics for this page.'),
                    'route' => $this->route('utilities.analytics'),
                    'icon' => 'fa-solid fa-chart-line',
                    'icon_key' => 'analytics',
                ]),
                $this->makeAction([
                    'id' => 'page.ads',
                    'label' => get_phrase('Boost announcement'),
                    'description' => get_phrase('Open Ads Manager to promote page posts.'),
                    'route' => $this->route('advertisement.dashboard'),
                    'icon' => 'fa-solid fa-bullhorn',
                    'icon_key' => 'bullhorn',
                ]),
                $this->makeAction([
                    'id' => 'page.reminders',
                    'label' => get_phrase('Schedule reminder'),
                    'description' => get_phrase('Set reminders for launches or announcements.'),
                    'route' => $this->route('utilities.calendar.index'),
                    'icon' => 'fa-regular fa-clock',
                    'icon_key' => 'clock',
                    'feature' => 'utilities_calendar',
                ]),
            ]),
            self::CONTEXT_GROUP => array_filter([
                $this->makeAction([
                    'id' => 'group.invite',
                    'label' => get_phrase('Invite members'),
                    'description' => get_phrase('Open the group directory to add members.'),
                    'route' => $this->route('groups') ?? url('/group'),
                    'icon' => 'fa-solid fa-user-plus',
                    'icon_key' => 'people',
                ]),
                $this->makeAction([
                    'id' => 'group.moderation',
                    'label' => get_phrase('Moderation center'),
                    'description' => get_phrase('Review reports & disputes inside Utilities.'),
                    'route' => $this->route('utilities.moderation'),
                    'icon' => 'fa-solid fa-gavel',
                    'icon_key' => 'gavel',
                    'ability' => 'moderate',
                ]),
                $this->makeAction([
                    'id' => 'group.jobs',
                    'label' => get_phrase('Share a role'),
                    'description' => get_phrase('Create a job or gig to share with the group.'),
                    'route' => $this->route('create.job') ?? $this->route('jobs.index'),
                    'icon' => 'fa-solid fa-briefcase',
                    'icon_key' => 'briefcase',
                ]),
                $this->makeAction([
                    'id' => 'group.event',
                    'label' => get_phrase('Schedule group event'),
                    'description' => get_phrase('Create a meet-up or live room.'),
                    'route' => $this->route('event') ?? url('/events'),
                    'icon' => 'fa-solid fa-calendar-plus',
                    'icon_key' => 'calendar',
                ]),
                $this->makeAction([
                    'id' => 'group.reminder',
                    'label' => get_phrase('Set reminder'),
                    'description' => get_phrase('Plan announcements or moderation tasks.'),
                    'route' => $this->route('utilities.calendar.index'),
                    'icon' => 'fa-regular fa-clock',
                    'icon_key' => 'clock',
                    'feature' => 'utilities_calendar',
                ]),
            ]),
            self::CONTEXT_MARKETPLACE => array_filter([
                $this->makeAction([
                    'id' => 'marketplace.saved',
                    'label' => get_phrase('Saved items'),
                    'description' => get_phrase('Open your Utilities saved products list.'),
                    'route' => $this->route('utilities.saved.index'),
                    'icon' => 'fa-regular fa-bookmark',
                    'icon_key' => 'bookmark',
                    'feature' => 'utilities_bookmarks',
                ]),
                $this->makeAction([
                    'id' => 'marketplace.reminder',
                    'label' => get_phrase('Set restock alert'),
                    'description' => get_phrase('Use Utilities calendar to remind yourself about a listing.'),
                    'route' => $this->route('utilities.calendar.index'),
                    'icon' => 'fa-regular fa-clock',
                    'icon_key' => 'clock',
                    'feature' => 'utilities_calendar',
                ]),
                $this->makeAction([
                    'id' => 'marketplace.dispute',
                    'label' => get_phrase('Open dispute center'),
                    'description' => get_phrase('Review Utilities moderation/dispute tools.'),
                    'route' => $this->route('utilities.moderation'),
                    'icon' => 'fa-solid fa-scale-balanced',
                    'icon_key' => 'scale',
                    'ability' => 'moderate',
                ]),
            ]),
            self::CONTEXT_MARKETPLACE_MANAGER => array_filter([
                $this->makeAction([
                    'id' => 'marketplace.manage',
                    'label' => get_phrase('Manage listings'),
                    'description' => get_phrase('Jump to your marketplace dashboard.'),
                    'route' => $this->route('userproduct'),
                    'icon' => 'fa-solid fa-store',
                    'icon_key' => 'business',
                ]),
                $this->makeAction([
                    'id' => 'marketplace.create',
                    'label' => get_phrase('Create listing'),
                    'description' => get_phrase('Add a new product to the marketplace.'),
                    'route' => $this->route('userproduct'),
                    'icon' => 'fa-solid fa-plus',
                    'icon_key' => 'bolt',
                ]),
                $this->makeAction([
                    'id' => 'marketplace.analytics',
                    'label' => get_phrase('Marketplace analytics'),
                    'description' => get_phrase('Monitor listing performance via Utilities analytics.'),
                    'route' => $this->route('utilities.analytics'),
                    'icon' => 'fa-solid fa-chart-line',
                    'icon_key' => 'analytics',
                ]),
                $this->makeAction([
                    'id' => 'marketplace.reminders',
                    'label' => get_phrase('Schedule follow-ups'),
                    'description' => get_phrase('Set reminders for buyer outreach or restocks.'),
                    'route' => $this->route('utilities.calendar.index'),
                    'icon' => 'fa-regular fa-clock',
                    'icon_key' => 'clock',
                    'feature' => 'utilities_calendar',
                ]),
                $this->makeAction([
                    'id' => 'marketplace.disputes',
                    'label' => get_phrase('Escrow & disputes'),
                    'description' => get_phrase('Open Utilities moderation to handle escalations.'),
                    'route' => $this->route('utilities.moderation'),
                    'icon' => 'fa-solid fa-scale-balanced',
                    'icon_key' => 'scale',
                    'ability' => 'moderate',
                ]),
            ]),
            self::CONTEXT_ADMIN => array_filter([
                $this->makeAction([
                    'id' => 'admin.analytics',
                    'label' => get_phrase('Analytics hub'),
                    'description' => get_phrase('Open Utilities analytics for cross-addon metrics.'),
                    'route' => $this->route('utilities.analytics'),
                    'icon' => 'fa-solid fa-chart-line',
                    'icon_key' => 'analytics',
                    'ability' => 'viewAnalytics',
                ]),
                $this->makeAction([
                    'id' => 'admin.security',
                    'label' => get_phrase('Security log'),
                    'description' => get_phrase('Audit brute-force attempts and sensitive events.'),
                    'route' => $this->route('utilities.security'),
                    'icon' => 'fa-solid fa-shield-halved',
                    'icon_key' => 'shield',
                    'ability' => 'viewSecurity',
                ]),
                $this->makeAction([
                    'id' => 'admin.moderation',
                    'label' => get_phrase('Moderation queue'),
                    'description' => get_phrase('Jump to flagged content, disputes, and reports.'),
                    'route' => $this->route('utilities.moderation'),
                    'icon' => 'fa-solid fa-gavel',
                    'icon_key' => 'gavel',
                    'ability' => 'moderate',
                ]),
                $this->makeAction([
                    'id' => 'admin.ads',
                    'label' => get_phrase('Ads manager'),
                    'description' => get_phrase('Review campaigns, pacing, and billing.'),
                    'route' => config('advertisement.enabled') ? $this->route('advertisement.dashboard') : null,
                    'icon' => 'fa-solid fa-bullhorn',
                    'icon_key' => 'bullhorn',
                    'condition' => fn () => (bool) config('advertisement.enabled'),
                ]),
            ]),
            self::CONTEXT_TALENT_AI => array_filter([
                $this->makeAction([
                    'id' => 'talent_ai.headhunters',
                    'label' => get_phrase('Headhunter dashboard'),
                    'description' => get_phrase('Review mandates, candidates, and pipeline stages.'),
                    'route' => $this->route('addons.talent_ai.headhunters.dashboard'),
                    'icon' => 'fa-solid fa-user-tie',
                    'icon_key' => 'work',
                    'condition' => fn () => (bool) config('gigvora_talent_ai.enabled'),
                ]),
                $this->makeAction([
                    'id' => 'talent_ai.launchpad',
                    'label' => get_phrase('Launchpad programmes'),
                    'description' => get_phrase('Create or progress cohorts, tasks, and interviews.'),
                    'route' => $this->route('addons.talent_ai.launchpad.programmes.index'),
                    'icon' => 'fa-solid fa-graduation-cap',
                    'icon_key' => 'school',
                    'condition' => fn () => (bool) data_get(config('gigvora_talent_ai'), 'modules.launchpad.enabled', false),
                ]),
                $this->makeAction([
                    'id' => 'talent_ai.ai_workspace',
                    'label' => get_phrase('AI workspace'),
                    'description' => get_phrase('Run resume, outreach, coach, and marketing tools.'),
                    'route' => $this->route('addons.talent_ai.ai_workspace.index'),
                    'icon' => 'fa-solid fa-robot',
                    'icon_key' => 'bolt',
                    'condition' => fn () => (bool) data_get(config('gigvora_talent_ai'), 'modules.ai_workspace.enabled', false),
                ]),
                $this->makeAction([
                    'id' => 'talent_ai.volunteering',
                    'label' => get_phrase('Volunteering opportunities'),
                    'description' => get_phrase('Publish or manage verified missions and hours.'),
                    'route' => $this->route('addons.talent_ai.volunteering.opportunities.index'),
                    'icon' => 'fa-solid fa-hand-holding-heart',
                    'icon_key' => 'favorite',
                    'condition' => fn () => (bool) data_get(config('gigvora_talent_ai'), 'modules.volunteering.enabled', false),
                ]),
            ]),
            default => [],
        };
    }

    protected function makeAction(array $action): ?array
    {
        if (isset($action['feature']) && ! $this->featureEnabled($action['feature'])) {
            return null;
        }

        if (isset($action['ability']) && ! Gate::allows($action['ability'])) {
            return null;
        }

        if (isset($action['condition']) && is_callable($action['condition']) && ! call_user_func($action['condition'])) {
            return null;
        }

        if (empty($action['route'])) {
            return null;
        }

        return [
            'id' => $action['id'],
            'label' => $action['label'],
            'description' => $action['description'] ?? '',
            'href' => $action['route'],
            'icon' => $action['icon'] ?? 'fa-solid fa-bolt',
            'icon_key' => $action['icon_key'] ?? 'bolt',
        ];
    }

    protected function featureEnabled(?string $feature): bool
    {
        if (! $feature) {
            return true;
        }

        return (bool) data_get($this->features, $feature, false);
    }

    protected function route(string $name, ?string $fallback = null): ?string
    {
        if (! Route::has($name)) {
            return $fallback;
        }

        return route($name);
    }
}


