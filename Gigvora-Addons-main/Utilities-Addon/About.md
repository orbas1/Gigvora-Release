# Agent Instructions – Professional Social Network Utilities, Security & Analytics Package (Laravel + Flutter)

## Overall Goal

Your goal is to create:

1. A **Laravel package**
2. A **Flutter mobile addon package**

that together provide the **same enhanced utilities, security and analytics functionality** on both:

* The **Laravel backend / web app**, and
* The **Flutter mobile app**.

These will plug into an existing **Sociopro-based social media platform**, moving it towards a **LinkedIn-style professional network**. This package is an **addon wrapper** that **extends** Sociopro without overriding or breaking core behaviour.

> ⚠️ Important: **Do not copy any binary files** (e.g. images, fonts, compiled assets, `.exe`, `.dll`, `.so`, APKs, etc.).

> ⚠️ Important: Assume Sociopro already provides core social features (users, posts, comments, stories, chat, marketplace, notifications, etc.). You must build **on top of** these, using **new namespaced code, config flags and adapters**, not by renaming or deleting existing core logic.

---

## Integration Principles

* Treat Sociopro as **the source of truth** for:

  * User accounts, authentication, core posts, comments, base stories, base chat, base marketplace.
* This utilities/security/analytics package must:

  * **Wrap and extend** existing features.
  * Use **new routes, views, controllers, services and tables** under clear namespaces.
  * Be **config-driven and opt-in** via a config file.
  * Avoid name collisions with Sociopro models, controllers and tables.

Everything should be **modular**, so features can be turned on/off without breaking the main site or app.

### Gigvora Integration Snapshot

- **Navigation**: keep utilities as supporting surfaces – notification bell + page, saved/bookmarked items reachable from user menus and module-local CTAs (e.g., job cards), calendar/reminders under "My Schedule", admin quick tools under settings/dashboards.
- **Routing**: web routes grouped under `/utilities` with `web`, `auth`, `verified`, `locale`; API routes grouped under `/api/utilities` with `api`, `auth:sanctum`, `locale` so mobile and SPA clients share the same surface.
- **Feature flags**: toggle notifications, bookmarks, calendar, reminders, and quick tools via `config/pro_network_utilities_security_analytics.php` (`features.utilities_*`). Limits/retention in `utilities.*` must be honoured by controllers, services, and queue jobs.
- **Data ownership**: every utility resource is user-scoped unless explicitly admin-only (metrics/export/token). Policies should enforce `user_id` matching and require `platform_admin`/`manageUtilities` for admin endpoints.

---

## Part 1 – Laravel Package

### 1. Config

Create a config file, e.g. `config/pro_network_utilities_security_analytics.php`, with:

* Global feature toggles, for example:

  * `features.connections_graph` (1st/2nd/3rd degree & My Network)
  * `features.recommendations`
  * `features.live_streaming_enhanced`
  * `features.notifications_wrapper`
  * `features.marketplace_escrow`
  * `features.profile_professional_upgrades`
  * `features.reactions_dislikes_scores`
  * `features.stories_wrapper`
  * `features.post_enhancements`
  * `features.hashtags`
  * `features.music_library`
  * `features.bad_word_checker`
  * `features.moderation_tools`
  * `features.file_scan`
  * `features.db_encryption`
  * `features.storage_backends`
  * `features.account_types`
  * `features.search_upgrade`
  * `features.chat_enhancements`
  * `features.analytics_hub`
  * `features.security_hardening`
  * `features.age_verification`
  * `features.newsletters`
  * `features.invite_contributors`
  * `features.multi_language_wrapper`

* Integration settings:

  * Reference to existing analytics driver or logger (if any) – you wrap it, you do not overwrite it.
  * External virus scanner endpoint/keys (e.g. ClamAV or third-party API).
  * Age/KYC verification provider placeholders.
  * Cloudflare R2, Wasabi and local storage configuration names (you only orchestrate their use).
  * Security settings (rate limits, brute force thresholds, IP reputation settings, GDPR logging flags).

All features should be **disabled by default** and enabled explicitly.

---

### 2. Database

Add new migrations that **extend** capabilities without breaking Sociopro:

* **Connections & Network**

  * Tables to cache and store 1st/2nd/3rd degree connections, mutual connections and network metrics.

* **Professional Profile Data**

  * Tables or extension tables for:

    * Professional header/tagline (e.g. "Commercial Professional | Finance | Sales & Ops | Client Service | Excel & CRM | London").
    * Location.
    * Top 5 skills as tags.
    * All skills tags.
    * Certifications & licenses history.
    * Work experience history.
    * Qualifications/education history.
    * References from past employers.
    * DBS/background check status.
    * Freelancer gigs offered.
    * Client projects open for freelancers.
    * Company jobs available.
    * "Available for work" flags.
    * Public profile URL slug and share link.

* **Company/Agency Profiles**

  * Extension tables for company/agency pages, including:

    * Professional page metadata.
    * Count of users who work there.

