# Gigvora UI Audit

Last updated: 2025-11-30 (Task 14 – Talent & AI / Headhunter / Launchpad)

This document captures the current state of the Gigvora (Sociopro) UI across the host Laravel app, all Laravel addons, and the Flutter mobile shell. It highlights the design system gaps and proposes a pathway toward a unified, token-driven reskin.

## 0. Project Scan Overview

### 0.1 Host Web App (Sociopro → Gigvora)
- **Layouts**: `resources/views/layouts/app.blade.php` (Tailwind + Alpine shell), legacy `layouts/master.blade.php`, and addon-specific wrappers (e.g., `freelance::layouts.freelance`, `advertisement::layouts.app`). Navigation lives in `resources/views/layouts/navigation.blade.php`.
- **Components**: Jetstream Blade components (`x-nav-link`, `x-dropdown`) mixed with manual Bootstrap markup. Utility classes from Tailwind coexist with raw Bootstrap classes (`row`, `card`, `col-lg-4`).
- **Assets**: Tailwind via `resources/css/app.css`; extra CSS/JS compiled per addon in `webpack.mix.js`. No shared SCSS tokens or base partial.
- **Profile / Stories / Media**: `frontend/profile/index.blade.php`, `profile_info.blade.php`, and `profile/media_hub.blade.php` rely on `gv-profile-*` / `gv-media-*` tokens for hero quick actions, right-rail utilities, Photos & Reels & Videos grids, Media Hub rails (reels, long videos, live/webinar/podcast cards), and inline ads; story rail/viewer (`resources/views/frontend/story/*.blade.php`) expose utilities quick tools plus toolbar buttons. Task 4 layered the media studio manifest (filters/overlays/stickers/GIFs, soundtrack selector, 4K→480p resolution ladder) onto story uploads via `StoryController@create_story` and `media_files.processing_manifest`, so both feed cards and the story viewer now render the edited visuals.
- **Feed composer & Live engagement (Task 4)**: `frontend/main_content/create_post_modal.blade.php` embeds `composer_studio.blade.php`, `MediaStudioService`, and `resources/js/feed/media-studio.js` to unlock story/reel/longform/live editing (filters, overlays, emojis/stickers/GIFs, soundtrack, scheduling, audience presets). Feed cards consume the manifest in `media_type_post_view.blade.php` to apply CSS filters + overlay layers, while `live_streaming_type_post_view.blade.php` now renders donation goals, viewer stats, and supporter leaderboards powered by `LiveEngagementService`. The live player (`frontend/live_streaming/index.blade.php`) adds a donation/reaction/question sidebar tied to `/live-engagement/*` endpoints plus Utilities CTA links defined in `posts.live_config`.
- **Talent & AI intelligence (Task 14)**: `frontend/main_content/talent_ai_summary.blade.php` introduces a `gv-talent-ai` card on the feed hub (headline stats + Launchpad/Volunteering/AI cards) and extends recommendation lanes + profile timeline/sidebar with Talent & AI data sourced from `TalentAiInsightsService`. Utilities quick tools gained a `talent_ai` context so the addon routes expose headhunter/launchpad/AI workspace/volunteering CTAs without bespoke markup.
- **Pages & Groups (Task 7)**: `frontend/pages/page-timeline.blade.php` and `frontend/groups/discuss.blade.php` now consume `CommunitySurfaceService` panels inside the Gigvora shell, standardizing tabs (About/Feed/Jobs/Events/Analytics), stats cards, and sidebar analytics while embedding `components.utilities.quick-tools` contexts (`page`, `group`) for reminder/ads/moderation/actions.
- **Marketplace (Task 7)**: `frontend/marketplace/products.blade.php` + `user_products.blade.php` moved into the Gigvora shell with tokenized filters, analytics snippets, advertisement slots, and contextual Utilities quick tools (`marketplace`, `marketplace_manager`). Product cards reuse the new `.gv-marketplace-card*` styles for consistent spacing/typography.
- **Jobs (Task 8)**: `resources/views/vendor/jobs/index|saved|show|apply.blade.php` plus employer portal screens (dashboard, jobs list, wizard, ATS board, interviews, billing, company profile) are refactored to the Gigvora layout using `.gv-card`, `.gv-btn`, `.gv-chip`, and contextual quick tools. `Jobs\Http\Controllers\EmployerPortalController` aligns middleware/role checks, while `/jobs` routes now hydrate analytics and Utilities links from `config/jobs.php`. Legacy `ApiController` job APIs were removed so `/api/jobs` is owned solely by the addon, and composer/left-nav/profile quick actions now gate off `config('jobs.features.enabled')` + employer roles, with seeker pills linking to `jobs.index`.
- **Utilities interview surfaces (Task 9a/Task 11)**: Feed/profile now stack both the timeline card (`interview_timeline.blade.php`) and the new reminders card (`interview_reminders.blade.php`) powered by `InterviewTimelineService` + `InterviewReminderService`, mirroring the Utilities notifications sidebar widgets (`gv-reminder-row`, `gv-stat-pill`). The Utilities Saved hub surfaces saved Jobs bookmarks + recruiter candidate notes, while the calendar/notifications views render ATS status digests and reminder CTAs sourced from `utilities_calendar_events` (including new `jobs_application_status` entries). Notification rows gained handling for `job_application_status*` payloads with contextual icons/links, ensuring candidates and employers see the same status copy across feed/profile/Utilities/mobile.
- **Navigation simplification & Gigvora Verify (Task 9b)**: `frontend/header.blade.php` now renders persona-aware icon rows (`.gv-header-icon`, `.gv-header-search__form`, `.gv-header-icon__badge`) so Feed/Jobs/Freelance/Live/Marketplace/Videos/Shorts journeys remain visible without duplication. Alerts on each icon originate from Utilities calendar events and can be acknowledged inline via the new `/utilities/alerts/header` endpoint. The profile dropdown was rebuilt (`.gv-profile-dropdown`, `.status-pill`) to host profile link, Gigvora Verify, theme toggle, settings, and logout; verification routing now hinges on `GigvoraVerifyService` and the new `config/gigvora_verify.php`. The left sidebar (`frontend/left_navigation.blade.php`) has been trimmed to Feed/Memories/Blog (plus gated addons) and now surfaces “My groups”/“My pages” collections with sticky rails, while share modal CTA copy has been updated to “My Feed.” Layout tokens (`resources/css/gigvora/tokens.css`) were expanded for header, dropdown, icon row, and sticky rails, satisfying AGENTS Task 9 + `logic_flows.md#1.2`/`#1.5`.
- **Notifications & Inbox (Task 8)**: The Utilities notifications center (`resources/views/utilities/notifications.blade.php`) now inherits the Gigvora shell with `gv-stat-tile`, `gv-notification-row`, `gv-notification-filter`, and the new JS module (`resources/js/utilities/notifications.js`) to handle filtering, sticky scrollers, and AJAX dismissals routed through `UtilitiesNotificationActionController`. Inbox/chat screens (`resources/views/frontend/chat/*.blade.php`) dropped the legacy standalone layout in favour of `layouts.app`, adopting `.gv-chat-*` tokens, composer panels (emoji, stickers, GIF search via `/api/utilities/composer/*`), and token-driven chips/badges. Shared composer tooling lives in `App\Services\UtilitiesComposerAssetsService` + `resources/js/utilities/composer.js`, keeping reactions/emoji/sticker metadata consistent with `logic_flows.md#1.8-notifications--utilities-surfaces` and `logic_flows.md#1.11-messaging--inbox`. Flutter parity arrives through `Gigvora Flutter Mobile App/App/lib/inbox_shell.dart`, exposing `GigvoraNotificationsPanel` + `GigvoraInboxComposer` widgets that mirror the same reaction/emoji toolbars.
- **Freelance shell (Task 10)**: Every Freelance Blade view (`vendor/freelance/**`) now extends `freelance::layouts.freelance`, which itself inherits the Gigvora shell (header hero, sidebar nav, Utilities quick tools). Navigation (`components/navigation/freelance-menu`), dashboards (freelancer/client), seller profile, gigs/projects/proposals/contracts/orders/disputes/escrow/admin screens now rely on `.gv-card`, `.gv-btn`, `.gv-pill`, `.gv-section`, and `.gv-sidebar` tokens plus the new `resources/css/freelance/app.css`. JS modules were moved into `resources/js/freelance/app.js` (importing the addon scripts) so dashboards, wizards, and filters initialize consistently. Task 12 extended this work by adding `.gv-freelance-card` components to the global search view (projects/gigs/talent lanes powered by `FreelanceSearchService`) and by rebuilding the freelancer/client dashboards with workspace KPIs, contract/escrow cards, recommendation rails, and ad slots fed by `FreelanceWorkspaceService`.

