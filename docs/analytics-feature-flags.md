# Analytics & Feature Flags Baseline

Last updated: 2025-11-29  
Scope: **AGENTS Task 1 – System Audit & Governance Setup (baseline analytics & feature flags)**.

This document establishes a production-ready baseline for analytics events and feature flags across the Gigvora stack.  
It is mapped explicitly to the canonical flow reference in `logic_flows.md` (notably sections **1.2 Live Feed**, **1.13 Stories & Live Moments**, **1.5 Profile & Journey**, and **3.6 Utilities Addon**, plus addon sections **3.1–3.5**).

## 1. Architecture Overview

- **Server-side analytics hub**
  - Central service: `ProNetwork\Services\AnalyticsService` (`track`, `trackMetric`), persisting to `AnalyticsEvent` and `AnalyticsMetric`.
  - Configuration: `config/pro_network_utilities_security_analytics.php` → `analytics.*` keys (driver, alias, forwarding, queue).
  - Optional forwarding to external/log channels controlled via:
    - `PRO_NETWORK_ANALYTICS_DRIVER`
    - `PRO_NETWORK_ANALYTICS_DRIVER_ALIAS`
    - `PRO_NETWORK_ANALYTICS_FORWARD`
    - `PRO_NETWORK_ANALYTICS_QUEUE`
- **Feature flagging**
  - Global flags defined under `features.*` in `pro_network_utilities_security_analytics.php` and read from `PRO_NETWORK_FEATURE_*` env vars.
  - HTTP middleware: `ProNetworkUtilitiesSecurityAnalytics\Http\Middleware\EnsureFeatureEnabled` aliased as `pro-network.feature`.
  - Applied on web/API routes in `routes/pro_network_web.php` and `routes/pro_network_api.php` to gate whole surfaces.
- **Flutter / mobile**
  - Talent & AI addon uses `AnalyticsApi` (from the Utilities package) and `TalentAiAnalyticsClient` to forward mobile-side events.
  - Integration wired in `Gigvora Flutter Mobile App/App/lib/addons_integration.dart` via `TalentAiApis.analyticsApi`.

## 2. Currently Implemented Analytics Events (Web)

**Source**: Utilities addon controllers using `AnalyticsService::track()`.

| Event name                     | Surface / action                                     | Code origin                                                                 | Logic flows anchor                    |
|--------------------------------|------------------------------------------------------|-----------------------------------------------------------------------------|---------------------------------------|
| `story.viewer.opened`          | Open story viewer rail                               | `StoryEnhancementController@viewer`                                         | `logic_flows.md#stories--live-moments-113` |
| `story.creator.opened`         | Open story creator/composer                          | `StoryEnhancementController@creator`                                        | `logic_flows.md#stories--live-moments-113` |
| `story.saved`                  | Save/publish story metadata                          | `StoryEnhancementController@store`                                          | `logic_flows.md#stories--live-moments-113` |
| `story.viewers.list`           | Fetch list of viewers for a story                    | `StoryEnhancementController@viewers`                                        | `logic_flows.md#stories--live-moments-113` |
| `post.poll.create.viewed`      | View “create poll” enhancement UI                    | `PostEnhancementController@createPoll`                                      | `logic_flows.md#live-feed-web-shell-12`    |
| `post.poll.created`            | Create poll on a post                                | `PostEnhancementController@storePoll`                                       | `logic_flows.md#live-feed-web-shell-12`    |
| `post.poll.voted`              | Vote on a post poll                                  | `PostEnhancementController@votePoll`                                        | `logic_flows.md#live-feed-web-shell-12`    |
| `post.thread.create.viewed`    | View “create thread” enhancement UI                  | `PostEnhancementController@createThread`                                    | `logic_flows.md#live-feed-web-shell-12`    |
| `post.thread.created`          | Create threaded post                                 | `PostEnhancementController@storeThread`                                     | `logic_flows.md#live-feed-web-shell-12`    |
| `post.reshared`                | Reshare an existing post                             | `PostEnhancementController@reshare`                                         | `logic_flows.md#live-feed-web-shell-12`    |
| `post.celebrate.create.viewed` | View “celebrate an occasion” enhancement UI          | `PostEnhancementController@createCelebrate`                                 | `logic_flows.md#live-feed-web-shell-12`    |
| `post.celebrate.created`       | Create “celebrate” enhancement post                  | `PostEnhancementController@storeCelebrate`                                  | `logic_flows.md#live-feed-web-shell-12`    |
| `analytics.metrics.viewed`     | View aggregated metrics in analytics dashboards      | `AnalyticsController@metrics`                                               | `logic_flows.md#utilities-addon-36`        |
| `analytics.series.viewed`      | View time-series of analytics events                 | `AnalyticsController@series`                                                | `logic_flows.md#utilities-addon-36`        |