* **Marketplace Escrow & Disputes**

  * Tables for:

    * Escrow transactions linked to existing marketplace orders.
    * Escrow milestones, release events, refunds.
    * Disputes and dispute messages.
    * Delivery method (delivery vs in-person collection).

* **Stories & Live Streaming**

  * Tables to store enhanced story metadata:

    * Story overlays, stickers, filters, attached music.
    * Linkage between live streams and stories (live replays).
  * Live streams metadata:

    * Guests/participants.
    * Likes, donations, live chat event references.

* **Reactions & Scores**

  * Tables for:

    * Extended reactions (like, love, celebrate, insightful, etc.) and dislikes.
    * Total like/reaction score per profile.

* **Hashtags & Tagging**

  * Tables for:

    * Normalised hashtags.
    * SEO/meta tags for skills, education, certificates, freelance categories, jobs, gigs, webinars, podcasts.

* **Music Library**

  * Tables for:

    * Stock/royalty-free music tracks metadata (no binary audio, just keys/paths).

* **Security & Moderation**

  * Tables for:

    * Security events (brute force attempts, suspicious activity, blocked actions).
    * Bad words list and rule sets.
    * Moderation queue (posts, comments, stories flagged for review).
    * File scan results (file ID/hash, scan status, scanner info).

* **Analytics**

  * Tables for aggregated metrics:

    * Gigs, jobs, projects.
    * Companies/agencies, groups, pages.
    * Podcasts, webinars, interviews.
    * Stories views, post reactions, chat.

* **Account Types & Settings**

  * Tables/columns to track:

    * Professional accounts.
    * Creator accounts.
    * Feature flags per user.

* **Age Verification**

  * Tables for:

    * Age/KYC verification status.
    * Provider reference IDs.
    * Timestamps and audit logs.

* **Newsletters & Invites**

  * Newsletter subscriptions.
  * Records of "invite to contribute" to posts/articles as experts.

> Do not drop or rename any Sociopro core tables. Only add new tables or safe nullable extension columns.

---

### 3. Domains

Organise Laravel domain logic in namespaced folders, e.g. `Domain/Connections`, `Domain/Profile`, `Domain/MarketplaceEscrow`, `Domain/Analytics`, `Domain/Security`, etc.

Implement domain services for:

1. **Connections & My Network**

   * Compute and fetch:

     * 1st degree connections.
     * 2nd and 3rd degree connections.
     * Mutual connections.
   * "My Network" summary like LinkedIn.

2. **Search SEO & Tags**

   * Normalise + store meta tags and skill/education/certification/freelance/job/gig/webinar/podcast tags.
   * Provide helper methods to attach tags to existing Sociopro entities.

3. **Live Streaming Wrapper**

   * Wrap Sociopro’s or Sngine’s existing live streaming logic (if available) to add:

     * Likes in live.
     * Add guests to the live.
     * Donations during live stream.
     * Live chat.
     * Record live and save as story.
   * Ensure live shows in the stories area.

4. **Notifications Utility Layer**

   * Wrap existing notifications to expose:

     * Comment replies.
     * Comments.
     * Likes.
     * Reactions.
   * Provide unified helpers for sending push/mobile notifications that call the existing notification system under the hood.

5. **Marketplace Escrow & Inventory**

   * Use Taskup-style escrow patterns as a reference to add:

     * Escrow on marketplace orders.
     * Inventory management helpers.
     * Refund & dispute flows for problem sales.
     * Delivery vs collection-in-person support.
   * Must integrate with existing marketplace orders **without replacing core order models**.

6. **Recommendations Engine**

   * Services to recommend:

     * New connects.
     * New followers.
     * Freelancers.
     * Freelance projects.
     * Gigs.
     * Jobs.
     * Pages.
     * Groups.
     * Company/agency pages.
     * Live now.
     * Webinars.
     * Podcasts.

7. **Page & Profile Upgrades**

   * Domain services for:

     * Upgrading pages to professional agency/company pages.
     * Managing professional profile fields (header, skills, experience, qualifications, references, DBS/background, available for work, etc.).
     * Linking current company and computing how many users work there.

8. **Reactions & Hashtags**

   * Domain logic for:

     * Post reactions (multi-reaction support).
     * Dislikes.
     * Total like/reaction score aggregation.
     * Hashtag parsing, linking, search integration.

9. **Stories & Post Enhancements**

   * Services for:

     * Improved story creation (layers, text, music, filters).
     * Improved story viewing flows (smooth viewer, progress, navigation).
     * Posts with polls, threads, reshare/repost, celebrate an occasion.

10. **Music Library**

    * Utilities to:

      * List and attach music to stories.
      * Manage music metadata.

11. **Account Types**

    * Logic for:

      * Professional vs Creator accounts.
      * Enabling upgraded features.

12. **Search Upgrade**

    * Helper services to:

      * Build improved, fast search queries (possibly integrating with existing search engine or Scout).
      * Use tags, skills, and professional data.

13. **Chat Enhancements**

    * Services that wrap Sociopro chat:

      * Delete chats, clear chats.
      * Emojis, GIFs, voice notes, attachments.
      * Voice calls and video call hooks.
      * Floating chat bubble logic.
      * Message request inbox.
      * Message settings (background, privacy).
      * Away/online presence.
      * CRUD on conversations.

