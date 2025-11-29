# Gigvora Addons Overview

This repository contains five independent addons that plug into the core Sociopro-style platform. Each addon keeps its own namespace, routes, and configuration to avoid collisions. Use this document to understand responsibilities and integration points so you can wire them up without duplicating or conflicting behaviour.

## Advertisement Addon
- **Purpose:** Ads manager covering advertisers, campaigns, creatives, placements, targeting, reporting, forecasting, and affiliate payouts for web and Flutter clients.
- **Backend package:** `Advertisement_Laravel_package`
  - Routes under `/api/advertisement/*` and `/advertisement/dashboard` (see `routes/api.php` and `routes/web.php`).
  - Config namespace `advertisement` from `config/advertisement.php`.
  - Views under `resources/views/vendor/advertisement/*` published via the `advertisement-views` tag.
- **Mobile addon:** `Advertisement_Flutter_addon` (screens and HTTP client for campaign/creative flows).
- **Boundary:** Owns ads, targeting, keyword pricing, and affiliate payouts. Do not recreate campaign or creative concepts in other addons—integrate via the API instead.

## Freelance Addon
- **Purpose:** Freelance marketplace with gigs, projects, proposals, contracts, milestones, escrow, disputes, reviews, and onboarding for freelancers/clients.
- **Backend package:** `freelance_laravel_package` with publishable routes, migrations, and views under `publishable/resources/views/vendor/freelance`.
- **Mobile addon:** `freelance_phone_addon` with Riverpod-backed screens for gigs, projects, disputes, escrow, and dashboards.
- **Boundary:** Canonical owner of gigs/projects/escrow/disputes. Jobs or live-event addons should reference these APIs instead of duplicating freelance flows.

## Interactive Addon
- **Purpose:** Live events stack covering webinars, networking (including speed networking), podcasts, and interviews for hosts, attendees, and admins.
- **Backend package:** `Webinar_networking_interview_and_Podcast_Laravel_package`
  - Routes loaded from `routes/web.php` and `routes/api.php` with views under `resources/views/vendor/live`.
  - Config namespace `webinar_networking_interview_podcast` and publish tags `wnip-*`.
- **Mobile addon:** `webinar_networking_interview_podcast_flutter_addon` providing event discovery, registration, waiting rooms, and live shells.
- **Boundary:** Owns all live/recorded event concepts; other addons should subscribe to its events rather than redefining webinars or interviews.

## Jobs Addon
- **Purpose:** Jobs & ATS experience including job search/posting, applications, ATS pipelines, screening questions, CV/cover letters, subscriptions, and interview scheduling.
- **Backend package:** `Jobs_Laravel_package` with API routes in `routes/api.php` and web routes in `routes/web.php` (views under `resources/views/vendor/jobs`).
- **Mobile addon:** `Jobs_phone_addon` for seeker/employer flows.
- **Boundary:** Canonical owner for permanent job postings and ATS stages. Freelance addon handles gigs/projects; keep those separate from job posts.

## Utilities Addon
- **Purpose:** Professional social network utilities, security, and analytics wrapper extending Sociopro (connections graph, recommendations, storage/security hardening, upgraded chat/search/stories, analytics, etc.).
- **Backend package:** under `Utilities-Addon` with config `pro_network_utilities_security_analytics.php` to toggle features.
- **Mobile addon:** `utilities_phone_addon` exposing menus, API clients, and state for the same utilities.
- **Boundary:** Provides cross-cutting enhancements (security, analytics, networking, media). It should wrap—not replace—core Sociopro services and should not duplicate ads, jobs, freelance, or live-event logic.

## Integration Notes
- Keep each addon’s routes namespaced (URI and route-name prefixes) when mounting into the host app to prevent collisions.
- Enable/disable features through each addon’s config file rather than editing core app config.
- When features interact (e.g., recommending jobs inside freelance dashboards), consume the owning addon’s APIs instead of reimplementing domain logic.