### 0.2 Flutter Mobile Shell
- **Structure**: Main Flutter app under `Gigvora Flutter Mobile App/App`, with addon packages imported via `pubspec.yaml` and wired through `addons_integration.dart`.
- **Styling**: Relies on Material defaults. Each addon exports its own theme constants; typography, color palette, and elevations differ from the web brand. Drawer/tab navigation naming still references Sociopro in places.
- **Parity helpers**: Feed consumes `GigvoraFeedShell` (now with optional `mediaStudio` and `interviewTimeline` + `interviewReminders` slots for story/reel/live composer + Utilities parity); profile/media/stories add `GigvoraProfileShell`, `GigvoraMediaSwipeShell`, and `GigvoraStoryRail` so mobile inherits the same hero, quick action, swipeable rails, story tooling, and dual interview widgets. Slots map directly to the Utilities reminder API payload so bell alerts, feed cards, and Flutter sheets share the same cadence.

### 0.3 Addon Inventory (Laravel)
| Addon | Layout & Styling | Notes |
| --- | --- | --- |
| Advertisement | `advertisement::layouts.app` now emits `.gv-ad-shell`, `.gv-card`, `.gv-table`, `.gv-input`, `.gv-btn` helpers powered by `resources/css/advertisement/addon.css` (token-backed). | Dashboards, campaigns, wizard, keyword planner, forecast, billing now reuse Gigvora tokens + ad-specific components (hero banner, metrics grid, placement cards). |
| Talent & AI | Tokenized Gigvora shell (`gv-card`, `gv-btn`, updated `resources/css/addons/talent_ai/talent_ai.css` for headhunters/launchpad/volunteering/AI workspace) plus feed/profile summary card (`gv-talent-ai`). | Remaining gap: ensure OpenAI/BYOK env keys are set before running the AI workspace; legacy Bootstrap remnants have been removed. |
| Freelance | Tokenised Gigvora shell (`freelance::layouts.freelance`), sidebar menu component, and shared CSS in `resources/css/freelance/app.css`; JS modules bundled via `resources/js/freelance/app.js`. | Typography/spacing now align with tokens; remaining gap is payment gateway UI (still uses placeholder copy while backend wiring finishes). |
| Interactive / Live | Live hub, webinars, networking, podcasts, and interviews now use `wnip::layouts.live` + `.gv-card`/`.gv-pill` tokens, quick tools, and shared components (`event_card`, `waiting_room_header`, `live_chat_panel`, `notes_sidebar`, `calendar_widget`). Waiting rooms + live shells share timers, Utilities CTAs, and advertisement slots. | Monitor for additional admin view parity + future animations, but primary surfaces are tokenized. |
| Jobs (Task 8) | Tokenized Gigvora shell (`resources/views/vendor/jobs/*.blade.php`) + `.gv-card`/`.gv-btn` patterns; employer portal moves to `EmployerPortalController`. | Job search/detail/apply, saved jobs, and employer dashboards/ATS/billing/interviews now share quick tools + nav config; Bootstrap remnants removed. |
| Utilities (Task 5) | Notifications, saved list, calendar, and quick tools now share Gigvora shells + `components.utilities.quick-tools` fed by `App\Services\UtilitiesQuickToolsService`. | Floating bubble + inline panels consume `/api/utilities/quick-tools`; contexts wired on feed, profile, Jobs, Freelance, Live, admin, and Flutter (`GigvoraQuickToolsPanel`). |

