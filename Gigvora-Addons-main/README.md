# Gigvora Addons Monorepo

This repository bundles five production-ready addons that extend a Sociopro/Laravel host without stepping on each other’s routes, models, or configs. Each addon ships both a Laravel package and a Flutter/mobile client so the same capabilities are available on web and phone. Use this README to see what is available and how to mount everything together on one site.

## Addon lineup

### Advertisement
- **Laravel package:** `Advertisement_Laravel_package` (routes/web + routes/api, config namespace `advertisement`, Blade views under `resources/views/vendor/advertisement`).
- **Flutter addon:** `Advertisement_Flutter_addon`.
- **Scope:** Campaigns, creatives, targeting, placements, reporting, forecasts, and affiliate payouts for ads.

### Freelance Marketplace
- **Laravel package:** `freelance_laravel_package` (publishable migrations, routes, and views under `publishable/resources/views/vendor/freelance`).
- **Flutter addon:** `freelance_phone_addon`.
- **Scope:** Gigs, projects, proposals, contracts, milestones, escrow, disputes, reviews, and freelance/client onboarding.

### Interactive Live & Events
- **Laravel package:** `Webinar_networking_interview_and_Podcast_Laravel_package` (views under `resources/views/vendor/live`, routes/web + routes/api, config namespace `webinar_networking_interview_podcast`).
- **Flutter addon:** `Webinar_networking_interview_and_Podcast_Flutter_addon`.
- **Scope:** Webinars, networking sessions, live/recorded podcasts, interview scheduling, waiting rooms, and live-session shells.

### Jobs & ATS
- **Laravel package:** `Jobs_Laravel_package` (routes/web + routes/api, views under `resources/views/vendor/jobs`).
- **Flutter addon:** `Jobs_Flutter_addon`.
- **Scope:** Job search/posting, applications, ATS stages, screening questions, CV/cover letters, interview scheduling, and employer billing/plans.

### Utilities, Security & Analytics
- **Laravel package:** utilities/security/analytics package with config toggles (see `utilities,security and analytics package`).
- **Flutter addon:** `flutter_addon` inside `Utilities-Addon`.
- **Scope:** Connections graph, recommendations, storage/security hardening, upgraded chat/search/stories, analytics dashboards, content moderation, and related professional-network utilities.

## Using multiple addons together
- **Keep namespace boundaries:** Mount each addon’s service provider and routes using its provided prefixes (`advertisement`, `jobs`, `freelance`, `live`, `utilities`) to prevent collisions when installed in the same Laravel host.
- **Publish views/config selectively:** Only publish the views/config for the addons you enable so you avoid overwriting host assets. Each package ships its own config file and publish tags noted above.
- **Feature toggles:** All addons expose config flags to enable/disable modules. Use those to roll out features gradually without editing core app code.
- **API-first integrations:** When one addon needs another’s data (e.g., recommending jobs inside freelance dashboards), call the owning addon’s API endpoints rather than duplicating domain logic.
- **Shared authentication:** Rely on the host’s authentication/session and avoid new user tables; every addon is designed to piggyback on existing users and permissions.

With these patterns, you can load every addon into the same Laravel deployment and keep the logic isolated while providing a seamless, full-featured Gigvora experience on web and mobile.
