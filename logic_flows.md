# Gigvora Logic Flows & Sub-Logic Reference

This document is the canonical map of end-to-end flows across the Gigvora platform (web, Flutter, addons, admin). Every task in `AGENTS.md` must be validated against the corresponding section here. When a flow changes, update both this file and the UI/QA trackers (`docs/ui-audit.md`, `docs/qa-bugs.md`, `docs/progress.md`).

---

## 1. Core Host App (Web)

### 1.0 Cross-Addon Roles, Permissions & Analytics (Task 20)
- **Role matrix**: `config/permission_matrix.php` centralizes platform personas (`member`, `freelancer`, `recruiter`, `company_admin`, `creator`, `moderator`, `platform_admin`) with permission slugs (`manage_advertisement`, `manage_talent_ai`, `access_admin_panel`, `manage_system_settings`, `viewAnalytics`, `viewSecurity`, `moderate`). `App\Support\Authorization\PermissionMatrix` resolves the active role (from `users.user_role` or `getUserRole()`) and maps it to gates registered in `AuthServiceProvider` plus the reusable `permission` middleware.
- **Navigation enforcement**: `App\Support\Navigation\NavigationBuilder` now defers to the permission matrix so Ads/Talent & AI/Admin/Moderation items only render when the user’s role is authorized. API navigation responses (`NavigationController`) log `analytics.navigation.rendered` events to keep admin personas in sync across web + Flutter shells.
- **Feature entry checks**: Freelance dashboard access (`DashboardController@index`) emits `analytics.freelance.dashboard.view` with role + profile identifiers after the `freelanceEnabled` gate passes, while role switching and favorites toggles (`SiteController`) emit `analytics.freelance.role.switched` and `analytics.freelance.favourite.toggled` for Utilities/Jobs/Freelance cross-journeys.
- **Analytics pipeline**: `App\Events\AnalyticsEvent` + the unified `ForwardJobsAnalyticsEvent` listener forward all matrix-defined events into `ProNetwork\Services\AnalyticsService` (queue `analytics`), preserving the existing Jobs event contract while adding cross-addon taxonomy entries (`analytics.ads.*`, `analytics.talent_ai.*`, `analytics.admin.*`).

### 1.1 Authentication & Session Lifecycle
- **Primary flow**: landing → register/login → email verification → onboarding wizard → feed.
- **Sub-logic**:
  - Password reset, 2FA prompt, session timeout + re-auth.
  - Device/session management from settings.
  - GDPR delete/export request: queues background anonymization.

### 1.2 Live Feed (Web Shell)
- **Primary flow**: load feed → hydrate `gv-feed-hub` layout → fetch cards (posts, jobs, gigs, live events, utilities, sponsored) → paginate/infinite scroll → interact (react, comment, share, save).
- **Sub-logic**:
  - Composer modes: text, media, polls, live, jobs/freelance quick-CTA, utilities reminder. Pills live in `resources/views/frontend/main_content/create_post.blade.php` and deep link to Jobs (`create.job`), Freelance (`freelance.*`), Live Center (`liveCenter.*`) and Utilities (`utilities.*`) anchors.
  - Composer studio: `resources/views/frontend/main_content/composer_studio.blade.php` + `MediaStudioService` add TikTok-style story/reel/longform/live editing (filters, overlays, emojis/stickers/GIFs, soundtrack selector, 4K→480p resolution presets, scheduling) with manifests persisted to `posts.studio_manifest` and `media_files.processing_manifest` for consistent playback styling.
  - Feed transformers: `App\Support\Feed\FeedCardPresenter` applies semantic badges/CTAs for post types (`job`, `freelance_project`, `gig`, `event`, `live_streaming`, `utilities_alert`, `sponsored`) ensuring typography + CTA parity across cards.
  - Post moderation: flagged content queue, soft delete, audit trail.
  - Ads injection: `AdvertisementSurfaceService` slots `newsfeed` (hero), `newsfeed_inline` (after 3rd card) and `newsfeed_lane` (recommendation rail) respect frequency caps + config.
  - Recommendation lanes: Jobs, Freelance, Live, Utilities (data from `FeedRecommendationService`) rendered inside `gv-feed-recos`; mobile parity provided via `GigvoraFeedShell` + `GigvoraFeedRecommendationLane` in `Gigvora Flutter Mobile App/App/lib/feed_shell.dart`.
  - **Header icon row (Task 9)**: `resources/views/frontend/header.blade.php` renders persona-aware icon buttons (`member`, `professional`, `hybrid`) determined by `App\Support\Persona\PersonaResolver`. Icons mirror the journeys above (Projects, Gigs, Applications, Interviews, Calendar, Events, Sessions, Marketplace, Videos, Shorts). Alert badges pull from `UtilitiesCalendarEvent` entries and are acknowledged via `POST /utilities/alerts/header` (`UtilitiesExperienceController@acknowledgeHeaderAlert`) to satisfy the 7d/3d/24h/6h/1h cadence requirement.