14. **Analytics Hub**

    * A central `AnalyticsService` to track:

      * Gigs, jobs, projects.
      * Companies, agencies, groups, pages.
      * Podcasts, pages, webinars, interviews.
      * Stories, reactions, chat.
    * Provide unified `track(event, properties, user)` method that:

      * Logs to analytics tables.
      * Optionally forwards to existing analytics/logging system.

15. **Security & Moderation**

    * `SecurityEventService` for:

      * Brute-force detection.
      * Hacker detection.
      * Database protection events.
      * GDPR-compliant logging.
    * Bad word checking and content moderation queue utilities.

16. **Storage & Encryption**

    * Services for:

      * Database encryption for sensitive fields.
      * Storage encryption and secure URL generation.
      * Selecting Cloudflare R2, Wasabi or local storage per config.

17. **Age Verification**

    * Domain logic/hooks to:

      * Trigger age/ID verification.
      * Store verification status.
      * Enforce verification for sensitive actions if enabled.

18. **Newsletters & Invites**

    * Manage newsletter subscriptions.
    * Manage "invite to contribute" to posts/articles.

19. **Multi-language Wrapper**

    * Utilities to:

      * Expose translation keys and language options.
      * Support: Eng, Fra, Ger, Spa, Port, Arabic, Russian, Croatian, Italian, Yoruba, Afrikaans, Mandarin, Japanese, Hindi, Urdu, Tamil, Sinhala, etc.

---

### 4. Http (Controllers & Requests)

Create controllers (web + API) in a dedicated namespace, for example `ProNetworkUtilitiesSecurityAnalytics\Http\Controllers`, for:

* Connections & My Network.
* Recommendations.
* Marketplace escrow and disputes.
* Enhanced stories and live streaming.
* Post polls, threads, reshare/repost, celebrations.
* Profile and company page enhancements.
* Reactions & dislikes.
* Hashtags.
* Music library listing.
* Chat enhancements (wrapping Sociopro chat endpoints).
* Analytics API endpoints for the Flutter addon.
* Security & moderation actions (for admins/moderators).
* Newsletters.
* Age verification callbacks and status.

Use Form Requests for validation.

---

### 5. Policies

Define policies for:

* Managing professional/creator upgrades.
* Editing upgraded company/agency pages.
* Opening & managing disputes.
* Accessing analytics dashboards.
* Using moderation tools.

Policies should integrate with Sociopro’s user roles/permissions and be opt-in.

---

### 6. Resources (Blade Views)

Create Blade views (namespaced under the package views path) for:

* **My Network** dashboard (LinkedIn-style).
* **Connections** lists and mutual connections.
* **Professional Profile** sections (header, skills, experience, education, references, DBS, available for work, activity, interests).
* **Company/Agency pages** with professional layout.
* **Marketplace Escrow & Disputes** UI (orders, escrow status, dispute flows).
* **Live & Stories** upgraded viewer and creator.
* **Post Enhancements** (polls, threads, reshare/repost, celebrate an occasion).
* **Analytics Dashboards** (graphs and tables) for all tracked entities.
* **Moderation Panel** (bad word hits, reported content, moderation actions).
* **Newsletters Management**.
* **Security & Settings**:

  * Account type (Professional/Creator).
  * Security event summaries.
  * Age verification status.

Use existing Sociopro layout structure and styling conventions.

---

### 7. Admin Panel Entries

Add admin panel/menu entries (via configuration and view composers) for:

* Professional Network Utilities.
* Analytics & Reports.
* Security & Moderation.
* Marketplace Escrow & Disputes.
* Storage & File Scanning.
* Newsletters.

These should link to the Blade views above.

---

### 8. Assets (CSS/JS)

Only non-binary assets:

* CSS/SCSS for:

  * My Network page.
  * Professional profile sections.
  * Upgraded stories viewer/creator.
  * Chat floating bubble/panel.
  * Analytics dashboards.

* JavaScript for interactive features:

  * Story creation & viewing.
  * Polls & threads.
  * Reshare/repost modals.
  * Live stream UI interactions (likes, donations, chat).
  * Chat floating bubble & conversation switching.
  * Analytics charts & filtering.
  * Moderation panel AJAX actions.

Reuse existing JS tooling and frameworks already in Sociopro (e.g. Axios, jQuery, or native fetch) instead of introducing new ones.

---

### 9. Language Translations

All new strings should live in language files:

* `resources/lang/{locale}/pro_network_utilities_security_analytics.php`

Provide full English strings and stub keys for the other target languages.

---

### 10. Routes

Create routes:

* Web routes (for Blade views) under a configurable prefix, e.g. `/pro-network/...`.
* API routes (for Flutter and AJAX) under a configurable prefix, e.g. `/api/pro-network/...`.

Attach:

* Appropriate middleware (auth, roles, rate limiting).
* Feature-toggle guards (so routes exist only when enabled).