**Notes**
- No usage of `trackMetric()` is present yet; metrics are aggregated via `AnalyticsMetric` queries only when explicitly requested.
- Current coverage focuses on **Stories**, **post enhancements**, and **analytics dashboard views**, not on Jobs/Freelance/Interactive/Ads flows themselves.

## 3. Analytics Integration (Flutter / Addons)

- **Talent & AI mobile addon**
  - `TalentAiApis.fromBaseUrl(...)` wires an `AnalyticsApi` pointing to the host (Laravel) analytics endpoints.
  - `GigvoraAddonProviders.talentAi()` and `GigvoraAddonNavigation.routes()` construct a shared `TalentAiAnalyticsClient`.
  - Talent & AI screens receive this client and are expected to emit events (examples from Flutter README):
    - `ads_campaign_created`
    - `headhunter_pipeline_stage_moved`
    - `ai_tool_ran`
    - `launchpad_applied`
    - `volunteering_applied`
  - These events hit the same analytics backend as web, aligning with addon flows in `logic_flows.md#ai--talent--ai-addon-35-57` and `logic_flows.md#advertisement-addon-34`.
- **Status**
  - From this repository we can confirm **wiring and API contracts**; the exact event names for each screen should be verified in the addon packages themselves during QA.

## 4. Feature Flags Baseline

**Source**: `config/pro_network_utilities_security_analytics.php` (`features.*`) and `EnsureFeatureEnabled` middleware.