### 1.3 Posting & Media Uploads
- **Primary flow**: choose media → pre-validate (type, size, duration) → upload → FFmpeg duration tagging → assign to Photos & Reels (≤120s) or Videos (>120s) → attach metadata → publish.
- **Sub-logic**:
  - Chunked uploads + retry.
  - Thumbnail generation, orientation fixes.
  - Content moderation hook before publish.
  - Post-edit flow: open modal → fetch existing media → update captions/tags → audit version.

### 1.4 Search & Discovery
- **Primary flow**: query entry → route to `/search` → fetch aggregated results (people, pages, groups, posts, jobs, gigs, events, videos, marketplace) → filter by tabs.
- **Sub-logic**:
  - Saved search presets (Utilities bookmarks).
  - Inline ad placements on search page.
  - Hashtag landing (#tag) with follow/subscribe.
  - Typeahead suggestions (recent queries, trending topics).

### 1.5 Profile & Journey
- **Primary flow**: visit profile → render hero card + nav tabs (`gv-profile-tabs`) → show insights (ProfileInsightsService) + journey steps (ProfileJourneyService) → load timeline/photos/videos/saved/check-ins.
- **Sub-logic**:
  - Cover/photo editing, profile lock/unlock, and hero quick actions (Jobs, Freelance, Live, Utilities) surfaced via `gv-profile-quick-actions` and mirrored in Flutter through `GigvoraProfileShell`.
  - Right rail (about, quick links, utilities pills, Photos & Reels/Videos preview, friends list) implemented in `frontend/profile/profile_info.blade.php`.
  - Photos & Reels grid vs Videos (long form) powered by `Media_files::photosAndReels()` / `longVideos()` and duration metadata.
  - Opportunities cards (jobs/freelance/live), utilities reminders, and journey CTAs linking to Jobs/Freelance/Interactive creation screens.
  - Media Hub (`profile.mediaHub`, `gv-media-hub`) aggregates Photos/Reels, long-form videos, live sessions, webinars/podcasts, and Utilities quick tools; Flutter mirrors via `GigvoraMediaSwipeShell` for swipeable reels/live/podcast rails.
  - **Gigvora Verify program (Task 9)**: Verification lives inside the profile dropdown (`frontend/header.blade.php`) and is orchestrated by `GigvoraVerifyService`. Eligibility requires ≥2,500 followers or ≥1,000 connects, ≥5,000 cumulative likes, and account age ≥60 days; configuration resides in `config/gigvora_verify.php`. `BadgeController@payment_configuration` stores eligibility snapshots, locks profiles during review (`users.profile_locked_for_verification`), and routes to checkout only when requirements are satisfied. Badge status (Verified / Under review / Not verified) surfaces via `.status-pill` and `Badge::isActive()` on both feed/profile cards and dropdown copy.

### 1.6 Pages & Companies
- **Primary flow**: create page → configure details/logo/cover → publish → manage posts/events/jobs.
- **Sub-logic**:
  - Admin role assignment, member invites.
  - Utilities widgets (analytics, reminders) embedded in dashboards.
  - Linked Jobs postings + cross-post to feed.
  - Company cards feeding Profile insights & recommendation rails.
  - `resources/views/frontend/pages/page-timeline.blade.php` uses the `CommunitySurfaceService` panels (stats, jobs, gigs, events, analytics) inside the `gv-shell` layout, shares tabs for About/Feed/Jobs/Events/Analytics, and renders context-aware Utilities quick tools via `components.utilities.quick-tools` (context `page` powered by `UtilitiesQuickToolsService`).
  - Flutter/mobile parity consumes the same data through the navigation builder so page insights/jobs/events appear under the shared tabs.

### 1.7 Groups & Communities
- **Primary flow**: discover groups → request join/auto-join → consume posts/events → manage membership.
- **Sub-logic**:
  - Approval queue + notifications.
  - Group events hooking into Interactive addon.
  - Group jobs/gigs hooking into Jobs/Freelance.
  - Moderation tools (mute, remove, report).
  - Group discuss view mirrors the page shell with `CommunitySurfaceService::groupPanels`, exposing analytics, jobs/gigs/events rails, and contextual Utilities quick tools (context `group`) so moderators can jump to reminders, moderation center, or job/event creation directly from the sidebar.

### 1.8 Events & RSVPs (Core Host)
- **Primary flow**: user creates event → selects type (in-person/virtual) → configures schedule + venue → publishes to feed/groups/pages → attendees RSVP (going/interested) → reminders sent → event day timeline (check-in, live link) → wrap-up + feedback.
- **Sub-logic**:
  - Ticketing/invite-only events with approval steps.
  - Export guest list + contact attendees (Utilities notifications).
  - Event chat threads bridging Interactive addon for live components.
  - Analytics: views, RSVPs, attendance conversion, recorded in admin dashboards.

### 1.8 Notifications & Utilities Surfaces
- **Primary flow**: events trigger notifications → store (Utilities) → user accesses bell dropdown → mark read/unread or open full notifications page.
- **Sub-logic**:
  - Digest emails & push notifications.
  - Bookmark/save toggles for posts/jobs/gigs/events.
  - Calendar reminders (interviews, webinars, milestones) synced with Utilities calendar.
- **UI contract**: `resources/views/utilities/notifications.blade.php` renders hero stats plus grouped lanes (new vs earlier) with `.gv-notification-stream` scrollers, `.gv-stat-tile` cards, and filter chips powered by `resources/js/utilities/notifications.js`. Each row uses `.gv-notification-row` and the new AJAX endpoint (`utilities.notifications.read`) so type-specific actions (Jobs interviews, Live invites, Utilities reminders) align with `App\Http\Controllers\UtilitiesNotificationActionController` while retaining the same payload structure defined here.

### 1.9 Settings & Account
- **Primary flow**: open settings → sections (profile, privacy, notifications, utilities, jobs, freelance, live, security) → edit → persist via API.
- **Sub-logic**:
  - Preferences for addons (alerts, availability, AI BYOK).
  - Connected accounts (OAuth providers, payment tokens).
  - GDPR data export/delete pipeline.

### 1.10 Admin Shell
- **Primary flow**: admin login → dashboard overview → navigate to addon panels (Jobs, Freelance, Ads, AI, Interactive, Utilities) → manage entities/logs.
- **Sub-logic**:
  - Role/permission enforcement.
  - Filters, bulk actions, CSV export.
  - System health metrics & audit log viewer.

- **Messaging & Inbox (1.11)**
  - **Primary flow**: access inbox → conversation list (1:1, group, company) → open thread → send text, emoji, GIF, media, voice notes, files → typing + read receipts → archive/pin/mute → search within thread.
  - **Sub-logic**:
    - Presence indicators (online/offline, last seen), status messages.
    - Attachments reuse media pipeline (virus scan, duration tagging for voice/video).
    - Thread actions: add/remove participants, rename, set topic, star messages, reply/forward.
    - Notification fan-out to Utilities (bell, push), mobile deep links.
    - AI assistant (summaries, suggested replies) accessible per thread for premium roles.
    - Admin abuse set: report conversation, block user, export transcript, escalation queue.
    - Integration with Jobs/Freelance: open chat directly from application/bid, attach job card preview.
- **Composer & utilities bridge**: Inbox threads now consume `App\Services\UtilitiesComposerAssetsService` so emoji packs, sticker packs, Utilities reactions (Like/Love/Celebrate/Insightful/Support/Curious/Dislike), and GIF search live in `/api/utilities/composer/assets|gifs` (see `UtilitiesComposerController`). `resources/js/utilities/composer.js` hydrates toolbar toggles, while `ChatController@react_chat` uses `ProNetwork\Services\ReactionsService` for reaction persistence. Flutter mirrors the surface via `Gigvora Flutter Mobile App/App/lib/inbox_shell.dart`, exposing `GigvoraNotificationsPanel` + `GigvoraInboxComposer` widgets that share the same utilities-driven data.

- **Marketplace & Commerce (1.12)**
  - **Primary flow**: browse marketplace → filter by category/location → view product → message seller or purchase via external link/reservation.
  - **Sub-logic**:
    - Seller dashboard (inventory, orders, inquiries).
    - Boosted listings tie into Advertisement addon.
    - Safety checks: prohibited items detection, report listing, auto-disable expired items.
    - Web view now uses the Gigvora shell (`frontend/marketplace/products.blade.php`) with tokenized filters, analytics snapshot (`CommunitySurfaceService::marketplacePanels`), `advertisement` slots, and Utilities quick tools context `marketplace`. The seller dashboard (`user_products.blade.php`) mirrors the same layout with manager-specific quick tools (`marketplace_manager`) and actionable cards for edit/delete/analytics.

- **Stories & Live Moments (1.13)**
  - **Primary flow**: user taps “Add Story” → capture/upload media → apply overlays/stickers (utilities quick tools) → set audience/privacy → publish → viewers watch via story rail (`gv-story-card` / `GigvoraStoryRail`).
  - **Sub-logic**:
    - Story insights (views, reactions) + share to Highlights.
  - Story studio inherits the same manifest/resolution pipeline as the feed composer so uploaded stories (web + Flutter) honor filters/overlays/stickers/GIFs, soundtrack, and resolution caps; see `StoryController@create_story`, `media_files.processing_manifest`, and `frontend/story/*.blade.php`.
    - Utilities enhancements: polls, reminders, CTA threads surfaced through `gv-story-quick-tools` and the in-viewer toolbar + Utilities routes.
    - Expiration + archival (24h) with retention controls for admins.
    - Cross-post to Interactive live sessions when streaming.

- **Video/Livestream Upload Management (1.14)**
  - **Primary flow**: schedule livestream → configure title/guest → auto-create post + notifications → go live (Interactive shell) → record + publish replay to Videos section.
  - **Sub-logic**:
    - Live chat moderation, slow mode toggles.
    - Engagement surface: `LiveEngagementService`, `LiveEngagementController`, `/live-engagement/*` endpoints power donation goals, leaderboards, viewer stats, and feed/live-shell overlays via `live_streaming_type_post_view.blade.php` + `frontend/live_streaming/index.blade.php`, ensuring donations/reactions/questions sync with Utilities notifications.
    - Multi-stream to company pages/groups.
    - Replay trimming, captions, chapter markers.
    - Ads insertion points defined via Advertisement addon surfaces.

- **Persona-Specific Journeys (1.15)**
  - **Job seeker**: onboarding → build profile/CV → follow companies → browse/apply jobs → track ATS status → schedule interviews → accept offers → share milestones to feed.
  - **Freelancer**: onboarding → create gigs/portfolio → bid on projects → deliver milestones → manage payments/reviews → showcase work on profile/feed.
  - **Client/employer**: set up company/page → post jobs/projects → manage ATS/pipelines → collaborate with recruiters → handle offers/contracts → review analytics.
  - **Recruiter/headhunter**: manage mandates, pipelines, interviews, talent pools, analytics dashboards, candidate comms.
  - **Content creator**: configure creator mode → schedule posts/stories/live shows → monetize via ads/donations/merch → access analytics + sponsorship tools.
  - **Admin/moderator**: monitor reports, approve content, manage feature flags/nav tokens, run audits, oversee addon health.
  - **Mobile parity**: each persona flow mirrored in Flutter with tokenized UI, offline states, and Utilities-driven notifications.

---

## 2. Media, Uploads & Asset Management

- **Photos & Reels**: short-form uploads, automatic categorization, album management, share to feed/profile.
- **Videos (Long-form)**: >120s, video detail pages, metrics (views, likes), embed in feed & search.
- **Stories**: create story → attach media/effects → schedule or publish → stories rail integration → expiration/archival.
- **Metadata enforcement**: `Media_files` assigns `duration_seconds` + `is_reel` on save via `MediaDurationService`, ensuring queries split short-form reels from long-form videos consistently across profile, feed, and mobile.
- **File storage**: S3/local selection, signed URLs, cleanup jobs for orphaned media.
- **Document uploads**: resume/CV for Jobs, scope docs for Freelance, attachments for Messaging (virus scan, file-type restrictions).
- **Asset governance**: media retention policy, per-addon storage quotas, CDN invalidation, backup/restore procedures.

---

## 3. Addon Logic Flows

### 3.1 Jobs Addon (`logic_flows.md#jobs-addon`)
- **Candidate journey**:
  - Browse/search (filters, keyword, location, remote toggles) → view job detail → save job → start multi-step application wizard.
  - Application steps: CV/Resume builder (templates, auto-fill from profile, upload PDF), cover letter composer (AI suggestions, saved drafts), screening questions, attachments/portfolio links, review & submit.
  - Sub-logic: ATS-friendly export, duplicate detection, withdraw/edit application, compliance exports, share application to profile timeline, interview scheduling integration, post-rejection feedback survey.
- **Interview sync**: every create/update/delete on `InterviewSchedule` now emits Utilities notifications + calendar entries for the candidate and employer, keeping feed/profile/Flutter timelines consistent with ATS state.
- **ATS status telemetry**: `JobApplication` status or note changes trigger `UtilitiesInterviewSyncService::syncApplicationStatus`, emitting Utilities calendar entries (`jobs_application_status`), notifications (`job_application_status*`), and reminder metadata so pipelines, digests, and feeds stay aligned without polling the Jobs addon.
- **Web shell alignment**: `resources/views/vendor/jobs/index|saved|show|apply.blade.php` use the Gigvora layout (`layouts.app`) with `.gv-card`, `.gv-btn`, quick tools context (`jobs`, `job_detail`), and inline stats. Navigation + feature flags read from `config/jobs.php`, mirroring the Utilities bubble + feed CTA expectations.
- **Recruiter/employer journey**:
  - Job creation wizard (basics → description → requirements → salary → screening form → ATS tags) with autosave/drafts.
  - ATS pipeline board (Applied, Screening, Interview, Offer, Hired) with drag/drop, bulk status changes, collaborative notes, attachments.
  - Interview scheduling: slot proposals, candidate self-select, reschedule/cancel, auto-create Interactive interview rooms, reminder sync to Utilities.
  - Offer management: offer letter builder, e-signature capture, acceptance tracking, onboarding tasks.
  - Sub-logic: recruiter reassignment, approvals, job cloning, internal/external posting, compliance audit log.
- **Employer portal**: `Jobs\Http\Controllers\EmployerPortalController` exposes dashboard metrics, jobs list, ATS board, candidate detail, interview schedule/calendar, billing/credits, and company profile routes under `/employer/**`, all secured by `jobs.middleware.web_protected` and the role map in `config/jobs.php`.
- **ATS & analytics**:
  - CV parsing pipeline, keyword scoring, candidate ranking, webhook/HRIS export.
  - Dashboard for funnel conversion, time-to-hire, source tracking, recruiter performance.
- **Recommendations & alerts**:
  - Job alerts (email/in-app) with AI suggestions, saved search triggers.
  - Utilities notifications for application status changes, interview reminders, ATS tasks due, employer recommended candidates. Saved jobs/bookmarks feed the Utilities “Saved” hub so candidates can reopen roles from one place, while employers see pinned candidate notes sourced from `job_applications.notes`.
- **Search/feed hooks**:
  - `App\Http\Controllers\Report\SearchController` pipes Jobs results into `frontend/search/searchview.blade.php` using `Jobs\Support\Search\JobSearchService`, rendering job cards inline with other result types and linking back to `jobs.show`.
  - Feed CTA buttons for jobs reuse `components.utilities.quick-tools` contexts (`jobs`, `job_detail`) so saving, reminders, and Utilities notifications remain in sync.
- **Host integration hygiene**:
  - Legacy `App\Http\Controllers\ApiController` job endpoints have been removed; `/api/jobs` traffic now exclusively hits the Jobs addon controllers declared inside `Gigvora-Addons/Jobs-Addon/Jobs_Laravel_package/routes/api.php`.
  - Web navigation/composer/quick actions check `config('jobs.features.enabled')` and route helpers (`jobs.index`, `create.job`) before surfacing Jobs entry points; posting shortcuts only appear for employer roles defined under `config('jobs.roles.employer_access')`, while general members are redirected to the seeker experience.
  - Left navigation and profile insights now link to `jobs.index`, mirroring the shared nav config (`config/navigation.php`) and ensuring sticky rails stay consistent with Task 9 requirements.

### 3.2 Freelance Addon
- **Freelancer journey**:
  - Onboard (skills, rates, availability, verification) → create gig (packages, add-ons, FAQs, media gallery) → submit for moderation → publish.
  - Order management: receive gig purchase, accept or request clarification, manage progress with milestone checklist, AI chat helper, reminders.
  - Delivery workflow: upload deliverables, handle revision requests, finalize submission, auto-request review, funds release to wallet, withdraw funds.
  - Sub-logic: rush delivery, extras upsell, cancellations, SLA enforcement, disputes (mediation, escalation, evidence management).
- **Client journey**:
  - Browse/search gigs (filters, ratings, price) → purchase package → configure requirements → chat with freelancer → monitor progress timeline.
  - Projects/contracts: post project, receive bids, shortlist, negotiate, award, set milestones, fund escrow per milestone, approve/reject deliverables, close contract, review.
  - Sub-logic: refund policies, partial release, milestone rollback, convert job posting into freelance contract.
- **Integrations & analytics**:
  - Feed cards, profile portfolio entries, Jobs → Freelance conversion.
  - Utilities reminders for milestones, escrow funding, dispute deadlines.
  - Dashboards for freelancer earnings, client spend, repeat work rate.
  - Workspace snapshots: `FreelanceWorkspaceService` powers web dashboards and `GET /api/freelance/workspace` so Flutter/mobile clients render the same KPIs, contracts, escrow state, and ad slots (`docs/progress.md#task-12`).
  - Search & feed surfaces use `FreelanceSearchService` to highlight live projects, gigs, and talent inside recommendation lanes and the global search page, matching the discoverability expectations in `#32`/`#54`.

### 3.3 Interactive Addon (Live / Webinars / Networking / Podcasts / Interviews)
- **Event host**:
  - Create event (webinar/networking/podcast/interview) → configure schedule/content/tickets → publish/promote → manage registrations → run live session (waiting room, live shell) → publish replay.
  - Sub-logic: host/producer roles, co-host invitations, attendee removal, donation/ticket refunds, merch sales, sponsor slots, recording fallback.
- **Attendee**:
  - Discover event → register/save → get reminders → join waiting room → participate (chat/Q&A) → provide feedback.
- **Webinar specifics**:
  - Ticket tiers (free/paid/donation), coupon codes, seating caps, reminder cadence, backstage vs live stage, screen share/polls/Q&A, AI-generated highlight reels.
  - Recording workflow: auto-record, trim, chapters, transcripts, gated replays with expiry.
- **Networking sessions**:
  - Session setup (rounds, duration, topics), ticketing, AI match suggestions, timed rotations, contact exchange, follow-up scheduler, host dashboard.
- **Podcasts**:
  - Series/editor management, episode scheduling, guest intake, recording (live/upload), monetization (ads, donations, paid episodes), catalog filters, donation widgets.
- **Interviews**:
  - Slot offering, interviewer panel, scoring forms, structured notes, candidate join assistance, fallback dial-in, scoring consensus, feedback distribution.
  - Slots now push Utilities calendar entries + notifications for interviewers/interviewees, and cancellation/reschedule events immediately update Utilities timelines.
- **Admin & analytics**:
  - Event performance dashboards (registrations→attendance), revenue, engagement metrics.
  - Recording management (transcoding, storage quotas), consent logs, export for audits.
  - Reference docs: `Gigvora-Addons/Interactive-Addon/.../about.md`, `functions.md`.
- **Experience orchestration & hub**:
  - `App\Services\LiveEventsExperienceService` aggregates upcoming webinars, networking sessions, podcast episodes, and interviews (with counts, status pills, and ad slots) for the Live hub (`resources/views/live/hub.blade.php`), recommendation lanes (`FeedRecommendationService`), and Utilities quick tools so the Interactive addon reuses the same tokenized layout everywhere.
  - Waiting rooms (`wnip::webinars.waiting_room`, `wnip::networking.waiting_room`, `wnip::interviews.waiting_room`) expose countdowns, status pills, and CTA enablement logic via shared Blade components (`wnip::components.waiting_room_header`) plus JS timers, ensuring web + Flutter stay in sync with interview/webinar/networking start states.
  - Live shells (`wnip::webinars.live`, `wnip::networking.live`, `wnip::podcasts.live`, `wnip::interviews.live_candidate`) rely on `.gv-card`/`.gv-pill` tokens, Utilities quick tools, advertisement slots, and shared partials (`live_chat_panel`, `notes_sidebar`, `host_tools_toolbar`, `calendar_widget`) so all Interactive sessions feel consistent with the Gigvora design system and support Utilities reactions/notes.

### 3.4 Advertisement Addon
- **Advertiser journey**:
  - Access Ads Manager → campaign wizard (objective → audience → placements → creatives → budget/schedule) → keyword planner/forecast → review & launch.
  - Sub-logic: AI creative assistant, reusable creative library, billing adjustments, pause/archive, duplication.
- **Ads management**:
  - Campaign/ad set/ad tables with inline edits, bulk actions, scheduling, pacing alerts, split tests, rules automation.
- **Keyword planner & simulations**:
  - Research volume/competition, negative keyword management, forecast outcomes for budget changes, scenario planning.
- **Analytics**:
  - Real-time dashboards, custom reports, anomaly detection, conversion tracking, attribution.
- **Settings & billing**:
  - Payment methods, invoices, credit wallets, team permissions, brand safety lists, compliance.
- **Placement surfaces**:
  - Feed, profile, search, jobs/freelance listings, marketplace, stories, live events overlays, respecting `config/advertisement.php` surfaces.
- **Admin**:
  - Creative policy review, advertiser verification, invalid click disputes, refunds, spend monitoring.
- **Reference docs**: `Gigvora-Addons/Advertisement-Addon/.../about.md`, `functions.md`.
- **Bid & pricing engine**:
  - `Advertisement\Services\BidStrategyService` normalizes keywords, aggregates targeting + metrics across campaigns, and stores smart CPC/CPA/CPM baselines (volume, competition, quality, placement multipliers) in `keyword_prices`. Keyword planner + simulations rely on this cache so CPC/CPA/CPM respond to campaign competition without heavy queries.
- **Placement scoring & delivery**:
  - `App\Services\AdvertisementSurfaceService` now evaluates campaigns per slot (`newsfeed`, `newsfeed_inline`, `profile`, `search`, `jobs`, `freelance`, `marketplace`, `groups`, `pages`, `live_overlay`, `story_interstitial`, `video_swipe`, etc.) using CTR, CVR, pacing, freshness, and diversity weights defined in `config/advertisement.php`. Slots return creatives enriched with media metadata for feed cards, banners, story/video swipe surfaces, and live overlays.
- **Story/live/video placements**:
  - Story viewer (`resources/views/frontend/story/story_details.blade.php`), live streaming cards (`frontend/main_content/live_streaming_type_post_view.blade.php`), and media swipe shells (`frontend/main_content/media_type_post_view.blade.php`) embed `story_interstitial`, `live_overlay`, and `video_swipe` ads so TikTok-style stories, live donations, and mobile swipes respect AGENTS Task 13 requirements.

### 3.5 AI / Talent & AI Addon
- **Headhunter pipeline**:
  - Mandate intake (client brief) → sourcing (AI suggestions, imports) → candidate dossier (resume, notes) → drag/drop pipeline with automation → interview triggers → offer tracking → placement + fee logging.
  - Sub-logic: collaboration (tasks, internal chat), compliance logs, billing (retain/contingency), analytics (time-to-fill).
- **Cross-surface insights**:
  - `TalentAiInsightsService` hydrates feed + profile cards (`gv-talent-ai` block) with open mandates, pipeline counts, Launchpad progress, volunteering hours, and AI workspace usage.
  - Feed lane (`gv-feed-recos`) now includes Talent & AI sections (Launchpad, Volunteering, Headhunter pipeline) so Jobs/Freelance flows can jump into the addon surfaces.
  - Profile timeline inherits new cards (`talent_ai_metrics`, `talent_ai_cards`) while the profile sidebar shows launchpad/volunteering/AI quick stats.
- **Quick tools context**:
  - Utilities quick tools (`components.utilities.quick-tools`) detect `addons/talent-ai/*` routes and surface shortcuts for Headhunters dashboard, Launchpad programmes, AI Workspace, and Volunteering screens.
- **Calendar + reminders**:
  - `TalentAiHeadhunterInterviewObserver` listens to `HeadhunterInterview` create/update/delete events and mirrors them into `UtilitiesCalendarService`, so interview reminders land on feed/profile and Utilities calendar timelines automatically.
- **Experience Launchpad**:
  - Programme builder (curriculum, modules, checkpoints, mentors).
  - Participant flow: apply → screening → onboarding kit → module progression (content, assignments, quizzes) → mentor reviews → capstone → certification → alumni community.
  - Sub-logic: AI nudges, leaderboards, cohort forums, re-enrollment, scholarship management.
- **AI Workspace**:
  - Prompt templates, BYOK provider management, execution history, result sharing, AI chat (resume optimizer, JD builder, coaching tips).
  - Sub-logic: credit tracking, moderation, rate-limits, outage fallback.
- **AI workspace backend**:
  - `AiProviderService` now supports OpenAI (configurable model + platform key) and user BYOK credentials stored encrypted via `AiByokCredential`.
  - API responses (`ToolController`) return normalized payloads (`result`, `variants`, `provider`, `model`) so both web + Flutter experiences can render AI drafts consistently.
- **Volunteering**:
  - Opportunity catalog, applications, hours logging, verification, badge issuance, integration with profile achievements.
- **Admin & compliance**:
  - Provider credential vault, audit logs, curriculum versioning, policy management.
- **Reference docs**: `Gigvora-Addons/Ai-Headhunter-Launchpad-Addon/.../about.md`, `functions.md`.

### 3.6 Utilities Addon
- **Notifications**:
  - Ingestion from jobs/freelance/live/posts → queue → fan-out to channels.
  - Sub-logic: batching, digest configuration, retention policies.
- **Interview reminders & digest**:
  - `InterviewReminderService` derives 7d/24h/6h/1h reminders from `utilities_calendar_events` (sources `jobs_interview`, `interactive_interview`, `jobs_application_status`) and surfaces them on feed/profile/Utilities notifications plus mobile API payloads.
  - Digest widget summarises scheduled/rescheduled/completed/cancelled interviews per user and syncs with header alert badges + Utilities calendar.
- **Bookmarks/Saved Items**:
  - Toggle save on posts/jobs/gigs/events → view/manage saved lists.
  - Sub-logic: dedupe, cross-device sync, limit enforcement. Saved jobs (Jobs addon bookmarks) and recruiter candidate notes now hydrate the Utilities Saved hub so both personas access interview prep artefacts.
- **Calendar & Reminders**:
  - Create reminders/interviews/events → integrate with Jobs & Interactive → send snooze/dismiss actions.
- **Quick Tools**:
  - Floating utilities bubble (chatbot, shortcuts, calculators) across feed/profile/jobs/freelance.
  - `/api/utilities/quick-tools?context={feed|profile|jobs|job_detail|freelance|interactive|admin}` hydrates both the bubble (`resources/js/utilities/bubble.js`) and inline component `components.utilities.quick-tools`, pulling from `App\Services\UtilitiesQuickToolsService`.
  - Context mappings: feed (poll/thread/reminder/story), profile (professional upgrade/story enhancer/hashtags), jobs (saved jobs/interview reminders), job detail (bookmark role/follow-up), freelance (dashboard/proposals/disputes), interactive (live hub/reminders/saves), admin (analytics/security/moderation/ads). These surface on feed, profile, Jobs index/detail, Freelance shell, Live hub, and admin dashboard.
  - Flutter parity via `GigvoraQuickToolsClient` + `GigvoraQuickToolsPanel` (`Gigvora Flutter Mobile App/App/lib/utilities_quick_tools.dart`) so tabs/drawers can render the same quick utilities rail.
- **Admin center**:
  - Notification templates, throttling rules, channel enablement.
  - Bookmark taxonomies (per resource type), retention policies.
  - Calendar integrations with external providers (ICS export, sync toggles).

### 3.7 Cross-Addon Journeys
- Feed discovery → CTA to Jobs/Freelance/Interactive → profile deep-link → settings/preferences update.
- Jobs ↔ Utilities: interview reminders, saved jobs, notifications.
- Jobs/Interactive ↔ Utilities: shared interview timeline powers feed/profile cards on web + Flutter via `UtilitiesCalendarEvent`, ensuring reschedules/cancellations propagate instantly.
- Jobs ATS ↔ Utilities: application status changes + interviewer/employer notes replicate through the reminder/digest service so notifications, saved hubs, and Flutter payloads remain in sync with pipelines.
- Freelance ↔ Interactive: invite successful freelancers to webinars/podcasts; convert interviews to contracts.
- Admin oversight: aggregated dashboards, metrics export, role adjustments.
- Search ↔ Ads: sponsored slots inline with organic results while respecting caps.
- Pages/Groups ↔ Addons: page admins boost posts with ads, host events via Interactive, post gigs/jobs directly.
- Flutter ↔ Web parity: every journey above mirrored with mobile-friendly screens and deep links.

### 3.8 Admin & Compliance Addon Hooks
- **Global governance**:
  - Role provisioning, permission templates per addon.
  - License removal audit (Envato → Gigvora cleanup).
  - Security scanning + dependency monitoring.
- **GDPR tooling**:
  - Data subject requests flow (collect request, verify identity, export/delete data across addons).
  - Logging of actions for compliance review.
- **Docs**: update `docs/qa-bugs.md`, `docs/nav-structure.md`, addon `about.md`/`functions.md` for every change.

---

## 4. Admin & Compliance

- **Roles & Permissions**:
  - Core roles: member, freelancer, recruiter, company admin, page admin, group admin, host, interviewer, moderator, platform admin.
  - Feature flags + AB tests documented with rollout plans.
- **Audit trails**:
  - Payment/escrow events, interview score submissions, ad billing changes, AI prompt executions, admin actions.
- **Security flows**:
  - License removal, encryption enforcement (password hashing, BYOK key storage), GDPR export/delete, consent banners, cookie preferences.
  - Incident response: detecting suspicious logins, rate-limit trips, WAF alerts.
- **Monitoring & Ops**:
  - Queue health dashboards (jobs for notifications, video processing, AI tasks).
  - Media processors (FFmpeg/FFprobe) availability checks.
  - Addon health pings + dependency version tracking.
  - Backup/restore drills logged in `docs/progress.md`.

---

## 5. Flutter Mobile App

### 5.1 Shell & Navigation
- Tabs: Feed, Jobs, Freelance, Live & Events, Profile (mirrors nav builder).
- Drawer/secondary menus: Notifications, Saved, Utilities tools, Settings.
- Deep links: push notifications/links land on correct addon screens with context IDs.

### 5.2 Feed & Composer
- Load feed + recommendation rails (people, roles, utilities).
- Composer parity (posts, media, jobs/freelance CTAs).
- Offline mode caching + retry queue.
- Profile/media parity: Flutter exposes `GigvoraProfileShell`, `GigvoraStoryRail`, and `GigvoraMediaSwipeShell` so tabs mirror the new hero, story quick tools, and swipeable webinars/podcasts/live-stream rails.

### 5.3 Jobs (Mobile)
- Jobs home (search, recommendations, alerts).
- Job detail + apply (resume upload, questionnaire).
- Applications tracker + interview schedule (Interactive integration).

### 5.4 Freelance (Mobile)
- Freelancer dashboard (gigs, projects, contracts, milestones).
- Client dashboard (projects, proposals, escrow).
- Notifications for milestones/disputes.

### 5.5 Interactive / Live
- Events catalogues (webinar/networking/podcast).
- Registration/reminder flows.
- Interview schedule & scoring (role-based).

### 5.6 Advertisement (Mobile)
- Ads Manager mobile views: campaigns list, analytics, quick actions (keyword planner, forecast shortcuts).

### 5.7 Talent & AI (Mobile)
- Headhunter pipelines board (drag/drop optimized for touch).
- Launchpad progress tracker.
- AI workspace prompt execution with mobile optimized inputs.

### 5.8 Utilities (Mobile)
- Notifications center, saved lists, calendar/reminders UI.
- Floating utilities FAB per screen.

### 5.9 Settings & Account (Mobile)
- Parity with web: privacy, notifications, addon preferences, BYOK config.
- Account deletion/export flows.
- Multi-device session list + remote logout.
- Push notification preferences per addon/channel.
- Dark mode toggle (consuming same token semantics as web).

---

## 6. Supporting Documents & Maintenance

- When updating a flow:
  1. Modify implementation.
  2. Update corresponding section here.
  3. Reflect UI changes in `docs/ui-audit.md`.
  4. Record QA outcomes in `docs/qa-bugs.md`.
  5. Log milestone in `docs/progress.md`.
- Use section anchors (e.g., `#live-feed-web-shell`) in `AGENTS.md` to reference required flows for each task.
- For addon-specific behaviour, reference their `about.md` & `functions.md` inside `Gigvora-Addons/*` to ensure alignment.
- Keep this file in lockstep with `AGENTS.md` and sprint plans; missing flows should be added before implementation begins.