---

### 11. Services & Support

Create services such as:

* `AnalyticsService`
* `SecurityEventService`
* `RecommendationService`
* `EscrowService`
* `ProfileEnhancementService`
* `StoryEnhancementService`
* `PostEnhancementService`
* `ChatEnhancementService`
* `StorageService`
* `NewsletterService`
* `AgeVerificationService`
* `ModerationService`

And support classes:

* Enums (account types, reaction types, security event types, connection degree).
* DTOs for events and API responses.
* Helpers for tags, hashtags, SEO meta.

---

### 12. Service Provider

Create `ProNetworkUtilitiesSecurityAnalyticsServiceProvider` to:

* Register config, migrations, routes, views and translations.
* Bind interfaces to the above services.
* Register event listeners for analytics & security.
* Register admin menu composers.
* Respect feature toggles from config.

The provider must **not override** existing Sociopro bindings.

---

### 13. Documentation

Provide:

* `README.md` – installation, configuration, basic feature overview.
* `functions.md` – exhaustive list of:

  * Features and functions.
  * Laravel routes and controllers.
  * Integration points with Sociopro feed, search, analytics and security.
  * How to integrate storage, newsletters, and age verification.

---

## Part 2 – Flutter Addon Package

The Flutter addon is a **thin client layer** that:

* Uses the Laravel API from this package.
* Wraps existing mobile features (stories, chat, profiles, marketplace) with enhanced professional, security and analytics capabilities.

### 1. `pubspec.yaml`

Define a Flutter/Dart package with:

* Name: `pro_network_utilities_security_analytics` (or similar).
* Dependencies:

  * HTTP client (`http` or `dio` as per host app standard).
  * State management (`provider`, `riverpod`, or `bloc` – match host app).
  * JSON serialization.
  * Optional: charts package, WebSocket/RTC (if used by live/chat in host app).

---

### 2. Models

Create Dart models that mirror the Laravel API responses for:

* Connections & networks.
* Professional profile data.
* Company/agency pages.
* Marketplace escrow & disputes.
* Stories & live streams.
* Post reactions, polls, threads, reposts.
* Recommendations (users, freelancers, jobs, gigs, projects, pages, groups, webinars, podcasts, live now).
* Analytics metrics & charts.
* Security/age verification status (where needed client-side).
* Newsletter subscription status.
* Chat conversations and messages (wrapping existing Sociopro chat data shapes).

---

### 3. Screens / Pages

Implement Flutter screens for:

* **My Network** (LinkedIn-style):

  * Summary view and lists of connections.
  * Recommendations section.

* **Enhanced Profile** components:

  * Professional header, location, skills, experience, education, references, DBS, available for work, activity, interests.

* **Company/Agency Page** view:

  * Company overview, jobs, gigs, projects, employees count.

* **Marketplace Escrow & Disputes** pages:

  * Escrow status for orders.
  * Dispute detail and resolution flows.

* **Stories Viewer & Creator**:

  * Upgraded, smooth viewer.
  * Story creator with overlays, text, filters, music picker.

* **Post Enhancements**:

  * Poll creation.
  * Threaded posts UI.
  * Reshare/repost sheet.
  * "Celebrate an occasion" composer.

* **Chat Enhancements**:

  * Chat list and message thread screens integrating:

    * Emojis, GIFs.
    * Voice notes.
    * Attachments.
    * Voice/video call buttons (hooking into host call system).
  * Message request inbox.

* **Analytics Screens**:

  * User-facing analytics (profile views, engagement).
  * Admin/advanced analytics screens (if permitted).

* **Moderation Tools** (for moderator/admin roles only).

* **Newsletters** subscribe/manage screen.

* **Age Verification Status** screen.

All screens must be navigable from **menu entries** provided by this addon (and integrable into the host app menu).

---

### 4. Services (API Clients)

Create API service classes that:

* Call the Laravel package endpoints for:

  * Connections, recommendations.
  * Profile upgrades.
  * Company pages.
  * Escrow & disputes.
  * Stories & posts enhancements.
  * Chat enhancements.
  * Analytics data.
  * Moderation actions.
  * Newsletters & age verification.
* Handle:

  * Auth tokens & headers (delegating to host app’s auth layer).
  * Errors and timeouts with meaningful messages.

---

### 5. State Management

Use a consistent state management pattern to:

* Manage data loads, loading states, error states.
* Keep UX responsive and clear.
* Refresh lists after actions (e.g. posting, updating, resolving disputes).

---

### 6. `menu.dart`

Expose a simple entry point (`menu.dart`) that:

* Defines a list of menu items/routes for:

  * My Network.
  * Upgraded profile.
  * Company/agency pages.
  * Marketplace escrow.
  * Stories creator.
  * Analytics.
  * Newsletters.
  * Settings (account type, security, age verification).
* Allows the host app to:

  * Import and merge these entries into its main navigation.

---

### 7. Analytics & Security Hooks (Client-Side)

* Provide a small client-side `AnalyticsClient` to:

  * Track screen views and key actions.
  * Send them to Laravel analytics endpoints.
