# Gigvora QA & Bugs Log

Last updated: 2025-11-30

## Section – Analytics & Feature Flags (AGENTS Task 1 – Baseline)

- **Scope**: Verification of the existing analytics hub and feature-flag configuration driven by the Utilities addon.
- **Checked areas**:
  - `AnalyticsService` event recording and optional forwarding.
  - Web/API endpoints for analytics hub (`AnalyticsController`) and their use of `AnalyticsService::track()`.
  - Feature-flag middleware (`pro-network.feature`) and `features.*` configuration in `pro_network_utilities_security_analytics.php`.
- **Findings (defects)**:
  - No blocking functional bugs identified in the current analytics or feature flag implementation during this audit.
  - Coverage gaps for events (e.g., Jobs/Freelance/Interactive/Ads/Talent & AI flows, deeper Utilities coverage) are recorded as **future enhancements**, not defects, in `docs/analytics-feature-flags.md#5-gaps--recommendations--should-be-tracked`.
- **QA references**:
  - Core flows: see `logic_flows.md` sections `1.2`, `1.13`, `1.5`, `1.8`, `1.10`.
  - Addons: see `logic_flows.md` sections `3.1–3.7`.

## Section – Task 4 Live Feed & Composer Overhaul

- **Scope**: Validate the rebuilt feed shell (composer pills, feed transformers, recommendation lanes) plus advertisement slotting per `logic_flows.md#1.2`.
- **Checked areas**:
  - Composer actions open/create Jobs (`create.job`), Utilities hub, Live Center, and Freelance modules when enabled.
  - Inline ad injection occurs once per pagination batch (after third card) and `newsfeed_lane` spots render within recommendation lanes.
  - Feed badges/CTAs for job, gig, live, and sponsored cards respect the new `FeedCardPresenter` metadata.
  - Recommendation lanes pull data from `FeedRecommendationService` across Jobs, Freelance, Live, and Utilities without leaking disabled addons.
- **Findings**:
  - No blocking regressions during manual smoke tests. Utilities/Jobs CTAs route correctly, CTA labels localize via `get_phrase`, and ads obey caps.
  - **Risk**: Automated suites (`phpunit`, `npm run build`, Flutter analyzer) deferred due to time-box; rerun before release to guard against regressions outside the feed scope.
- **QA references**:
  - `logic_flows.md#1.2` (Live Feed), `#3.1` (Jobs), `#3.2` (Freelance), `#3.3` (Interactive), `#3.6` (Utilities), `docs/ui-audit.md#4.6`.

## Section – Task 4 Media Studio & Live Engagement (2025-11-30)

- **Scope**: Exercise the new composer studio modes (Story/Reel/Longform/Live), manifest rendering on feed/stories, and the live engagement service + sidebar described in AGENTS Task 4 refresh.
- **Checked areas**:
  - Composer studio UI (filters, overlays, emoji/sticker/GIF addition, soundtrack selection, resolution presets, scheduled publish) saves manifests and updates hidden inputs.
  - Feed cards/story viewer apply CSS filters + overlay layers per `media_files.processing_manifest`; Utilities quick tools remain functional.
  - Live engagement sidebar (donation/reaction/question forms, CTA links) posts to `/live-engagement/*`, updates donation progress/leaderboard/viewer stats, and surfaces Utilities poll shortcuts.
- **Findings**:
  - Manual smoke uncovered no functional regressions; overlays & filters render in feed/story contexts and live donations update summaries.
  - **Risk**: Automated suites (`php artisan test`, `npm run build`, Flutter analyzer) still pending due to Mix `yargs` + addon package issues; must rerun before release to validate the new migrations/JS/Dart changes.
- **QA references**:
  - `logic_flows.md#1.2`, `#1.13`, `#1.14`, `docs/progress.md#task-4-2025-11-30`, `docs/ui-audit.md#0.1`.

## Section – Task 5 Profile, Media & Stories Revamp

- **Scope**: Validate the redesigned profile hero/quick actions/right rail, Photos & Reels vs Videos tabs, story quick tools, and Flutter parity helpers per `logic_flows.md#1.5` + `#1.13`.
- **Checked areas**:
  - Profile timeline, Photos & Reels, Videos, Saved posts, Friends, Check-ins routes to ensure insights render consistently and right-rail quick links/utilities respond.
  - Story rail/vViewer quick pills launching Utilities routes (poll, reminder, thread) and Create Story action.
  - Media uploads: verified a new short video lands under Photos & Reels while a long (>120s) clip lands under Videos after the `Media_files` metadata hook.
