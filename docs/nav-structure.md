# Gigvora Navigation Structure

This document captures the canonical navigation model that must match across the web app, responsive drawer, and Flutter shell. All menus are sourced from `config/navigation.php` and built via `App\Support\Navigation\NavigationBuilder`.

- Permissions are enforced via the shared matrix in `config/permission_matrix.php`; `NavigationBuilder` filters Ads/Talent & AI/Admin/Moderation entries when the caller lacks the mapped permission slug and the API controller (`NavigationController`) emits `analytics.navigation.rendered` to track which sections render for each persona.

## 1. Primary Navigation (Top Header & Flutter Tabs)

| Order | Label     | Route/Screen                    | Roles      | Notes                                                |
|-------|-----------|----------------------------------|------------|------------------------------------------------------|
| 1     | Home      | `dashboard`                      | all        | Feed landing (web header + tab 1 on mobile).         |
| 2     | Jobs      | `jobs.index`                     | member     | Candidate dashboard; recruiters get submenus.        |
| 3     | Freelance | `freelance.dashboard`            | member     | Gigs/projects workspace; role-aware subnav.          |
| 4     | Live      | `wnip.webinars.index`            | member     | Webinars/Networking/Podcasts/Interviews.             |
| 5     | Groups    | `groups.index`                   | all        | Community groups.                                    |
| 6     | Pages     | `pages.index`                    | all        | Company/brand pages.                                 |
| 7     | Messages  | `messages.index`                 | all        | Unified inbox / chat tabs.                           |

Flutter Tab mapping:
- `GigvoraTab.feed` → Home
- `GigvoraTab.jobs` → Jobs
- `GigvoraTab.freelance` → Freelance
- `GigvoraTab.live` → Live
- `GigvoraTab.profile` → Profile/More (houses Groups, Pages, Settings, etc.)

> **Desktop presentation**: The header now renders the primary modules as icon-first pills (`.gv-nav-icon` / `.gv-header-icon`) so Jobs, Freelance, Live, Groups, Pages, Messages, and future modules remain visible without crowding. Addon groups (Ads, Talent & AI, Live & Events) surface via adjacent icon buttons that open tokenized dropdowns.

### 1.1 Persona icon row & alert cadence (Task 9)

- The legacy text list has been replaced by persona-aware icon rows inside `resources/views/frontend/header.blade.php`. `App\Support\Persona\PersonaResolver` inspects Utilities account-type assignments (`pro_network_account_types`) and emits one of three icon sets (member, professional, hybrid) as required by AGENTS Task 9 and `logic_flows.md#1.2-live-feed`.
- Icon badges now reference `utilities_calendar_events` so interviews, events, gigs, and sessions can show 7d/3d/24h/6h/1h reminders inline. Hover/focus acknowledges alerts via `POST /utilities/alerts/header` handled by `UtilitiesExperienceController@acknowledgeHeaderAlert`.
- Header search was widened (double width) and styled via `.gv-header-search__form` to keep long feed/search queries readable.

## 2. Secondary Utilities (Header Icons + Drawer Shortcuts)

| Label         | Route             | Icon      | Flutter entry                               |
|---------------|-------------------|-----------|---------------------------------------------|
| Notifications | `notifications`   | Bell      | App bar bell + notifications screen.        |
| Saved         | `saved.index`     | Bookmark  | “Saved” section (Jobs/Gigs/Posts).          |
| Schedule      | `calendar.index`  | Calendar  | Calendar/My Schedule (interviews + events). |

### 2.1 Profile dropdown + Gigvora Verify (Task 9)

- Avatar hover/click reveals `.gv-profile-dropdown`, which now hosts profile link, **Gigvora Verify** entry, theme toggle, settings, admin panel, payment settings, change password, and logout.
- Gigvora Verify status is surfaced via `.status-pill` using `App\Models\Badge::isActive()` plus the new `review_status` metadata. The dropdown replaces the old sidebar badge CTA to satisfy Task 9’s “collapse nav” requirement and is referenced in `logic_flows.md#1.5-profile--journey`.
- Purchasing verification now routes through `BadgeController@payment_configuration`, which consults `GigvoraVerifyService` (followers/connects/likes/account age) before queuing checkout.

## 3. Addon Dropdown Groups (Header + Drawer Sections)

### Ads Manager
- Overview (`advertisement.dashboard`)
- Campaigns (`advertisement.campaigns.index`)
- Reports (`advertisement.reports.index`) – optional
Visibility: feature flag `advertisement.enabled` + `can('manage_advertisement')`.

### Talent & AI
- Headhunters (`addons.talent_ai.headhunters.dashboard`)
- Experience Launchpad (`addons.talent_ai.launchpad.programmes.index`)
- AI Workspace (`addons.talent_ai.ai_workspace.index`)
- Volunteering (`addons.talent_ai.volunteering.opportunities.index`)
- Talent & AI Admin (`addons.talent_ai.admin.config`) – `manage_talent_ai`

### Live & Events
- Live Hub (`liveCenter.hub`) – curated entry point for webinars, networking, replays.
- Webinars (`wnip.webinars.index`)
- Networking (`wnip.networking.index`)
- Podcasts (`wnip.podcasts.index`)
- Interviews (`wnip.interviews.index`) – surfaces also under Jobs > Interviews.
- Live hub + addon routes share the same tokenized shell (`wnip::layouts.live`) and data from `App\Services\LiveEventsExperienceService`, so hero metrics, ad slots, and Utilities quick tools stay in sync with the Live navigation entries on both web and Flutter clients.