* Provide optional security signals (device info, app version, etc.) in headers or payloads (configurable).

These must be **lightweight** and cannot replace any existing analytics SDK in the host app; they just complement it.

---

## Required Functional Areas (Both Laravel & Flutter)

Both the **Laravel package** and the **Flutter addon** must support the following core functions (by wrapping and extending Sociopro):

1. **Connections like LinkedIn**

   * 1st, 2nd, 3rd degree connections.
   * "My Network" overview.

2. **Search & SEO Tags**

   * Skill, education, certificate, freelance category, jobs, gigs, webinar, podcast donation-related tags.
   * Better meta tagging for search.

3. **Live Streaming**

   * General live streams (not webinars) with:

     * Likes, guests, donations, recording, live chat.
   * Live replays visible in stories.

4. **Phone Notifications Wrapper**

   * Comment replies, comments, likes, reactions etc. routed via a unified notification interface.

5. **Marketplace Escrow & Inventory**

   * Escrow for marketplace payments.
   * Inventory management.
   * Refunds, disputes, delivery vs collection.

6. **Recommendations**

   * New connects, followers, freelancers, projects, gigs, jobs, pages, groups, company pages, live now, webinars, podcasts.

7. **Professional Pages**

   * Upgraded agency/company pages.

8. **Post Reactions**

   * Multi-reactions plus dislikes.
   * Total like/reaction score on profiles.

9. **Full Analytics**

   * Gigs, jobs, projects, companies, agencies, groups, podcasts, pages, webinars, interviews.

10. **Music Library for Stories**

* Stock/royalty-free track metadata for story uploads.

11. **Bad Word Checker**

* Post/comment content filtering.

12. **Moderation of Posts & Timeline**

* Queue and tools for moderators.

13. **File Upload Checker**

* Malware/virus scanning for uploads.

14. **Database Encryption**

* Encryption of sensitive columns.

15. **Storage Encryption & Security**

* Secure storage backends and URLs.

16. **Professional & Creator Account Types**

* Feature flags and upgraded capabilities.

17. **Upgraded Search**

* Faster, richer search using tags and professional fields.

18. **Upgraded Chat**

* Delete/clear chats, emojis, GIFs, voice notes, attachments, voice/video calls.
* Floating chat bubble, message requests, settings, transparent backgrounds, presence, CRUD.

19. **Analytics Graphs & Tables**

* Visual analytics surfaces.

20. **Security Hardening**

* Brute force protection, hacker protection, DB protection.
* UK GDPR-compliant logging and data handling.

21. **Dislikes**

* Count and UI.

22. **Total Like Score**

* Aggregate on profile.

23. **Stories UX Upgrade**

* Creation & viewing like Instagram-quality.

24. **Post Upgrades**

* Polls, threads, reshare/repost, celebrate an occasion.

25. **Proper Hashtagging**

* Extraction, linking, search integration.

26. **Age Verification ID**

* Hooks for age/ID verification flow.

27. **Cloudflare R2, Wasabi, Local Storage Options**

* Config-driven storage selection.

28. **Newsletters**

* Subscription and manage flows.

29. **Invite to Contribute**

* Invite users as experts to posts/articles.

30. **Profile Upgrades**

* Header tagline, location, top 5 skills, all skills, certifications, work experience, qualifications, references, DBS, gigs (freelancers), projects (clients), jobs (companies), available for work, public profile URL, connection count, activity, interests.

31. **Company Employment Visibility**

* Add current company to profile and show how many work there.

32. **Celebrate an Occasion**

* Dedicated post type.

33. **Multiple Languages**

* Support for the listed languages for all new features.

34. **My Network (Like LinkedIn)**

* Dedicated section for managing and exploring network.

35. **Share TikToks to Story & Viewers**

* Ability to share TikTok links into stories and view who watched stories.

---

By following this document, the agent should:

* Build a **modular Laravel package** and **Flutter addon** that wrap and extend Sociopro.
* Provide professional-network utilities, security and analytics in a **non-breaking, opt-in manner**.
* Offer clear integration points for feed, search, analytics, security, storage, and mobile UX.


Agent Instructions – Professional Social Network Utilities, Security & Analytics Package (Laravel + Flutter)
Overall Goal

Your goal is to create:

A Laravel package

A Flutter mobile addon package

that together provide the same enhanced utilities, security, and analytics functionality on both:

The Laravel backend / web app, and

The Flutter mobile app.

These will plug into an existing Sociopro-based social media platform, turning it into a LinkedIn-style professional network with modern utilities, analytics and hard security – without breaking or replacing Sociopro’s core.

⚠️ Important: Do not copy any binary files (e.g. images, fonts, compiled assets, .exe, .dll, .so, APKs, etc.).

⚠️ Important: Sociopro already provides users, posts, comments, stories, chat, marketplace, notifications, etc.
This package is a wrapper/addon that extends and augments that behaviour via new namespaced code.

Integration Principles (Sociopro-safe)

Treat Sociopro as the core engine:

Do not rename or delete core controllers, models, migrations, or routes.