| Feature key                       | Env var                                      | Description (high level)                                         | Gated routes (examples)                                                   | Logic flows mapping                             |
|-----------------------------------|----------------------------------------------|-------------------------------------------------------------------|----------------------------------------------------------------------------|-------------------------------------------------|
| `connections_graph`               | `PRO_NETWORK_FEATURE_CONNECTIONS_GRAPH`      | My Network graph & connections views                              | `/pro-network/my-network*`, `/api/pro-network/connections*`              | `logic_flows.md#groups--communities-17` (social graph) |
| `recommendations`                 | `PRO_NETWORK_FEATURE_RECOMMENDATIONS`        | People/companies/groups/content recommendations                   | `/api/pro-network/recommendations/*`                                      | `logic_flows.md#live-feed-web-shell-12`, `#search--discovery-14` |
| `live_streaming_enhanced`         | `PRO_NETWORK_FEATURE_LIVE_STREAMING`         | Enhanced live streaming hooks (stories/live moments)              | (used by controllers referenced in About.md)                              | `logic_flows.md#video-livestream-upload-management-114` |
| `notifications_wrapper`           | `PRO_NETWORK_FEATURE_NOTIFICATIONS`          | Upgraded notifications center wrapper                             | Utilities addon notifications routes                                      | `logic_flows.md#notifications--utilities-surfaces-18` |
| `marketplace_escrow`              | `PRO_NETWORK_FEATURE_MARKETPLACE_ESCROW`     | Escrow + disputes for marketplace orders                          | `/pro-network/marketplace/*`, `/api/pro-network/marketplace/*`           | `logic_flows.md#marketplace--commerce-112`      |
| `profile_professional_upgrades`   | `PRO_NETWORK_FEATURE_PROFILE_UPGRADES`       | Professional profile layouts & company pages                      | `/pro-network/profile/professional*`, `/pro-network/company/*`           | `logic_flows.md#profile--journey-15`            |
| `reactions_dislikes_scores`       | `PRO_NETWORK_FEATURE_REACTIONS`              | Reactions/dislikes & profile reaction scores                      | `/api/pro-network/reactions*`                                            | `logic_flows.md#live-feed-web-shell-12`         |
| `stories_wrapper`                 | `PRO_NETWORK_FEATURE_STORIES`                | Enhanced stories viewer/creator and APIs                          | `/pro-network/stories/*`, `/api/pro-network/stories*`                    | `logic_flows.md#stories--live-moments-113`      |
| `post_enhancements`               | `PRO_NETWORK_FEATURE_POSTS`                  | Polls, threads, reshares, celebrations                            | `/pro-network/posts/*`, `/api/pro-network/posts/*`                       | `logic_flows.md#live-feed-web-shell-12`         |
| `hashtags`                        | `PRO_NETWORK_FEATURE_HASHTAGS`               | Hashtag search & landing pages                                    | `/pro-network/hashtags/*`, `/api/pro-network/hashtags*`                  | `logic_flows.md#search--discovery-14`           |
| `music_library`                   | `PRO_NETWORK_FEATURE_MUSIC`                  | Music library for stories/live                                    | `/api/pro-network/music-library*`                                        | `logic_flows.md#stories--live-moments-113`      |
| `bad_word_checker`                | `PRO_NETWORK_FEATURE_BAD_WORDS`              | Content moderation filters                                        | Moderation tools, content pipeline (per About.md)                        | `logic_flows.md#admin-shell-110`, `#core-host-moderation` |
| `moderation_tools`                | `PRO_NETWORK_FEATURE_MODERATION`             | Moderation queues & actions                                       | `/pro-network/moderation`, `/api/pro-network/moderation/*`              | `logic_flows.md#admin-shell-110`                |
| `file_scan`                       | `PRO_NETWORK_FEATURE_FILE_SCAN`              | Virus scanning for uploads                                        | Storage/virus scan services                                              | `logic_flows.md#posting--media-uploads-13`      |
| `db_encryption`                   | `PRO_NETWORK_FEATURE_DB_ENCRYPTION`          | DB encryption for sensitive fields                                | Storage/encryption services                                              | `logic_flows.md#media-uploads--asset-management-2` |
| `storage_backends`                | `PRO_NETWORK_FEATURE_STORAGE`                | Multi-backend storage helpers                                     | Storage services                                                          | `logic_flows.md#media-uploads--asset-management-2` |
| `account_types`                   | `PRO_NETWORK_FEATURE_ACCOUNT_TYPES`          | Professional/creator account types                                | Profile/settings surfaces                                                 | `logic_flows.md#persona-specific-journeys-115`  |
| `search_upgrade`                  | `PRO_NETWORK_FEATURE_SEARCH`                 | Enhanced search capabilities                                      | Hooks into core `/search`                                                | `logic_flows.md#search--discovery-14`           |
| `chat_enhancements`               | `PRO_NETWORK_FEATURE_CHAT`                   | Chat enhancements (requests, settings, cleanup)                   | `/api/pro-network/chat/*`                                               | `logic_flows.md#messaging--inbox-111`           |
| `analytics_hub`                   | `PRO_NETWORK_FEATURE_ANALYTICS`              | Analytics dashboards + APIs                                       | `/pro-network/analytics`, `/api/pro-network/analytics/*`                 | `logic_flows.md#utilities-addon-36`, `#admin-shell-110` |
| `security_hardening`              | `PRO_NETWORK_FEATURE_SECURITY`               | Security logs & events                                            | `/pro-network/security/log`, `/api/pro-network/security/events`          | `logic_flows.md#admin-shell-110`, `#data-layer--environment-readiness-17` |
| `age_verification`                | `PRO_NETWORK_FEATURE_AGE_VERIFICATION`       | Age/ID verification workflows                                     | `/pro-network/age-verification/*`, `/api/pro-network/age-verification/*`| `logic_flows.md#events--rsvps-18`, `#utilities-addon-36` |
| `newsletters`                     | `PRO_NETWORK_FEATURE_NEWSLETTERS`            | Newsletter management & subscriptions                              | `/pro-network/newsletters/*`, `/api/pro-network/newsletters/*`           | `logic_flows.md#core-host-app-1` (engagement)   |
| `invite_contributors`             | `PRO_NETWORK_FEATURE_INVITES`                | Invite contributors to posts/articles                             | As defined in About.md                                                   | `logic_flows.md#live-feed-web-shell-12`         |
| `multi_language_wrapper`          | `PRO_NETWORK_FEATURE_MULTI_LANGUAGE`         | Multi-language support wrapper                                    | Translation utilities                                                     | Cross-cutting; all flows                         |
| `utilities_notifications_center`  | `PRO_NETWORK_FEATURE_UTILITIES_NOTIFICATIONS`| Utilities notifications surfaces                                   | Utilities addon notifications                                             | `logic_flows.md#notifications--utilities-surfaces-18` |
| `utilities_bookmarks`             | `PRO_NETWORK_FEATURE_UTILITIES_BOOKMARKS`    | Bookmarks/saved items                                             | Utilities bookmarks                                                       | `logic_flows.md#notifications--utilities-surfaces-18` |
| `utilities_calendar`              | `PRO_NETWORK_FEATURE_UTILITIES_CALENDAR`     | Utilities calendar                                                | Utilities calendar                                                        | `logic_flows.md#notifications--utilities-surfaces-18` |
| `utilities_reminders`             | `PRO_NETWORK_FEATURE_UTILITIES_REMINDERS`    | Utilities reminders                                               | Utilities reminders                                                       | `logic_flows.md#notifications--utilities-surfaces-18` |
| `utilities_quick_tools`           | `PRO_NETWORK_FEATURE_UTILITIES_QUICK_TOOLS`  | Utilities quick tools bubble and widgets                          | Utilities quick tools                                                     | `logic_flows.md#utilities-addon-36`, `#utilities-module-integration-7` |