### 0.4 Addon Inventory (Flutter)
- **Advertisement add-on**: Ads home screen now mirrors the Gigvora hero + metrics grid (gradient header, metric cards, refreshed top campaigns list) using the shared theme exposed via `GigvoraThemeData`; keyword planner and forecast routes stay reachable via the action buttons.
- **Talent & AI add-on**: Individual `ChangeNotifier` providers per module; UI still uses Material defaults but the package has been renamed to `talent_ai_flutter_addon` so the host app (`addons_integration.dart`) can import the routes/menu without workaround.
- **Freelance add-on**: Rich set of screens (gigs, projects, contracts) with bespoke theme; buttons differ from Gigvora standard.
- **Interactive add-on**: Live events screens now inherit Gigvora tokens; podcast catalogue/series/episode players surface follower counts, AJAX follow buttons, playback progress (web `podcastPlayer.js` + Flutter `PodcastEpisodePlayerScreen`), and host live shells use `podcastLive.js` timers. Error/loading states are present on Flutter catalogue/series/episode screens.
- **Jobs add-on (planned)**: Needs full theme alignment and shared navigation gating.

## 1. Existing Design System Inventory

| Dimension | Current State |
| --- | --- |
| **Colours** | Default Tailwind palette (`indigo-500`, `gray-100`), Bootstrap `primary`/`secondary`, plus addon-specific hex values. No documented palette. |
| **Typography** | Mix of Tailwind defaults (Inter/Roboto) and ThemeKata fonts inside Freelance (Nunito / Poppins). Heading scales differ per module. |
| **Spacing** | Tailwind spacing units on core app; Freelance uses 10px/20px increments; Ads/Talent use Bootstrap spacing utilities. |
| **Border radius** | Tailwind `rounded-md` vs Bootstrap `rounded` vs hard-coded `12px`. |
| **Components** | Buttons, cards, chips, tabs all vary per addon; some rely on Livewire components, others pure Blade. |
| **Breakpoints** | Tailwind breakpoints on host (`sm`, `md`, `lg`, `xl`). Addons use Bootstrap grid (`col-lg-4`). Flutter uses default Material breakpoints. |

