# Professional Network Utilities, Security & Analytics Addon

This repository delivers a Sociopro-compatible addon that layers professional networking utilities, marketplace escrow, security hardening, analytics, and a companion Flutter client on top of an existing Sociopro core install. It keeps core models and bindings intact while exposing feature-gated routes, domain services, and typed mobile integrations.

## Feature Highlights
- **Connections & My Network**: LinkedIn-style degrees, mutuals, recommendations.
- **Professional Profiles & Company Pages**: Enriched profile fields, employment visibility, upgraded agency/company pages.
- **Marketplace Escrow & Disputes**: Order escrow lifecycle, milestones, refunds, and dispute handling.
- **Stories & Post Enhancements**: Enhanced stories with music, polls, threads, reshares, and celebrate posts.
- **Reactions & Hashtags**: Multi-reactions/dislikes with profile scores and normalized tagging.
- **Analytics Hub**: Metrics and series endpoints plus admin dashboards.
- **Security & Moderation**: Brute-force logging, moderation queues, malware/virus scan hooks, GDPR-aware logging.
- **Storage & Media**: Cloudflare R2, Wasabi, or local storage with optional encryption and signed URLs.
- **Account Types & Age Verification**: Professional/creator flags and age/KYC placeholders.
- **Newsletters & Invites**: Subscription flows and invite-to-contribute utilities.
- **Flutter Addon**: Typed models, API clients, state, screens, and `menu.dart` hooks that mirror the Laravel API.

## Installation
1. **Add via Composer** (path or VCS):
   ```bash
   composer config repositories.pro-network path ./utilities,security\ and\ analytics\ package
   composer require pro-network/utilities-security-analytics:*
   ```
   Or add the Git repository URL as a VCS repository before requiring.
2. **Register Service Provider** if not auto-discovered:
   ```php
   // config/app.php
   'providers' => [
       // ...
       ProNetwork\ProNetworkUtilitiesSecurityAnalyticsServiceProvider::class,
   ];
   ```
3. **Publish Config (and assets if desired):**
   ```bash
   php artisan vendor:publish --provider="ProNetwork\\ProNetworkUtilitiesSecurityAnalyticsServiceProvider" --tag=config
   php artisan vendor:publish --provider="ProNetwork\\ProNetworkUtilitiesSecurityAnalyticsServiceProvider" --tag=assets
   ```
4. **Run Migrations:**
   ```bash
   php artisan migrate
   ```
5. **Configure Feature Flags:** Edit `config/pro_network_utilities_security_analytics.php` (see below) to enable the features you want; all ship disabled by default.
6. **Optional Flutter Addon:** Add the `flutter_addon` directory as a local package in `pubspec.yaml` (e.g., `pro_network_addon:
   path: ../flutter_addon`) and run `flutter pub get`.

## Configuration
All feature toggles and integration settings live in `config/pro_network_utilities_security_analytics.php`:
- **features**: Opt-in flags (connections graph, recommendations, live streaming wrapper, notifications, escrow, profile upgrades, reactions/dislikes/scores, stories, post enhancements, hashtags, music library, bad word checker, moderation tools, file scanning, DB/storage encryption, account types, search upgrade, chat enhancements, analytics hub, security hardening, age verification, newsletters, invite contributors, multi-language wrapper).
- **analytics**: Driver alias, optional forwarding, and queue settings.
- **storage**: Disks for R2/Wasabi/local, signed URL TTL, optional encryption key.
- **virus_scanner**: Endpoint/token/timeouts and enable flag for malware scanning hooks.
- **age_verification / kyc**: Provider placeholders, API keys, callback URL, and requirements for live/marketplace payouts.
- **security**: Brute-force thresholds, rate limits, IP reputation flags, GDPR logging/retention settings.
- **models**: Bindings to Sociopro core models (user, post, page, group, story, marketplace order).

## Basic Usage
- **Web entry points (auth protected):**
  - `/pro-network/my-network` for network summary and connections.
  - `/pro-network/profile/professional` (and `/pro-network/profile/professional/{user}`) for enhanced profiles; `/pro-network/company/{company}` for company/agency pages.
  - `/pro-network/marketplace/orders/{order}/escrow` for escrow state and dispute creation views.
  - `/pro-network/stories/viewer` and `/pro-network/stories/creator` for story UX; post enhancement creators under `/pro-network/posts/*`.
  - `/pro-network/analytics` (with `can:viewAnalytics`), `/pro-network/security/log` (with `can:viewSecurity`), `/pro-network/moderation` (with `can:moderate`).
  - `/pro-network/newsletters/manage` and age verification callback under `/pro-network/age-verification/callback`.
- **API prefix:** `/api/pro-network/...` mirrors the above for mobile/SPA consumption (connections, recommendations, profile/company CRUD, escrow/disputes, stories, posts, reactions, hashtags, music, chat enhancements, analytics, security/moderation, newsletters, age verification). Sanctum auth and feature middleware apply.
- **Flutter integration:** Import `flutter_addon/lib/menu.dart` to merge `proNetworkMenuItems` into your navigation. Initialize API clients with your base URL and auth token provider, reuse the packaged state classes under `flutter_addon/lib/state`, and wire `AnalyticsClient` to send screen/action events to `/api/pro-network/analytics/*`.

## Notes
- All features are disabled by default for safety; enable only what your instance needs.
- The package loads its own migrations, routes, translations, and views without overriding Sociopro core bindings.
- Follow `AGENTS.md` for boundaries with Sociopro core and for any additional platform-specific expectations.