## 5. Gaps & Recommendations – “Should Be Tracked”

The following are **high-priority analytics gaps** derived from `logic_flows.md`. They are not defects but **enhancement targets** for Tasks 4–13.

- **Core Live Feed (`logic_flows.md#live-feed-web-shell-12`)**
  - Track feed impressions and actions:
    - `feed.loaded`, `feed.scrolled`, `feed.filter.applied`, `feed.recommendation.clicked`.
    - `post.created`, `post.updated`, `post.deleted`, `post.commented`, `post.reacted`, `post.saved.toggled`.
  - Tag source contexts: feed vs profile vs group vs page vs search.
- **Stories & Live Moments (`logic_flows.md#stories--live-moments-113`, `#video-livestream-upload-management-114`)**
  - Events such as: `story.published`, `story.viewed`, `story.reacted`, `story.quicktool.used`, `story.highlighted`.
  - Livestream: `live.session.started`, `live.session.ended`, `live.viewer.joined`, `live.viewer.left`, `live.donation.made`.
- **Profile & Journey (`logic_flows.md#profile--journey-15`)**
  - Track profile visits and edits: `profile.viewed`, `profile.tab.changed`, `profile.hero.edited`, `profile.opportunity.clicked`.
  - Journey CTAs: `journey.step.started`, `journey.step.completed` with links to Jobs/Freelance/Interactive.
- **Jobs Addon (`logic_flows.md#jobs-addon-31`, `#jobs-mobile-53`)**
  - Candidate events: `job.viewed`, `job.saved`, `job.application.started`, `job.application.submitted`, `job.application.withdrawn`.
  - Recruiter events: `job.created`, `job.published`, `ats.stage.changed`, `interview.slot.offered`, `offer.sent`, `offer.accepted`.
- **Freelance Addon (`logic_flows.md#freelance-addon-32`, `#54`)**
  - Freelancer: `gig.viewed`, `gig.published`, `project.bid.submitted`, `milestone.completed`, `dispute.opened`, `dispute.resolved`.
  - Client: `project.created`, `contract.created`, `escrow.funded`, `escrow.released`, `escrow.refunded`.
- **Interactive / Live Addon (`logic_flows.md#interactive-addon-33`, `#55`)**
  - Host: `event.created`, `event.published`, `event.registration.opened/closed`, `replay.published`.
  - Attendee: `event.viewed`, `event.registered`, `event.joined`, `event.feedback.submitted`.
- **Advertisement Addon (`logic_flows.md#advertisement-addon-34`)**
  - `ads.campaign.created`, `ads.campaign.edited`, `ads.campaign.paused`, `ads.campaign.deleted`.
  - Placement performance: `ads.impression`, `ads.click`, `ads.conversion` tagged with surface (feed, profile, search, jobs, freelance, marketplace, live).
- **Talent & AI Addon (`logic_flows.md#ai--talent--ai-addon-35-57`)**
  - `headhunter.mandate.created`, `headhunter.pipeline.stage.moved`, `launchpad.programme.joined`, `launchpad.module.completed`, `ai.workspace.tool.used`.
- **Utilities Addon (`logic_flows.md#utilities-addon-36`, `#utilities--jobs-interview-sync-37`)**
  - `utilities.notification.created/opened/read`, `bookmark.toggled`, `calendar.event.created/updated/deleted`, `reminder.created/sent/snoozed`.
  - Interview-specific: `interview.reminder.created`, `interview.reminder.fired`, `interview.timeline.viewed`.

## 6. Operational Notes & Next Steps

- **Env & config readiness**
  - Ensure all `PRO_NETWORK_FEATURE_*` flags have sane defaults in `.env.example` and are documented for staging/production.
  - Confirm analytics driver configuration (`log` vs remote) per environment; enable forwarding only where external sinks exist.
- **Governance**
  - Changes to event names or flag behaviors must update:
    - `logic_flows.md` (relevant section),
    - this file,
    - and the QA matrix in `docs/qa-bugs.md` / `docs/progress.md` snapshots.
- **Implementation backlog**
  - Actual implementation of “should” events should be scheduled under future tasks (4–13) and tracked via the project’s issue tracker.