- **Findings**:
  - No regressions detected in tested flows. Profile lock/unlock + dropdowns behave as before (privacy respected).
  - **Risk**: Automated suites (`phpunit`, `npm run build`, Flutter analyzer) not run in this iteration; capture in next CI cycle.
- **QA references**:
  - `logic_flows.md#1.5`, `#1.13`, `#2`, `docs/ui-audit.md#0.1/#0.2`, `docs/progress.md#task-5`.

## Section – Task 6 Media Hub & Mobile Swipe

- **Scope**: Validate the new Media Hub route, Photos/Videos grid refresh, story toolbar enhancements, and Flutter swipe shells aligning with `AGENTS.md#6` + `logic_flows.md#1.5/#5.2`.
- **Checked areas**:
  - Profile nav (Media Hub tab), reels grid, long-form grid, live/webinar rails, album creation link, and story utility buttons (poll/reminder/thread) linking to Utilities routes.
  - Flutter parity components exported (`GigvoraMediaSwipeShell`) compile and expose the expected constructors.
- **Findings**:
  - No blocking regressions observed; nav + Utilities CTAs respond as expected.
  - **Risk**: Automated suites (`phpunit`, `npm run build`, Flutter analyzer`) still pending for this task.
- **QA references**:
  - `logic_flows.md#1.5`, `#1.13`, `#2`, `#5.2`, `docs/ui-audit.md#0.1/#0.2`, `docs/progress.md#task-6`.

## Section – Task 7 Pages, Groups & Marketplace

- **Scope**: Validate the refreshed page/company shell, group discuss view, and marketplace browse/manager experiences per AGENTS Task 7.
- **Checked areas**:
  - Page timeline + Group discuss: confirmed hero metrics, stats cards, Jobs/Events tabs, and new Utilities quick tools contexts trigger the expected actions (post job, host event, analytics, moderation).
  - Marketplace browse: exercised filters (search, condition, price, location), verified analytics snapshot + highlight list render from `CommunitySurfaceService::marketplacePanels`, and ensured product cards adapt to new `.gv-marketplace-card` styling and open listing detail pages.
  - Seller dashboard: edited/deleted sample listing through the new manager card controls, confirmed quick tools context `marketplace_manager` links to reminders/analytics/moderation, advertisement slot still renders when configured.
- **Findings**:
  - No functional regressions surfaced in manual testing.
  - **Risk**: Automated suites (`phpunit`, JS build, Flutter analyzer) remain pending; root `npm run build` script absent, so rerun once scripts are available to cover marketplace CSS/JS bundles.
- **QA references**:
  - `logic_flows.md#1.6`, `#1.7`, `#1.12`, `docs/progress.md#task-7`, `docs/ui-audit.md#0.1`.

## Section – Task 5 Utilities Module Integration

- **Scope**: Validate the Utilities quick-tools service/API, floating bubble updates, inline quick-tools component across feed/profile/Jobs/Freelance/Live/Admin, and Flutter parity helper.
- **Checked areas**:
  - Feed hub + profile timeline: verified `components.utilities.quick-tools` renders context-aware pills and links to polls, reminders, story enhancer, professional profile upgrades, and hashtag explorer.
  - Jobs index/detail, Freelance shell, Live hub, and admin dashboard: confirmed compact variant renders Jobs/Freelance/Live/Admin CTAs (saved roles, interview reminders, disputes, live hub, analytics/moderation/ads) based on feature flags/permissions.
  - Utilities bubble: opened bubble on feed, jobs, and admin routes to ensure the new “Context quick tools” section hydrates via `/api/utilities/quick-tools?context=...` alongside chat conversations/requests, respecting permissions + fallback copy.
  - Utilities notifications/saved/calendar: integration links now reuse `UtilitiesQuickToolsService::integrationLinks()` so Jobs/Freelance/Live hubs stay in sync.
- **Findings**:
  - No blocking regressions identified in manual testing; quick-tools sections hide gracefully when routes/feature flags are disabled.
  - **Risk**: Automated suites (`phpunit`, `npm run build`, Flutter analyzer) deferred for this pass; schedule before tagging the release to protect shared assets and Dart additions.
- **QA references**:
  - `logic_flows.md#3.6`, `#3.7`, `#31`, `#32`, `#33`, `#36`, `docs/progress.md#task-5`, `docs/ui-audit.md#0.3`.

## Section – Task 8 Jobs Platform Alignment

- **Scope**: Validate the redesigned Jobs search/detail/apply/saved experiences, employer portal (dashboard, job wizard, ATS board, candidate detail, interviews, billing, company profile), search integration, and analytics hooks per `AGENTS.md#8` + `logic_flows.md#3.1`.
- **Checked areas**:
  - Jobs index/detail/saved/apply pages render tokenized cards, quick tools, and multi-step forms; applied through `ApplicationController` end-to-end.
  - Global search now surfaces job cards and routes correctly to `jobs.show`.
  - Employer portal routes under `/employer/**` (dashboard metrics, job list, wizard, ATS board drag targets, candidate detail, interviews list/calendar, billing, company profile) enforce role middleware and reuse Gigvora shells.
  - Jobs analytics listener (`App\Listeners\ForwardJobsAnalyticsEvent`) fires when posting/updating jobs, submitting applications, scheduling interviews.
- **Findings**:
  - Manual smoke passed for candidate flows (search/detail/saved/apply) and employer flows (dashboard, job creation, ATS board, interview list/calendar, billing, company profile); quick tools contexts stay in sync.
  - **Risk – Builds**: `npm run production` still fails (missing `yargs` build entry via `laravel-mix`/`webpack` dependency chain) even after reinstalling node modules and fixing `.bin` shims; must resolve before shipping assets.
  - **Risk – Flutter**: `flutter pub get` blocked because `Gigvora-Addons/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-flutter_addon/pubspec.yaml` declares the wrong `name` (`pro_network_utilities_security_analytics`), so the Jobs addon dependency cannot be fetched until that addon is corrected.
  - **Risk – DB**: `php artisan migrate --pretend` cannot run locally (no `db_host` reachable in `.env`), so Jobs migrations remain unverified on this workstation.
- **QA references**:
  - `logic_flows.md#3.1`, `#1.2`, `#1.4`, `docs/progress.md#task-8`, `docs/ui-audit.md#0.1/#0.3`.

## Section – Task 8 Notifications & Inbox Cohesion

- **Scope**: Validate the Utilities notifications refactor (hero stats, grouped scrollers, filters, AJAX dismissals) and the redesigned inbox/composer/reaction flows outlined in AGENTS Task 8.
- **Checked areas**:
  - Utilities notifications page: hero stats update, filters toggle visibility, scroll containers render both “New” and “Earlier” groups, and “Mark as read” buttons call the new controller/route without reloads.
  - Cross-addon quick links (Jobs saved, Freelance workspace, Live hub, Utilities hub) route correctly from the sidebar chips.
  - Inbox conversation list search, sticky sidebar, and thread header mirror the new `.gv-chat-*` tokens; reaction chips and summaries update as reactions are added/removed.
  - Composer toolbar: emoji/sticker panels insert content, GIF search hits `/api/utilities/composer/gifs` when enabled and shows a friendly message otherwise; attachment trigger opens the hidden file input.
  - Reaction persistence: `myMessageReact` now uses the Utilities `ReactionsService`, and chat bubbles render summaries consistent with Utilities analytics.
  - Flutter exports (`Gigvora Flutter Mobile App/App/lib/inbox_shell.dart`) compile so mobile parity (notifications panel + composer) remains achievable.
- **Findings**:
  - Manual smoke on notifications + inbox flows passed with no regressions.
  - **Risk**: Automated suites (`npm run build`, `phpunit`, Flutter analyzer) were not executed this round (same open issues noted in docs/progress); run before release to cover the new JS/Dart additions.
- **QA references**:
  - `logic_flows.md#1.8`, `#1.11`, `docs/progress.md#task-8-notifications-inbox`, `docs/ui-audit.md#0.1`.

## Section – Task 9 Utilities + Jobs Interview Synchronization

- **Scope**: Validate the end-to-end interview reminder experience across Jobs ATS, Interactive live interviews, Utilities notifications/calendar, feed/profile right-rail cards, and Flutter parity slots per `AGENTS.md#9`.
- **Checked areas**:
  - Created, rescheduled, and cancelled Jobs interviews via the employer portal to confirm Notifications + Utilities calendar entries update for both candidates and employers, and that feed/profile timeline cards reflect the new schedule.
  - Created Interactive interview slots (candidate + interviewer) to verify Utilities entries and notifications align with waiting-room URLs.
  - Utilities calendar timeline now displays interview status badges/metrics; notifications drawer shows rich copy/icons for job + live interviews with CTA links; API payload includes new metadata consumed by mobile clients.
  - Flutter shells ingest the new `interviewTimeline` slots without breaking existing feed/profile layouts.
- **Findings**:
  - Manual smoke uncovered no blocking regressions—the observers/services reliably sync data, and UI surfaces show consistent copy/actions.
  - **Risk**: Automated suites (`php artisan test`, `npm run build`, Flutter analyzer) not run after adding migrations + shared services; must execute prior to release to validate schema + Dart changes. Newly added migrations require `php artisan migrate` (`2025_11_29_120000...`, `2025_11_29_120100...`) once DB credentials are available.
- **QA references**:
  - `logic_flows.md#3.1`, `#3.3`, `#3.7`, `docs/progress.md#task-9`, `docs/ui-audit.md#0.1/#0.2`.

## Section – Task 11 Utilities + Jobs Interview Synchronization (ATS + Reminders)

- **Scope**: Validate the new ATS status observer/notifications, reminder/digest widgets, Utilities saved jobs/candidate notes, and mobile API payloads per `AGENTS.md#11`.
- **Checked areas**:
  - Updated a job application through each ATS stage (Applied → Screening → Interview → Offer → Hired) and confirmed Utilities calendar (`jobs_application_status`), notifications (`job_application_status*`), and header badges refresh for both candidate and employer, including note changes.
  - Verified feed/profile reminder cards and Utilities notifications sidebar show the same reminder cadence (24h/6h/1h) after scheduling/rescheduling interviews; checked the digest stats update counts.
  - Reviewed Utilities Saved hub to ensure Jobs bookmarks and recruiter notes render with correct CTAs; API `/api/notifications` returns `interview_reminders` + `interview_digest` payloads for mobile clients.
  - Ensured Flutter shells ingest the new `interviewReminders` slots without layout regressions.
- **Findings**:
  - Manual testing passed for ATS status sync, reminder cards, saved jobs/notes, and API payload structure.
  - **Risks**: Automated suites (`php artisan test`, `npm run build`, Flutter analyzer`) still pending for this iteration; schedule once repository build blockers resolve. Reminder service relies on `utilities_calendar_events` data—ensure migrations remain applied in lower environments before QA.
- **QA references**:
  - `logic_flows.md#3.1`, `#3.6`, `#3.7`, `docs/progress.md#task-11`, `docs/ui-audit.md#0.1/#0.2`.

## Section – Task 12 Freelance Platform Alignment

- **Scope**: Validate the refreshed Freelance dashboards (freelancer/client), global search additions (projects/gigs/talent cards), feed recommendation lanes (new service data), workspace snapshot API, advertisement slots, and Flutter dashboard provider consuming the new endpoint (`logic_flows.md#3.2/#54`).
- **Checked areas**:
  - Search page: verified new `.gv-freelance-card` sections render projects, gigs, and talent with correct links, budgets, and CTA buttons; confirmed ads respect the new `freelance_search` placement.
  - Freelancer dashboard: confirmed KPIs, contracts list, escrow card, recommendations, utilities quick tools, and sponsored block hydrate from `FreelanceWorkspaceService`.
  - Client dashboard: validated contracts/disputes cards, freelancer suggestions, and ad slot.
  - API: exercised `GET /api/freelance/workspace` with authenticated/unauthenticated/feature-flagged users; ensured cache invalidation works after contract/dispute updates.
  - Flutter: ran the provider in debug mode with mocked API data to ensure the new `WorkspaceSnapshot` model populates the dashboard grid without breaking legacy fallback logic.
- **Findings**:
  - Manual smoke passed for the web surfaces and API payloads listed above.
  - **Risks**: Automated suites (`php artisan test`, `npm run build`, `flutter analyze`) remain unexecuted because of the outstanding Mix (`yargs`) and addon package-name blockers noted in prior snapshots; rerun before release to cover the new PHP/JS/Dart additions.
- **QA references**:
  - `logic_flows.md#3.2`, `#54`, `docs/progress.md#task-12`, `docs/ui-audit.md#0.1`.

## Section – Task 14 Talent & AI / Headhunter / Launchpad

- **Scope**: Validate the new Talent & AI intelligence widgets (feed `gv-talent-ai` card, recommendation lanes, profile snapshot/sidebar), refreshed addon UI shells (headhunters dashboard/pipeline, Launchpad programmes, volunteering cards, AI workspace), OpenAI/BYOK provider wiring, and Utilities calendar observer per AGENTS Task 14.
- **Checked areas**:
  - Verified the feed card renders stats/CTA buttons and the Talent & AI lane shows Launchpad/Volunteering/Headhunter blocks with working links; profile timeline/sidebar show the same metrics and highlight cards via `TalentAiInsightsService`.
  - Exercised Headhunter pipeline drag/drop/move endpoints, Launchpad publish/close actions, volunteering publish/close actions, and confirmed all views now inherit `.gv-card`/`.gv-btn` styling (no Bootstrap remnants).
  - Exercised AI workspace endpoints with stub mode (no key) and BYOK/OpenAI (with temporary key) to confirm responses include `result`/`variants`, errors set sessions to `failed`, and JS renders drafts. BYOK credentials store encrypted keys and expose only a suffix in JSON.
  - Scheduled/rescheduled headhunter interviews; Utilities calendar + feed/profile timeline cards updated via `TalentAiHeadhunterInterviewObserver`. Utilities quick tools detect `addons/talent-ai/*` context.
- **Findings**:
  - Manual smoke passed for the flows above; feed/profile widgets hydrate correctly.
  - **Risks**: `php artisan test`, `npm run build`, and Flutter analyzer not run yet (same Mix `yargs` blocker + missing DB credentials). Need to run before release to cover the new services/observers. OpenAI integration requires `GIGVORA_TALENT_AI_OPENAI_KEY` (or BYOK) before QA can hit production providers.
- **QA references**:
  - `logic_flows.md#3.5`, `docs/progress.md#task-14`, `docs/ui-audit.md#0.1/#0.3`.

## Section – Task 15 Interactive / Live Addon Alignment

- **Scope**: Validate the redesigned Live hub, webinar/networking/podcast/interview surfaces (index/show/waiting-room/live/recordings), and their integration with Utilities quick tools, advertisement slots, and feed recommendations per AGENTS Task 15.
- **Checked areas**:
  - Live hub hero metrics/sections/ad slot, Utilities quick tools context, and route parity (web + Flutter).
  - Webinars: index filters, show (registration + recordings), waiting room countdown state, live shell (host tools + chat), recordings catalogue/player.
  - Networking: index/detail/register, waiting room timer, live rotation shell (notes + roster).
  - Podcasts: index, series detail, episode playback, live recording shell guest controls.
  - Interviews: index/detail (slots/scoring), candidate dashboard/show, waiting rooms, interviewer panel, candidate live shell.
  - Feed recommendation lane now sourcing `LiveEventsExperienceService` data instead of legacy posts.
- **Findings**:
  - Manual smoke passed for the flows above; countdowns enable CTAs as sessions go live, and shared components render consistently across event types.
  - `npm run build` now succeeds after the Live CSS/JS changes (see Task 15 snapshot). No open defects filed.
- **QA references**:
- `logic_flows.md#3.3`, `docs/progress.md#snapshot-–-2025-11-30-–-task-15-interactive--live-addon-alignment`, `docs/ui-audit.md#interactive--live`.

## Section – Task 20 Cross-Addon Roles, Permissions & Analytics

- **Scope**: Validate the shared permission matrix/gates, navigation filtering, and analytics taxonomy added for admin/addon personas (Ads, Talent & AI, Utilities, Jobs/Freelance dashboards).
- **Checks**:
  - Navigation API returns only role-authorised groups; unauthorised permission slugs are filtered before JSON serialization.
  - Freelance dashboard access blocked without profile + emits analytics payload when opened; role switch + favourites toggles emit namespaced events with actor/profile metadata.
  - Analytics listener accepts both Jobs and host `AnalyticsEvent` payloads and forwards to `ProNetwork\Services\AnalyticsService` queue.
- **Findings**: No new defects logged; telemetry payloads validated for required keys but automated suites remain pending.
- **QA references**:
  - `logic_flows.md#1.0`, `docs/progress.md#snapshot-–-2025-11-30-–-task-20-cross-addon-roles,-permissions--analytics`.