This package must:

Use its own namespaces and own tables.

Interact with Sociopro via:

Existing models (e.g. User, Post, Message, Page, Group).

Events, observers, and service calls.

Be config-driven and opt-in via a single config file.

If there is a clash (route name, view name, JS var), rename on the addon side, never touch Sociopro core.

Part 1 – Laravel Package
1. Config

Create config/pro_network_utilities_security_analytics.php with:

Feature toggles, e.g.:

'features' => [
    'connections_graph'                => true,
    'recommendations'                  => true,
    'live_streaming_enhanced'          => true,
    'notifications_wrapper'            => true,
    'marketplace_escrow'               => true,
    'profile_professional_upgrades'    => true,
    'reactions_dislikes_scores'        => true,
    'stories_wrapper'                  => true,
    'post_enhancements'                => true,
    'hashtags'                         => true,
    'music_library'                    => true,
    'bad_word_checker'                 => true,
    'moderation_tools'                 => true,
    'file_scan'                        => true,
    'db_encryption'                    => true,
    'storage_backends'                 => true,
    'account_types'                    => true,
    'search_upgrade'                   => true,
    'chat_enhancements'                => true,
    'analytics_hub'                    => true,
    'security_hardening'               => true,
    'age_verification'                 => true,
    'newsletters'                      => true,
    'invite_contributors'              => true,
    'multi_language_wrapper'           => true,
];

Integration settings:

Analytics driver alias (to wrap existing analytics/logging).

Storage driver settings (Cloudflare R2, Wasabi, local).

Virus scanner endpoint / keys.

Age/KYC provider placeholder config.

Security: brute force thresholds, IP ban time, rate limits, GDPR logging flags.

All features should be safe defaults, easy to turn off if not used.

2. Database

Add new migrations only, that extend behaviour:

Connections & My Network

Tables for cached:

1st/2nd/3rd degree connections.

Mutual connections.

“network strength” metrics.

Professional Profiles

Extension tables (or user_id keyed tables) for:

Professional header tagline.

Location.

Top 5 skills (tags).

Complete list of skills (tags).

Certifications & licenses history.

Work experience history.

Qualifications / education.

References.

DBS / background check status.

“Available for work” flag.

Public profile URL / slug.

Activity & interests metadata.

Company/Agency Profiles

Tables linking:

Company pages to users as employees.

Count of employees.

Marketplace Escrow & Disputes

Escrow table linked to existing marketplace orders (foreign key).

Milestones, release, refund events.

Dispute table with messages and resolution outcome.

Delivery vs collection fields.

Stories & Live Streaming

Enhanced story metadata:

Filters, text overlays, stickers, music track ID.

Live stream table:

Host user.

Guest participants.

Donations summary.

Link to story replay.

Reactions, Dislikes & Scores

Reaction table (multi-type).

Dislikes.

Aggregated reaction score per user/profile.

Hashtags & Tags

Normalised hashtag table.

Join tables mapping tags to posts / gigs / jobs / pages / groups / webinars / podcasts etc.

Music Library

Track metadata table (title, artist, duration, license info, storage key/url).

Security & Moderation

Security events (brute force, suspicious IP, blocked login).

Moderation queue (content id, type, reason, status).

Bad word list & rules.

File scan results (file hash, status, scanner metadata).

Analytics

Aggregate stats tables:

Gigs, jobs, projects.

Companies, agencies, groups, pages.

Podcasts, webinars, interviews.

Stories views, reactions, chat metrics.

Account Types & Settings

Professional & Creator flags.

Feature toggles at user level.

Age Verification

Status table (user, status, provider key, timestamps).

Newsletters & Contribute Invites

Newsletter subscriptions.

Invite-to-contribute records (post/article + invited user + role).

Do not edit Sociopro core tables beyond SAFE nullable additions if absolutely necessary.

3. Domains (Backend Logic)