### Utilities
- Utilities Hub (`utilities.hub`)
- My Network (`utilities.network`) – `pro_network/my-network` bridge; gated by `connections_graph`.
- Professional Profile & Company upgrades (`utilities.professional`, `utilities.company`) – hooks into `pro_network/profile/professional`.
- Stories & Post Enhancements (`utilities.stories.create`, `utilities.posts.*`) – one-tap access to story creator plus poll/thread/celebrate composers.
- Hashtag Explorer (`utilities.hashtags`) – deep links to the upgraded hashtag view under `pro_network`.

## 4. Profile Tabs (Web + Mobile Profile Rail)

| Tab key          | Label            | Route                | Logic flow reference                     |
|------------------|------------------|----------------------|------------------------------------------|
| `timeline`       | Timeline         | `profile`            | `logic_flows.md#1.5-profile--journey`    |
| `photos_reels`   | Photos & Reels   | `profile.photos`     | `logic_flows.md#1.3-posting--media`      |
| `videos`         | Videos           | `profile.videos`     | `logic_flows.md#1.3-posting--media`      |
| `saved`          | Saved            | `profile.savePostList` | `logic_flows.md#1.5-profile--journey` |
| `checkins`       | Check-ins        | `profile.checkins_list` | `logic_flows.md#1.5-profile--journey` |

These entries power the profile hero tabs on web (header rail) and should also be mirrored in Flutter’s profile/“More” surfaces.

## 5. Settings & Admin

Profile dropdown entries (web) and More menu (mobile):
- Account Settings (`all_settings.view`)
- Privacy & Notifications (`notifications.index` + addon-specific toggles)
- Admin dashboard (`admin.dashboard`) – `access_admin_panel`
- System Settings (`settings.system`) – `manage_system_settings`
- Logout

## 6. Responsive Drawer Flow

- Primary links (Home, Jobs, Freelance, Live, Groups, Pages, Messages)
- Addon dropdowns (Ads, Talent & AI, Live & Events)
- Secondary utilities (Notifications, Saved, Schedule)
- Profile/account summary + settings/admin/Logout

### 6.1 Left sidebar simplification & sticky rails (Task 9)

- The desktop left rail now contains only Feed, Memories, Blog (plus optional Jobs/Fundraiser/Paid Content entries). The Jobs link now resolves to `jobs.index` and is gated by `config('jobs.features.enabled')`, so seeker/employer states stay aligned with the central nav config. Below the static list we render two contextual sections:
  - **My groups** – latest six accepted memberships pulled via `Group_member::with('getGroup')`.
  - **My pages** – owned pages merged with likes (`Page` + `Page_like`), deduped and capped at six.
- `.gv-shell-grid` has been updated to use a 220 px sidebar (previously 260 px) and applies `position: sticky` to both rails so they “follow” the feed per AGENTS Task 9 and `logic_flows.md#3-addons` behaviour notes.
- Mobile drawer mirrors the same sections underneath the primary nav.

## 7. Flutter Navigation Alignment

- Bottom tabs (from `config/navigation.php` ➜ `mobile.tabs`):
  - Feed (`dashboard`)
  - Jobs (`jobs.index`)
  - Freelance (`freelance.dashboard`)
  - Live (`wnip.webinars.index`)
  - Profile (`profile`)
- Drawer sections (from `mobile.drawer`) mirror Communities (Groups, Pages), Workflows (Messages, Calendar), Utilities (Saved, Notifications), and Admin & Settings.
- Use the centralized API export (`GET /api/navigation`, auth via Sanctum) to hydrate Flutter menus so the config stays in sync with web.
- **Mobile drawer refresh**: The responsive menu uses the same icon buttons, addon chip lists, and utilities cluster defined in `resources/css/gigvora/tokens.css` (`.gv-mobile-nav-section`, `.gv-mobile-chip`) so every module (Jobs, Freelance, Ads, Talent & AI, Live/Interactive, Utilities, Admin) is reachable within two taps on phones.
- Flutter helper: `Gigvora Flutter Mobile App/App/lib/gigvora_navigation.dart` ships `GigvoraNavigationClient` and icon helpers so the mobile shell can fetch `/api/navigation`, render icon-first tabs (including Utilities), and build drawer sections identical to the Laravel IA.

## 8. Live Feed & Contextual Menus

- Feed quick filters: Jobs, Gigs, Events, Stories (toggle by feature flag).
- Job detail + Application screens include “Interviews” entry linking to `calendar.index`.
- Freelance dashboards expose Jobs & Utilities shortcuts (saved items, notifications).

## 9. Role Visibility Summary

| Role             | Special Menus                           |
|------------------|------------------------------------------|
| Job Seeker       | Jobs (candidate view), Saved Jobs.       |
| Recruiter/Company| Jobs (posting/pipelines), Ads Manager.   |
| Freelancer       | Freelance workspace, Saved gigs.         |
| Client           | Freelance client tabs.                   |
| Event Host       | Live dropdown (host dashboards).         |
| Platform Admin   | Admin dashboard, system settings.        |

## 10. Implementation Notes & Next Steps

- **Single source of truth**: `config/navigation.php` now contains sections for primary, addon groups, secondary utilities, admin links, profile tabs, settings, and mobile tabs/drawer. `App\Support\Navigation\NavigationBuilder` filters these with feature flags and permissions; persona grouping happens downstream in the header view.
- **API consumption**: `GET /api/navigation` (auth:sanctum) exposes the filtered map for mobile/WebView clients. Response includes `primary`, `groups`, `secondary`, `admin`, `profile_tabs`, `settings`, and `mobile`.
- **Task 9 add-ons**: Persona resolver + header icon row, Gigvora Verify dropdown entry, header alert acknowledgement endpoint, widened search bar, simplified left nav + contextual collections, and sticky rails.
- **Pending**: Add analytics instrumentation for nav/icon usage once the new reactivity layer is in place.