## 2. User Types & Key Flows

| Persona | Primary Screens (Web) | Primary Screens (Mobile) |
| --- | --- | --- |
| Member / Creator | Feed, Profiles, Groups, Pages, Live, Stories | Feed tab, Profile tab |
| Job Seeker | Jobs listing/detail, Saved Jobs, My Applications, Interviews | Jobs tab (search, saved, applications) |
| Recruiter / Company Admin | Job Posts, Applicants, Pipelines, Interview schedule | Jobs tab (recruiter routes) |
| Freelancer | Freelance dashboard, Gigs/Projects, Contracts, Disputes | Freelance tab (gigs, proposals, contracts) |
| Client (Freelance) | Project creation, Invitations, Contracts | Freelance tab (client routes) |
| Event Host | Live dashboard, Webinars, Networking, Podcasts | Live tab (host tools) |
| Platform Admin | Ads, Talent & AI admin, Utilities dashboards | Admin-only sections |

## 3. Problem Areas

1. **Visual Fragmentation**: Each addon ships its own CSS/JS bundle with bespoke palette, leading to clashing backgrounds, buttons, and typography.
2. **Layout Drift**: Multiple layout files (Jetstream, ThemeKata, custom) create inconsistent spacing, breadcrumb styles, and page headers.
3. **Navigation Duplication**: Menus are redefined per addon; responsive nav and desktop nav are out of sync and perform redundant feature checks.
4. **Accessibility Gaps**: Inconsistent focus states, missing ARIA labels on icon-only buttons (save, bookmark, live controls), low contrast badges.
5. **Flutter Disconnect**: Mobile UI retains Material defaults and Sociopro naming; tabs and colors do not match web, and some addon menus were trimmed without clear alternatives.
6. **Feed/Search Integration**: Jobs, Freelance, Live, and Ads appear sporadically (or not at all) in the core feed/search, reducing discoverability.
7. **CSS Debt**: Inline styles and repeated selectors (`.tk-serviesbann`, `.gigvora-ad`) without shared tokens make reskinning labor-intensive.

## 4. Recommended Token-Based Upgrade Path