Structure in e.g. App/ProNetwork/Domains/*:

Connections & My Network

Compute 1st, 2nd, 3rd degree connections.

Provide “My Network” stats & lists.

Search & SEO Tags

Normalise and attach:

Skill tags, education tags, certificate tags.

Freelance category, jobs, gig tags.

Webinar/podcast donation/interest tags.

Provide helpers for feed/search.

Live Streaming Wrapper

Wrap existing Sociopro live (or add simple RTMP/webRTC integration if minimal) to:

Add guests to live.

Likes & reactions during stream.

Donations/tips.

Live chat stream.

Recording & linking to Story.

Notifications Wrapper

A small abstraction over existing notification system for:

comment replies, comments, likes, reactions, new followers, new connects, live events.

Marketplace Escrow & Inventory

Use Taskup-style escrow patterns:

Wrap existing marketplace orders.

Add escrow open/hold/release/refund.

Inventory adjustments.

Dispute creation/handling.

Recommendations Engine

Generate recommended:

Connections, followers.

Freelancers, freelance projects, gigs, jobs.

Pages, groups, company pages.

Live now, webinars, podcasts.

Professional Pages & Profiles

Upgrading pages to company/agency type.

Managing all professional profile fields.

Linking current company and counting employees.

Reactions, Dislikes & Hashtags

Extended reactions & dislikes.

Aggregating profile reaction score.

Hashtag parsing & linking into search.

Stories & Posts Enhancements

Story creation enhancements.

Story viewing upgrades.

Post enhancements:

Polls.

Threads.

Reshare / Repost.

“Celebrate an occasion”.

Music Library

Expose music tracks for stories.

Account Types

Professional vs Creator features.

Search Upgrade

Abstractions to build better queries using tags & professional data.

Chat Enhancements

Wrap core chat to provide:

Delete/clear chats.

Emojis, GIFs, voice notes.

Attachments.

Voice calls, video calls hooks.

Floating chat bubble logic.

Message request inbox.

Message settings & presence.

Analytics Hub

Central AnalyticsService:

track($event, $props, $user) interface.

Writes to analytics tables and forwards to existing analytics/logging.

Security & Moderation

SecurityEventService:

Brute force detection.

Hacker/blocklisting.

DB security events.

GDPR-compliant logs.

Bad word filtering & moderation queue operations.

Storage & Encryption

StorageService to:

Choose Cloudflare R2 / Wasabi / local based on config.

Handle encryption and signed URLs.

Database encryption helpers (e.g. Laravel casts).

Age Verification

Start verification, poll status, store results.

Enforce verification on specific routes if enabled.

Newsletters & Experts

Newsletter subscribe/unsubscribe.

Invite to contribute flows.

Multi-language Wrapper

Utilities to deal with multiple locales for all new features.

4. Http (Controllers & Requests)

Create namespaced controllers, e.g.:
ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\*, for:

Connections & My Network.

Recommendations.

Professional profile & page upgrades.

Marketplace escrow & disputes.

Stories & live streaming enhancements.

Post enhancements (polls, threads, reshare, celebrate).

Reactions/dislikes.

Hashtags.

Music library.

Chat enhancements.

Analytics API.

Security & moderation actions.

Newsletters.

Age verification.

Use Form Requests for validation and keep controller methods thin.

5. Policies

Register policies for:

Professional & Creator upgrades.

Managing company/agency pages.

Dispute management.

Analytics dashboards (user vs admin level).

Moderation tools.

Integrate with Sociopro’s existing roles/permissions.

6. Blade Views (Web)

Create package-scoped Blade views for:

My Network

LinkedIn-style network page (connections, invites, recommendations).

Profiles

Professional header, skills, experience, education, references, DBS, available for work, interests, activity.

Company/Agency Pages

Professional layout with employees count, jobs, gigs, projects.

Marketplace Escrow & Disputes

Escrow status widget for orders.

Dispute page (timeline, messages, actions).

Stories & Live

Story viewer (full-screen, swipe, auto-progress).

Story creator (text, stickers, music, filters).

Live viewer panel (likes, chat, donations, guests).

Posts

Poll UI.

Threaded posts display.

Reshare/repost modal.

“Celebrate an occasion” composer.

Analytics

Graphs & tables for:

Gigs, jobs, projects.

Companies/agencies, groups, pages.

Podcasts, webinars, interviews.

Stories, reactions, chat.

Moderation

Content queue list + detail+approve/reject.

Newsletters

Admin & user subscription management.

Security & Settings

Account type upgrade.

Security overview.

Age verification status.

Use Sociopro’s base layout (@extends) and consistent styling.

7. Admin Menu Entries

Add menu entries (via view composers or a config-based hook) for:

Professional Network Utilities.

Analytics.

Moderation & Security.

Marketplace Escrow.

Storage & Scanning.

Newsletters.

Each should open the relevant admin Blade view.

8. Assets (CSS & JS)

CSS/SCSS:

My Network page.

Professional profile sections.

Stories + live.

Chat bubble & panel.

Analytics dashboards.

Moderation UI.

JS:

Story creator/viewer behaviour.

Live streaming UI interactions.

Polls & threads.

Reshare modals.

Chat bubble UX.

Analytics chart rendering.

Moderation queue actions.

Reuse existing JS stack (Ajax lib, bundler) used by Sociopro.

9. Language Files

All strings in:

resources/lang/{locale}/pro_network_utilities_security_analytics.php

Provide full English; add stub arrays for:

French, German, Spanish, Portuguese, Arabic, Russian, Croatian, Italian, Yoruba, Afrikaans, Mandarin, Japanese, Hindi, Urdu, Tamil, Sinhala, etc.

10. Routes

Two groups:

Web: /pro-network/...

API: /api/pro-network/...

Each:

Protected with auth and appropriate permission middleware.

Wrapped in feature toggle checks (don’t register routes if feature off).

11. Services & Support

Services:

AnalyticsService

SecurityEventService

RecommendationService

EscrowService

ProfileEnhancementService

StoryEnhancementService

PostEnhancementService

ChatEnhancementService

StorageService

NewsletterService

AgeVerificationService

ModerationService

Support:

Enums for account types, reactions, degrees, security events.

DTOs for analytics events, recommendations, API payloads.

Helpers for tags, hashtags, SEO meta, language detection.

12. Service Provider

ProNetworkUtilitiesSecurityAnalyticsServiceProvider must:

Publish config, migrations, views, lang.

Register web + api routes.

Bind all services to the container.

Hook into events (login, post created, etc.) for analytics/security.

Set up admin menu injections.

Respect feature toggle flags.

Do not override core Sociopro bindings.

13. Documentation

README.md – install & basic config.

functions.md – for later “finishing pass”:

All functions.

All pages.

How to integrate into:

Feed.

Search engine.

Analytics system.

Security stack.

Part 2 – Flutter Addon Package

The Flutter addon is a plug-in module that:

Calls the Laravel package API.

Adds screens & components for pro utilities, analytics, security.

Hooks into the existing Sociopro mobile app navigation.

1. pubspec.yaml

Name: pro_network_utilities_security_analytics.

Declare dependencies:

HTTP client (match existing app).

State management (Provider/Riverpod/BLoC – match existing).

JSON serialization.

Charts (+ WebSocket/RTC if used).

2. Models

Dart models for:

Connections & network.

Professional profile fields.

Company/agency data.

Marketplace escrow & disputes.

Story/live metadata.

Reactions & reaction scores.

Polls, threads, reposts.

Recommendations (typed results).

Analytics metrics & chart data.

Age verification status.

Newsletter subscription.

Chat messages & conversations (wrapping existing shapes).

3. Screens / Pages

Implement screens equivalent to the web package:

My Network (connections, recommended connections, companies).

Enhanced Profile components.

Company/Agency Page.

Marketplace Escrow & Dispute UI.

Stories Viewer & Creator (Instagram-like UX).

Post Enhancements (polls, threads, reshare, celebrate).

Chat Enhancements UI:

Emoji, GIF, voice note, attachments, call buttons.

Message request inbox.

Analytics Dashboards (user and admin roles).

Moderation tools (if moderator).

Newsletters subscribe/manage.

Account & Security (account type, age verification status).

Use modern, clean design; match the host app’s theme.

4. Services (API Clients)

ConnectionsApi, ProfileApi, CompanyApi, MarketplaceEscrowApi, StoriesApi, PostsApi, ChatApi, AnalyticsApi, SecurityApi, NewsletterApi, AgeVerificationApi.

Each:

Calls Laravel endpoints.

Handles auth via host app.

Returns typed models.

Handles errors gracefully (retries, snackbars/toasts).

5. State Management

For each feature group:

A state class/BLoC/Notifier:

Holds loading/error/data states.

Drives UI based on API results.

Supports pull-to-refresh where relevant.

6. menu.dart

Export a simple List<MenuItem> or equivalent structure:

“My Network”

“Professional Profile”

“Company Pages”

“Escrow & Orders”

“Stories Creator”

“Analytics”

“Newsletters”

“Account & Security”

So the host app can directly plug these into its main navigation.

7. Client-side Analytics & Security Hooks

A thin AnalyticsClient to send:

Screen view and action events to Laravel AnalyticsService.

Optionally send safe device/app info along with security-sensitive endpoints.

No SDK replacement – just additional signals to the new backend analytics.

Required Functional Areas (Backend + Flutter)

The package must cover all of these (wrapping Sociopro where relevant):

LinkedIn-style connections (1st/2nd/3rd degree) + My Network.

Search SEO & tag utilities (skills, education, certificates, categories, donation tags).

Live streaming wrapper (guests, likes, donations, chat, record to story).

Phone notifications wrapper for comments/likes/replies/reactions.

Marketplace escrow + inventory + refunds + disputes, delivery vs collection.

Recommendation engine (connects, followers, freelancers, projects, gigs, jobs, pages, groups, companies, live, webinars, podcasts).

Upgraded company/agency pages.

Post reactions & dislikes + total reaction score on profile.

Full analytics (gigs, jobs, projects, companies/agencies, groups, podcasts, pages, webinars, interviews).

Music library for stories.

Bad word checker & moderation.

File upload malware/virus checks.

Database encryption for sensitive data.

Storage encryption & secure access.

Professional & Creator account types with feature upgrades.

Upgraded search (fast + tag-driven).

Upgraded chat (delete, clear, emojis, GIFs, voice notes, attachments, voice/video calls, floating bubble, message requests, settings, presence).

Analytics graphs & tables UI.

Security hardening (brute force, hacking, DB, GDPR for UK).

Stories UX upgrade (creation + viewing like Instagram).

Post upgrades (polls, threads, reshare/repost, celebrate).

Proper hashtags for posts.

Age/ID verification integration.

Cloudflare R2, Wasabi, local storage options.

Newsletters.

Invite experts to contribute to posts/articles.

Profile upgrades (header, location, skills, history, DBS, gigs/projects/jobs, available for work, public URL, connection numbers, activity, interests).

Company employment count in search.

Celebrate an occasion posts.

Multi-language support for all new features.

My Network section (LinkedIn-style).

Share TikTok links to stories & view story viewers.