1. **Create Token Layer** (`resources/css/gigvora/tokens.css`):
   - Define `--gv-color-*` custom properties (primary, secondary, surface, border, success, warning, danger).
   - Standardize typography scale (H1–H6, body, caption) and spacing variables (`--gv-space-1` etc.).
   - Provide utility classes `.gv-card`, `.gv-pill`, `.gv-chip`, `.gv-btn-primary`, `.gv-surface`, `.gv-elevated`, `.gv-section`, `.gv-heading`, `.gv-body`, `.gv-link`.

2. **Adopt a Single Layout**:
   - Extract `layouts/gigvora.blade.php` that wraps navigation, page header, and stacks (CSS/JS).
   - Migrate host + addons to extend this layout (replace `freelance::layouts.freelance`, `advertisement::layouts.app`, etc.).

3. **Scoped Reskin Pass**:
   - Convert addon CSS bundles to import `_tokens.scss` and replace hard-coded colors with tokens.
   - Remove inline styles by mapping to `.gv-*` utilities.
   - Normalize cards/lists using `.gv-card` wrappers with consistent padding/hover states.

4. **Navigation Systemization**:
   - Move menu definitions into `config/navigation.php` (or dedicated builder class) with role metadata.
   - Render menus via a single Blade component for header + responsive nav; share config with Flutter via JSON export or API (`/api/navigation`).
   - Use `.gv-nav-icon`, `.gv-icon-button`, and `.gv-mobile-nav-section` helpers to keep module access discoverable without crowding (icon-first primary bar, addon icon triggers, utilities cluster, fully rebuilt mobile sheet).
   - Surface the Utilities addon via `resources/views/utilities/hub.blade.php`, which houses cards for My Network, Professional Profiles, Escrow guidance, Stories/Post Enhancements, and Reactions/Hashtags with direct CTAs to the `pro-network` routes.

5. **Flutter Theming**:
   - Introduce `GigvoraThemeData` with shared `ColorScheme`, typography, and component themes mirroring web tokens.
   - Update addon integrations to use the shared theme rather than local constants.

6. **Profile & Feed Restructure**:
   - Rename media tabs to **Photos & Reels** wherever they surface (profile tabs, search filters, Flutter tabs) and align the UI to the new Gigvora shell.
   - Rebuild the profile page into modular sections that can surface addon data: Jobs, Freelance projects/gigs, Companies, Live feed posts, Podcasts/Webinars, Interviews, Utilities widgets, and a unified calendar. Right rail now uses `gv-profile-quick-links` + utilities pills; Flutter mirrors via `GigvoraProfileShell`.
   - Extend the live feed composer + recommendation lanes (`gv-feed-hub`, `gv-feed-recos`) to handle new post types (jobs, gigs, live sessions, utilities alerts, sponsored) produced by addons, with clear CTAs surfaced via `App\Support\Feed\FeedCardPresenter` and mirrored in Flutter through `GigvoraFeedShell`.

7. **Utilities Surfaces (Task 5)**:
   - Expose quick tools via `components.utilities.quick-tools` on feed, profile, Jobs (index + detail), Freelance shell, Live hub, and admin dashboard. Each instance queries `App\Services\UtilitiesQuickToolsService` so CTAs stay context-aware without duplicating arrays inside Blade.
   - Floating Utilities bubble (`components.utilities.chat-bubble` + `resources/js/utilities/bubble.js`) calls `/api/utilities/quick-tools` to hydrate the same actions while also showing chat conversations/requests.
   - Flutter mirrors the feature through `GigvoraQuickToolsClient` + `GigvoraQuickToolsPanel` (see `App/lib/utilities_quick_tools.dart`), enabling tabs/drawers to render identical action chips driven by the REST payload.
8. **Documentation & Governance**:
   - Document tokens and usage rules in `/docs/design-system.md`.
   - Enforce linting (Stylelint / Dart analyzer) tied to token usage where possible.

Following this path enables a progressive reskin without freezing feature work: we can first define tokens, then iteratively migrate layouts and components module-by-module, while ensuring both web and Flutter share the same brand foundation and that new profile/feed experiences reflect the expanded addon ecosystem.

