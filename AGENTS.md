# Gigvora Agent Task Brief

## Chronological Execution Plan
Each numbered objective must be completed in order. Treat `logic_flows.md` as the canonical definition of scope: every task below references specific anchors that define inputs, states, and acceptance behavior. A task is complete only when implementation, documentation, and QA evidence align with those flows and progress is logged in `docs/progress.md`, defects in `docs/qa-bugs.md`, and styling references in `docs/ui-audit.md`.

1. ✅ **System Audit & Governance Setup**
   - **Goal**: Establish a single source of truth for flows, UI, navigation, and roles before any redesign or integration work.
   - **Scope**:
     - Review the entire web + Flutter + addons surface area against `logic_flows.md` and fill any missing flows/sub-flows.
     - Update `docs/ui-audit.md` with all screens, components, inconsistencies, and current token usage.
     - Refresh `docs/nav-structure.md` with the actual IA (header, sidebar, tabs, drawers) and alignment issues.
     - Baseline analytics and feature flags, listing what is currently tracked vs. what should be tracked. **Status**: Completed 2025-11-29 – see `docs/analytics-feature-flags.md`, `docs/progress.md#snapshot-–-2025-11-29-–-task-1-system-audit--governance-setup`, and `docs/qa-bugs.md#section-–-analytics--feature-flags-agents-task-1-–-baseline`.
   - **UI & UX**: No major visual changes yet; focus on cataloguing gaps in layout, spacing, orientation, fonts, and interactive behavior that later tasks will fix.
   - **Integration**: Map where addons already hook into feed, profile, search, settings, and where they are missing.
   - **Security & Privacy**: Note any obvious security smells (license checks, plain-text storage, missing GDPR hooks) to address in later tasks.
   - **Completion Criteria**: All three docs updated with explicit references to `logic_flows.md` anchors, TODO tracker populated, and at least one snapshot entry in `docs/progress.md` summarizing the current state.

2. ✅ **Design System & Tokens**
   - **Goal**: Create a coherent, token-driven design system and apply it consistently as the foundation for all subsequent UI work.
   - **Scope**:
     - Finalize all Gigvora tokens in `resources/css/gigvora/tokens.css` (colors, typography, spacing, radius, shadows, transitions, focus rings) and ensure semantic names for surfaces and text.
     - Implement `GigvoraThemeData` in Flutter, mapping token values to `ColorScheme`, `TextTheme`, `ButtonTheme`, `ChipTheme`, etc.
     - Replace hard-coded fonts, colors, and sizes in core layouts/components with token-based classes (`.gv-*`) and ensure new text uses the typography scale.
   - **UI & UX**: Apply tokenized styles to base shells (layouts, cards, buttons, inputs, pills, chips) and ensure modern, high-end look (consistent padding, hierarchy, hover/focus states, responsive behavior).
   - **Integration**: Wire tokens into shared Blade components and Flutter widgets that are reused by addons, so all future flows inherit consistent styling.
   - **Security & Performance**: Validate no inline styles or untrusted style injection remain in critical surfaces; ensure CSS/JS bundles build cleanly without regressions.
   - **Completion Criteria**: Tokens documented and referenced in `docs/ui-audit.md`, all base UI uses tokens, Flutter theme visually matches web, and spot checks across main pages confirm consistent fonts, colors, and spacing.

3. ✅ **Navigation & IA Merge**
   - **Goal**: Replace fragmented menus with a single, role-aware navigation model shared by web and mobile.
   - **Scope**:
     - Implement a centralized nav config/builder that assembles main nav, sidebars, profile tabs, and settings menus from one data source.
     - Integrate legacy Sociopro options and all addon entries (Jobs, Freelance, Ads, Talent & AI, Interactive, Utilities) using consistent labels, icons, and routing.
     - Mirror this IA in Flutter (tabs, drawer, app bars) so Jobs/Interviews, Freelance, Live, Utilities sit in the same conceptual locations as web. **Status**: Completed 2025-11-29 – see `docs/nav-structure.md`, `docs/progress.md#snapshot-–-2025-11-29-–-task-3-navigation--ia-merge`, and `App/lib/gigvora_navigation.dart` for the shared mobile client.
   - **UI & UX**: Redesign nav elements using Gigvora tokens (button styles, hover/focus, active states), improve orientation and position (grouping, separators, headings), and ensure readability across breakpoints.
   - **Integration**: Ensure every nav item lands on a screen whose flow exists in `logic_flows.md`; add deep links for notifications, feed cards, and search results.
   - **Security**: Enforce role/permission checks and feature flags at nav-generation time so users never see inaccessible items.
   - **Completion Criteria**: Web + mobile navs share the same config, persona-specific menus validated (job seeker, freelancer, recruiter, admin, creator), `docs/nav-structure.md` updated with final IA, and no orphan nav entries remain.

4. ✅ **Live Feed & Composer Overhaul**
   - **Goal**: Turn the feed into a unified, Gigvora-branded hub 
   - **Scope**:
     - Redesign feed layout grid (wider main column, clear side rails) using tokenized cards and spacing; integrate recommendation sections and ad slots from `AdvertisementSurfaceService`.
     - Rebuild the composer (web + Flutter) with pills for text, media, jobs, gigs, live events, and utilities actions, all styled via tokens (buttons, icons, font sizes).
     - Normalize feed transformers so every post type (core posts, jobs, gigs, live sessions, utilities alerts, sponsored content) renders with consistent typography, CTAs, and metrics.
        - Ensure when uploading into the story it behaves like a TikTok experience with editing tools (cropping, add emojis, add effects, add overlay, add filters, add stock music, text overlay, text styling, stickers, GIFs), and keep the same tooling on web + Flutter with direct camera access on mobile. Honor the resolution ladder (4K → 1080p → 780p → 480p) wherever possible.
      - Reel posting process: editing tools (cropping, emojis, effects, overlays, filters, soundtrack, text overlays, stickers, GIFs) followed by caption/tag/location stage, respecting the resolution ladder on web + Flutter.
      - Long-form posting: scheduling (date/time), same editing toolkit as above, captions/tags/locations, and resolution ladder enforcement.
      - Live streaming: donations (goal + leaderboard), large donor callouts, feed chat/polls/questions, GIFs/stickers/emojis/reactions/sharing/replies, Utilities integrations (links to marketplace/projects/podcasts/posts/groups/pages/companies), viewer count + goals, 4K-first streaming fallback to 1080p/780p/480p.
   - **UI & UX**: Upgrade visual hierarchy (avatars, headings, metadata), hover/tap states, and empty/loading states; ensure great mobile ergonomics (thumb reach, card heights, line length).
   - **Integration**: Wire Jobs, Freelance, Interactive, Utilities, and Ads cards into the feed as described in `logic_flows.md#3-addons` and ensure clicking each card follows the correct sub-flow.
   - **Security & Safety**: Preserve or improve moderation hooks, ad labeling, and privacy for sensitive content; verify rate limits and CSRF protections still apply to composer actions.
   - **Completion Criteria**: Feed on web and mobile matches the new design system, all card types and composer modes are functional, ad frequency caps enforced, and UX validated via QA against all feed flows.

5. ✅ **Utilities Module Integration**
   - **Goal**: Make Utilities the shared fabric of notifications, bookmarks, calendar, reminders, and quick tools across Gigvora.
   - **Scope**:
     - Implement floating utilities bubble and context-aware quick tools on feed, profile, Jobs, Freelance, Interactive, admin, and key detail screens.
     - Centralize notifications center UI, saved/bookmarked lists, and calendar/reminders using tokenized components and consistent interactions.
   - **UI & UX**: Design utilities surfaces to be lightweight but powerful—clear icons, compact cards, accessible interactions on both web and mobile.
   - **Integration**: Hook Jobs, Freelance, Interactive, Ads, and core social modules into Utilities APIs for notifications, bookmarking, and scheduling as specified in `logic_flows.md#36-utilities-addon`.
   - **Security**: Ensure reminders/notifications/bookmarks are always scoped to the current user, rate-limited, and respect privacy; avoid leaking sensitive identifiers.
   - **Completion Criteria**: Utilities experiences are reachable from all major flows, behave consistently, and docs (`about.md/functions.md`) explain how other modules integrate.
   - **Status**: Completed 2025-11-29 – see `docs/progress.md#snapshot-–-2025-11-29-–-task-5-utilities-module-integration`, `docs/ui-audit.md#0-project-scan-overview`, and `docs/qa-bugs.md#section-–-task-5-utilities-module-integration`.

6. ✅ **Profile, Media & Stories Revamp**
   - **Goal**: Make the profile and stories surfaces the “Prime” view of a user’s jobs, freelance, live, utilities, and media graph with cohesive styling.
   - **Scope**:
     - Redesign profile hero, tabs, and right rail using Gigvora cards and typography (Photos & Reels revamp instagram style , Videos revamp instagram style, stories upload method like tiktok and instagram, live stream like instagrama and tiktok  Saved, Check-ins, Opportunities, Journey).
     - Enforce media categorization logic (short vs long video) and update all upload/query flows to respect it.
     - Rebuild story rail, story viewer, and live moments UI using tokens and integrate Utilities quick tools (polls, reminders, CTAs).
   - **UI & UX**: Improve avatar presentation, cover overlays, tab orientation, card alignment, and copy; ensure all texts use token fonts and responsive sizes across desktop/tablet/phone.
   - **Integration**: Surface Jobs, Freelance, Interactive, Ads, and Utilities signals within profile cards and stories, following the cross-addon journeys in `logic_flows.md`.
   - **Security & Privacy**: Respect privacy settings for locked profiles, story audiences, and sensitive content; guard profile actions with correct policies.
   - **Completion Criteria**: Profile and stories on web and Flutter look and behave consistently, media flows verified (Photos & Reels vs Videos), and insights/journey cards populated with live data.

7. ✅ **Pages, Companies, Groups, Marketplace Alignment**
   - **Goal**: Bring all community and business surfaces into the Gigvora shell and connect them deeply with Jobs, Freelance, Live, Utilities, and Ads.
   - **Scope**:
     - Reskin page/company and group views/admin panels with tokenized cards, badges, and tabs; standardize layouts for “About,” “Feed,” “Jobs,” “Events,” and “Analytics.”
     - Modernize marketplace listings and product cards, aligning typography, spacing, and CTAs with the new system.
     - Embed Utilities widgets (analytics snippets, reminders, quick tools) on company dashboards, group admin panes, and marketplace management screens.
   - **UI & UX**: Clarify roles (member vs admin vs moderator), ensure discoverability of creation/editing actions, and align fonts/orientation with other primary shells.
   - **Integration**: Ensure Jobs and Freelance postings from pages/groups match their respective flows and appear in search/feed; tie Ads placements into these surfaces where configured.
   - **Security**: Lock admin actions behind correct roles, prevent cross-tenant data exposure, and audit sensitive operations.
   - **Completion Criteria**: All page/group/marketplace templates extend the master layout, utilities widgets visible where specified in `logic_flows.md`, and personas validated in QA.
   - **Status**: Completed 2025-11-29 – see `docs/progress.md#snapshot-–-2025-11-29-–-task-7-pages--companies--groups--marketplace-alignment`, `logic_flows.md#1.6-pages--companies`/`#1.7-groups--communities`/`#1.12-marketplace--commerce`, and `docs/qa-bugs.md#section-–-task-7-pages-groups-marketplace`.

8. ✅ **Notifications & Inbox Cohesion**
   - **Goal**: Bring notifications, inbox, and chat flows up to Gigvora’s utilities standards so messaging feels responsive, organized, and extensible across web and Flutter.
   - **Scope**:
     - Refactor the notifications center per `logic_flows.md#36-utilities-addon` so alerts are tidy, grouped, and wrapped in a dedicated scroller with consistent card sizing, hover states, and dismissal affordances.
     - Re-layout the main inbox so chat bubbles align evenly away from container edges, spacing mirrors the token grid, and composer elements inherit the same styling across one-to-one, group, and addon-powered threads.
     - Embed Utilities reactions, emoji picker, GIF search, and sticker packs inside inbox + chat composers (web + Flutter), ensuring the state sync matches the utilities service contracts.
   - **UI & UX**: Apply Gigvora tokens to notification rows, unread counters, and chat bubbles; guarantee readable contrast, focus-visible states, and adaptive scroll behaviors for long histories.
   - **Integration**: Keep Utilities as the source of truth for activity metadata, update analytics events for notification interactions, and verify composer attachments follow the same pipelines used in feed posts.
   - **Security & Privacy**: Preserve rate limits, sanitize rich media payloads, and ensure read receipts + reactions obey thread membership policies.
   - **Completion Criteria**: QA passes for notification scrolling and chat alignment on desktop/mobile, utilities reactions available in inbox, and documentation updated in `docs/ui-audit.md` + `docs/progress.md`.
   - **Status**: Completed 2025-11-30 – see `docs/progress.md#snapshot-–-2025-11-30-–-task-8-notifications--inbox-cohesion`, `docs/ui-audit.md#0-project-scan-overview`, and `docs/qa-bugs.md#section-–-task-8-notifications--inbox-cohesion` for implementation + QA evidence.

9.  ✅**Navigation Simplification & Gigvora Verify Program**
   - **Goal**: Consolidate redundant profile/nav entry points, modernize header + sidebar ergonomics, and relaunch the badge journey as “Gigvora Verify” with clear eligibility and benefits.
   - **Scope**:
     - Collapse profile access into the avatar control: direct click navigates to profile, hover reveals dropdown; remove the duplicate profile button from the header and the left sidebar.
     - Relocate the badge action from the left sidebar into the avatar dropdown, rename it to “Gigvora Verify”/“Get Verified,” and move the dark/light mode toggle into the same dropdown.
     - Double the width of the header search input, rename Timeline → Feed, and remove Groups/Pages from the static left nav (discover them via search or recommendation rails).
     - Shift Marketplace, Events, Videos, and Shorts out of the left nav and into the top icon button row, while the sidebar now lists joined/owned groups and pages with avatars plus reduces its width by ~15%.
     - Make both left and right rails sticky-follow: they scroll independently until their content bounds, then track feed scroll to stay in view per `logic_flows.md#3-addons` behavior notes.
     - Overhaul the Gigvora Verify purchase flow with new eligibility (≥2,500 followers or 1,000 connects, ≥5,000 likes, account age ≥60 days), lock profile edits during review, collect £9.99/mo, and grant boosts (search ads slots, recommendation priority, feed uplift, higher ranking in Freelance bids/gigs).
     - Define top header icon sets: for members show Projects, Find a Gig, Applications, Interviews, Calendar, Events, and Session Bookings with hover-to-clear notifications; for professional personas show Projects search, Gig Orders, Job Listings, Events, Calendar, and Session Bookings with the same notification cadence (7d/3d/24h/6h/1h) tied to Utilities signals.
     - Audit persona segmentation (member vs professional vs hybrid) so permissions, icon sets, and contextual panels reflect the correct experience without over-complication.
   - **UI & UX**: Use tokenized circular icon buttons, refreshed sidebar icons, and consistent spacing so the header + rails feel premium and decluttered while keeping notification cues legible.
   - **Integration**: Wire new buttons to existing Jobs, Freelance, Interactive, and Utilities flows referenced in `logic_flows.md#31-#37`, ensuring analytics tags capture usage per persona.
   - **Security & Privacy**: Enforce eligibility checks server-side, freeze profile edits securely during verification, and guard paid badge workflows (billing + manual approval) against spoofing.
   - **Completion Criteria**: Header/sidebar/nav behave per the new IA on web + Flutter, Gigvora Verify flow documented with eligibility logic, and `docs/nav-structure.md` + `docs/progress.md` capture the IA + badge changes. **Status**: Completed 2025-11-30 – see `docs/progress.md#snapshot-–-2025-11-30-–-task-9-navigation-simplification--gigvora-verify-program`, `docs/nav-structure.md`, `docs/ui-audit.md#0-project-scan-overview`, and `logic_flows.md#1.2/#1.5` for flow updates.

10. ✅**Jobs Platform Alignment (Prompt 8A)**
   - **Goal**: Turn Jobs into a first-class, fully integrated ATS for both web and mobile.
   - **Scope**:
     - Backend: align models, services, and policies for job posting, applications, ATS pipeline, interview scheduling, offers, and reporting.
     - Web UI: redesign job search, job detail, application wizard (CV builder, cover letter, questions), recruiter dashboards, and pipelines using tokens and improved layouts.
     - Mobile: ensure candidate and recruiter views on Flutter mirror web flows with appropriate navigation and widgets.
   - **UI & UX**: Provide clean, guided flows for job seekers (resume/cover creation, status tracking) and recruiters (pipeline visualization, quick filters, ATS actions) with clear use of typography and spacing.
   - **Integration**: Connect Jobs with Utilities (notifications, calendar, bookmarks), Interactive (interviews), Profile (job history/preferences), and Search.
   - **Security**: Strictly enforce roles (job seeker, recruiter, company admin), protect application data, and ensure GDPR export/delete for job-related data.
   - **Completion Criteria**: All candidate/recruiter journeys in `logic_flows.md#31-jobs-addon` + `#53-jobs-mobile` run end-to-end, UI matches tokens, and docs reflect final behaviors.

11. ✅ **Utilities + Jobs Interview Synchronization**
    - **Goal**: Ensure every interview-related event is visible and actionable across Utilities, Jobs, and Interactive.
    - **Scope**:
      - Connect Jobs ATS status changes and Interactive interview events to Utilities notifications and calendar.
      - Implement interview reminders, digests, and saved interview-related items (like saved jobs or candidate notes).
    - **UI & UX**: Surface interview timelines and reminders in profile, feed sidebars, and mobile notifications with consistent styling and wording.
    - **Integration**: Confirm that every interview lifecycle step in `logic_flows.md#31-#33-#36-#37` has a matching Utilities artifact (reminder, notification, bookmark, calendar entry).
    - **Security**: Restrict interview data visibility to appropriate parties (candidate, recruiter, interviewer) and avoid leaking details in notifications.
    - **Completion Criteria**: Interview flows feel “joined up” across web/mobile; QA checklist confirms every step appears in Utilities correctly.

12. ✅**Freelance Platform Alignment (Prompt 6A)**
   - **Goal**: Make Freelance a robust parallel to Jobs, with polished gig and project flows across web/mobile.
   - **Scope**:
     - Backend: verify and align controllers/services for gigs, projects, bids, contracts, milestones, escrow, disputes, and reviews.
     - Web UI: redesign gig cards, project listings, proposal forms, dispute centers, and dashboards using tokens and consistent alignment.
     - Flutter: integrate freelance addon screens into main nav; style them with GigvoraThemeData and ensure full functional parity.
   - **UI & UX**: Improve clarity around gig packages vs projects, show progress and earnings, highlight CTA buttons, and ensure mobile flows feel native and efficient.
   - **Integration**: Connect Freelance with Jobs (convert offers/roles to contracts), Ads (promoting gigs/projects), Utilities (reminders, notifications, bookmarks), and profile portfolios.
   - **Security**: Protect financial and contractual data, enforce roles (freelancer vs client), and log all critical actions for audits.
   - **Completion Criteria**: Gig/project flows and escrow/dispute processes fully match `logic_flows.md#32-#54`, and UIs look/behave like the rest of Gigvora.

13. ✅**Advertisement Addon Completion**
    - **Goal**: Provide a powerful yet polished Ads Manager that feels native to Gigvora like meta ads .
    - **Scope**:
      - Backend: ensure campaign, ad set, ad, keyword planner, forecast, billing, and policy services are wired correctly.
      - Web UI: redesign dashboards, wizards, keyword planners, simulations, reports, and settings pages with tokens and high-end visual polish.
      - Mobile: integrate Ads Manager flows (campaign view, basic editing, analytics) into Flutter with consistent theming.
      -ads pricing for cpc etc. words need algorithm for the pricing algorithm (smart but low cpu usage)
      -placing/placement algorithm for ads (smart but low cpu usage) - search - in the phone app video swipe - on live feed - profile recomendations - on groups, companies pages, pages etc -
      -competititon algorithm for ads (smart but low cpu usage)
      - words, image, videos, search ads full styling 
    - **UI & UX**: Make key metrics and actions easily discoverable, reduce cognitive load in the wizard, and ensure responsive layouts for analytics tables and charts.
    - **Integration**: Confirm ad placements across feed, profile, search, jobs/freelance, marketplace, and live events behave as defined in `logic_flows.md#34`.
    - **Security & Compliance**: Enforce policy review, advertiser verification, and defensively handle billing/credit operations.
    - **Completion Criteria**: End-to-end ad lifecycle (create, run, analyze, bill) works on web and core monitoring works on mobile, with UI matching design tokens.

14. ✅**Talent & AI / Headhunter / Launchpad**
    - **Goal**: Make Talent & AI the intelligence layer over Jobs, Freelance, and core social graphs.
    - **Scope**:
      - Backend: complete mandate management, headhunter pipelines, Launchpad curricula, AI workspace, and volunteering modules.
      - Web UI: reskin pipelines, Launchpad dashboards, AI workspace panels, and volunteering cards using tokens and accessible layouts.
      - Flutter: integrate Talent & AI routes/screens into mobile nav and align them with web flows.
    - **UI & UX**: Provide clear visual states for candidate progress, cohort progression, and AI actions; emphasize trust and explainability for AI-driven suggestions.
    - **Integration**: Connect Talent & AI outputs to Jobs (recommendations), Freelance (matches), Utilities (reminders), and profile badges/achievements.
    - **Security**: Harden BYOK key management, log AI usage, and guard sensitive candidate/employer data; align with GDPR expectations.
    - **Completion Criteria**: All flows in `logic_flows.md#35-#57` functional, with clean UI and updated `about.md/functions.md`.

15. ✅**Interactive / Live Addon Alignment (Prompt 6B)**
    - **Goal**: Deliver a coherent “Live & Events” experience covering webinars, networking, podcasts, and interviews.
    - **Scope**:
      - Backend: align routes/services for events, registrations, attendance, recordings, networking sessions, podcast catalogs, and interviews.
      - Web UI: reskin event catalogues, detail pages, waiting rooms, live shells, and replays with Gigvora tokens and consistent layouts.
      - Mobile: integrate corresponding Flutter flows for joining events, attending networking, listening to podcasts, and handling interviews.
    - **UI & UX**: Provide clear ticketing/donation flows, intuitive waiting rooms, polished players, and accessible controls on small screens.
    - **Integration**: Tie Interactive into Jobs (interviews), Utilities (reminders/notifications), Ads (sponsored events), and feed/search discovery.
    - **Security**: Protect live session access, recordings, ticket data, and interview scores; enforce role-based access for hosts, interviewers, attendees.
    - **Completion Criteria**: All event types and sub-flows in `logic_flows.md#33-#55` function smoothly across web and mobile with Gigvora UI.

16. **Interviews Experience Completion**
   - **Goal**: Deliver the full end-to-end interviews journey (scheduling → waiting rooms → live shells → scoring) defined in `logic_flows.md#33-interactive-addon-live--webinars--networking--podcasts--interviews`, mirroring the Laravel + Flutter specs in `Gigvora-Addons/Interactive-Addon/About.md#14-interviews`.
   - **Scope**:
     - Backend: finalize slot creation, interviewer panel orchestration, scoring matrices, structured notes, consent tracking, and ATS callbacks so Jobs (`logic_flows.md#31-jobs-addon`) always reflects interview outcomes.
     - Web UI: implement/refresh candidate dashboards, interviewer scoring panels, waiting rooms, and live session shells under `resources/views/vendor/live/interviews/*`, ensuring tokens replace legacy styling and that Blade components reuse shared Utilities widgets.
     - Flutter: align `webinar_networking_interview_podcast_flutter_addon` screens for candidate/interviewer flows (dashboards, countdowns, live RTC shells, scoring sync) with the same information architecture and tokens as web.
     - Data flows: sync interview reminders, calendar entries, notes, and feedback distribution with Utilities (`logic_flows.md#36-utilities-addon`) and Talent & AI dossiers.
   - **UI & UX**: Provide clear scheduling states, responsive countdown/waiting room layouts, distraction-free live shells, and structured scoring tables that follow Gigvora typography, focus states, and accessibility targets.
   - **Integration**: Bridge Jobs ATS stage changes, Talent & AI mandate tracking, Utilities reminders, and notification/event analytics so every interview event is observable and traceable.
   - **Security & Privacy**: Enforce interviewer/candidate permissions, encrypt recordings and scoring artifacts at rest, honor consent + regional retention policies, and ensure GDPR export/delete covers interview notes.
   - **Completion Criteria**: Candidate and interviewer personas complete every sub-flow without gaps on web/mobile, ATS + Utilities data stay in sync, analytics fire per taxonomy, and docs (`docs/progress.md`, `docs/qa-bugs.md`, addon `about.md/functions.md`) capture implementation + QA evidence.

17. **Webinars Experience Completion**
   - **Goal**: Ship the webinars host/attendee lifecycle (catalogue → detail → registration → waiting room → live → replay) as defined in `logic_flows.md#33-interactive-addon-live--webinars--networking--podcasts--interviews` and `Gigvora-Addons/Interactive-Addon/About.md#11-webinars`, ensuring parity across Laravel and Flutter.
   - **Scope**:
     - Backend: lock down event creation APIs, ticket tiers (free/paid/donation), coupon + seating logic, reminders, recording lifecycles, and sponsorship hooks exposed via `Gigvora-Addons/Interactive-Addon/functions.md`.
     - Web UI: modernize catalogue, detail, waiting room, live host shell, attendee panels, and recordings library views under `resources/views/vendor/live/webinars/*` using Gigvora tokens, realtime badges, and CTA hierarchy from `logic_flows.md#3-addons`.
     - Flutter: implement the same catalogue/detail/live/recordings flows with `GigvoraThemeData`, ensuring push reminders, offline states, and replay gating match web behavior.
     - Commerce & content: integrate Ads placements, merch/donation widgets, and Utilities reminders; wire highlight reels + transcript exports into feed/profile surfaces per `logic_flows.md#3.7-cross-addon-journeys`.
   - **UI & UX**: Present crisp cards, countdown experiences, live shells with accessible chat/Q&A, and replay players with chapter navigation while preserving host/attendee affordances across breakpoints.
   - **Integration**: Connect webinars to Feed recommendations, Utilities calendar/notifications, Ads sponsorship inventory, and analytics events (registrations, attendance, engagement) with centralized naming.
   - **Security & Privacy**: Enforce ticket/role checks server-side, protect paid replay URLs, capture host/attendee consent and moderation logs, and monitor abuse via admin dashboard.
   - **Completion Criteria**: Hosting, attending, and replay flows run end-to-end on web/mobile; recordings pipeline stable; analytics + monetization hooks verified; documentation + QA notes updated with evidence and open risks.

18. **Podcasts Experience Completion**
   - **Goal**: Complete the podcasts ecosystem (series management, live recordings, on-demand listening, monetization) per `logic_flows.md#33-interactive-addon-live--webinars--networking--podcasts--interviews` and `Gigvora-Addons/Interactive-Addon/About.md#13-podcasts`, keeping web/Flutter parity.
   - **Scope**:
     - Backend: finish series + episode CRUD, scheduling, guest workflows, paid episode entitlements, donation/ads hooks, and transcript/highlight generation APIs shared with feed/profile surfaces.
     - Web UI: polish catalogue, series detail, episode players, live recording shells, and follow states using tokenized cards, inline audio controls, and AJAX follow/playback interactions.
     - Flutter: mirror catalogue/detail/player/live record screens with persistent audio controls, offline caching, and deep links into Utilities saved items.
     - Distribution: push new episodes to feed, profile “Media” tabs, Utilities notifications/bookmarks, and Ads sponsorship placements following `logic_flows.md#3.7-cross-addon-journeys`.
   - **UI & UX**: Deliver immersive listening experiences with responsive players, semantic typography, focus-visible controls, and host tooling (notes, timers) that match the design system.
   - **Integration**: Tie podcasts into monetization (Ads/donations), analytics (plays, completion, followers), Utilities reminders/bookmarks, and Talent & AI content recommendations.
   - **Security & Privacy**: Guard paid content URLs, validate guest consent + release forms, enforce DRM/lightweight watermarking for premium audio, and ensure audio uploads respect storage quotas.
   - **Completion Criteria**: Series creation, live recording, publishing, listening, and monetization loops validated across platforms; analytics verified; docs + QA artifacts updated with references to `logic_flows.md` and addon specs.

19. **Networking Sessions Completion**
   - **Goal**: Finalize speed/group networking experiences (discovery → registration → waiting room → live rotations → follow-ups) specified in `logic_flows.md#33-interactive-addon-live--webinars--networking--podcasts--interviews` and `Gigvora-Addons/Interactive-Addon/About.md#12-networking`.
   - **Scope**:
     - Ticket purchase - Free/or priced - price setting - deals - coupons etc. 
     -Backend: complete session templates (rounds, duration, topics), AI pairing/matchmaking services, ticketing + waitlists, contact exchange APIs, and follow-up scheduler integrations with Utilities calendar.
     - Web UI: refresh landing, detail, waiting room, live rotation shells (a bit animated), and notes/contact capture flows under `resources/views/vendor/live/networking/*`, making pairing + timer states realtime via websocket or SSE hooks.
     - Flutter: implement equivalent session discovery, registration, waiting room editing (business cards), live pairing UI, and post-session recap flows using shared tokens/components.
     - Follow-ups: push contact exchanges, notes, and reminders into Utilities saved items and CRM handoffs (Jobs/Freelance/Talent & AI) per `logic_flows.md#3.7-cross-addon-journeys`.
   - **UI & UX**: Ensure countdowns, partner cards, and rotation cues are glanceable, thumb-friendly, and accessible; provide clear states for waitlist/full sessions and post-event summaries.
   - **Integration**: Connect networking sessions to feed promotions, Jobs/Freelance invitations, Utilities reminders, Ads sponsorships, and analytics covering registrations, attendance, matches, and follow-through.
   - **Security & Privacy**: Rate-limit contact exchanges, honor reporting/blocking, prevent unauthorized session access, and scrub personal notes according to retention policies.
   - **Completion Criteria**: Live networking runs smoothly on web/mobile with reliable pairing + timer orchestration, follow-up artifacts land in Utilities/Jobs/Freelance, analytics + moderation dashboards updated, and documentation/QA evidence logged.

20. **Cross-Addon Roles, Permissions & Analytics (Prompt 9)**
    - **Goal**: Create a clean, enforceable authorization and analytics model spanning all addons.
    - **Scope**:
      - Define and document the global role/permission matrix (member, freelancer, recruiter, company admin, creator, moderator, platform admin).
      - Implement permission checks/shared middleware across Jobs, Freelance, Ads, Talent & AI, Interactive, and Utilities.
      - Standardize analytics event naming and ensure all critical flows emit events.
    - **UI & UX**: Ensure UI state (visible actions, disabled controls) reflects permissions; avoid exposing actions the user cannot perform.
    - **Integration**: Align nav visibility, button display, and settings screens with the centralized roles model.
    - **Security**: Use least-privilege principles, audit logs for permission changes, and verify no bypasses exist.
    - **Completion Criteria**: Role matrix enforced system-wide, analytics taxonomy stable and documented, and cross-addon journeys respect these rules.

21. **Mobile Convergence**
    - **Goal**: Bring the Flutter app to full parity with the web experience, both visually and functionally.
    - **Scope**:
      - Audit all web flows and confirm a corresponding mobile screen/route exists (feed, profile, Jobs, Freelance, Ads, Talent & AI, Utilities, settings).
      - Align navigation patterns, theming, and interactions using `GigvoraThemeData` and shared components.
      - Ensure offline behavior, deep links, and push notifications support all important flows.
      -Ensure comments in the feeds for both web and mobile have gifs, stickers, emojis, likes, dislikes, sharing too, replies ensure the web has this too 
      -Ensure when uploading into the story its like a tiktok esperience where it can eb uploaded to the story, with editing tools (cropping, add emojis, add effects, add overlay, add filters, add stock music, text overlay too, text stylin, stickers, gifs),  ensure the web has this too and it can directly access the device camera on phone instantly and tablet, on pc it requests unless its able to auto . must use the best resolution postible so ultra hd show 4k at best then 1080p then 780p and 480p lowest 
      -Reel posting process, add with editing tools (cropping, add emojis, add effects, add overlay, add filters, add stock music, text overlay too, text stylin, stickers, gifs), then after next stage, can add text caption , tags, tag location too ensure the web has this too so ultra hd show 4k at best then 1080p then 780p and 480p lowest 
      --Long posting process, , posting time and date plan ahead, add with editing tools (cropping, add emojis, add effects, add overlay, add filters, add stock music, text overlay too, text stylin, stickers, gifs), then after next stage, can add text caption , tags, tag location too so ultra hd show 4k at best then 1080p then 780p and 480p lowest 
      ---Live Streaming - Donations, large donation treats, score leaderboard setup,  Donation goal setup, feed chat, feed alert, feed polls, feed questions, gifs, stickers, emojis, likes, dislikes, sharing too, replies, interactive , links to marketplace, links to projects,links to podcast, links to post, links to groups, links to pages  links to companies etc. so ultra hd show 4k at best then 1080p then 780p and 480p lowest , viewer count, viewr count goals, 
    - **UI & UX**: Deliver native-feeling experiences (gestures, layouts) tuned for phone constraints while preserving design language from web.
    - **Integration**: Confirm API clients, error handling, and analytics hooks mirror backend expectations.
    - **Security**: Validate secure token storage, correct use of HTTPS/CORS, and safe handling of secrets/config.
    - **Completion Criteria**: Mobile app passes a parity checklist against `logic_flows.md#5`, analyzer/status clean, and `docs/mobile-build.md` describes final build process.

22. **Admin, Compliance & Security Hardening**
    - **Goal**: Provide a robust command center for admins with strong security and compliance guarantees.
    - **Scope**:
      - Finish admin dashboards for each addon: KPIs, filters, exports, troubleshooting panels.
      - Implement or refine GDPR export/delete flows, audit logs, and incident response runbooks.
      - Add or enhance monitoring for queues, FFmpeg/FFprobe, external integrations, and dependency health.
      - escrow and dispute controls
      - API controls
      
    - **UI & UX**: Make admin screens clear but powerful, using tokenized tables, filters, and alert components; avoid clutter, emphasize critical signals.
    - **Integration**: Link admin actions to core flows (e.g., user bans, content takedowns, role changes) and log everything.
    - **Security**: Lock admin routes tightly, require strong auth, document security settings, and regularly review logs.
    - **Completion Criteria**: Admin experiences complete and intuitive, GDPR operations verified, monitoring dashboards show healthy system behavior.

23. **Data Layer & Environment Readiness**
    - **Goal**: Make it safe and repeatable to bring up/down environments and keep schemas consistent.
    - **Scope**:
      - Clean up and order migrations, add missing indexes, and reconcile addon tables with core schema.
      - Make all seeders idempotent and suitable for dev/demo environments, updating `install.sql` if used.
      - Update `.env.example` with all required keys per addon and confirm config caching works.
    - **UI & UX**: N/A directly, but ensure any installer/admin flows show clear messaging based on env readiness.
    - **Security**: Remove any debug seeders or env defaults that would be unsafe in production.
    - **Completion Criteria**: Fresh install (`migrate:fresh --seed`) succeeds, documentation guides new contributors through setup, and CI passes schema-related checks.

24. **Build, QA & Release**
    - **Goal**: Ship a stable, production-ready Gigvora release across web and mobile.
    - **Scope**:
      - Verify composer/npm builds, run Flutter debug + release builds without warnings/failures.
      - Execute persona-based smoke tests for each major role using `logic_flows.md` as a checklist.
      - Collect and triage defects, finalize release notes, and capture lessons learned in `docs/progress.md`.
    - **UI & UX**: Validate that all previously-updated UIs behave correctly in real flows and that styling is consistent and performant.
    - **Integration & Security**: Double-check that cross-addon integrations, feature flags, and security boundaries hold under real usage.
    - **Completion Criteria**: QA matrix complete, only low-risk known issues remain, release documentation finalized, and go-live checklist signed by stakeholders.

## Execution Rules & References
- Always update `logic_flows.md`, `docs/ui-audit.md`, `docs/nav-structure.md`, `docs/progress.md`, and `docs/qa-bugs.md` when relevant changes occur.
- Token work begins in `resources/css/gigvora/tokens.css`; Flutter must mirror the same palette.
- Migration `2025_11_30_013909_add_duration_flags_to_media_files_and_videos.php` must run locally (requires ffmpeg/ffprobe).
- Existing services (`ProfileInsightsService`, `ProfileJourneyService`, `FeedRecommendationService`) should be reused rather than rewritten.
- For Flutter, update the host theme before modifying addon packages to avoid double work.
- Prompt X (Addon Logic, Navigation & Compliance) is satisfied throughout tasks 10–18; Prompts 1–4 (Ads + Talent & AI) correspond to tasks 13–14.

## Acceptance Summary
A task is “done” only when:
1. Implementation matches the referenced `logic_flows.md` sections and functional specs (`Gigvora-Addons/*/about.md` & `functions.md` where applicable).
2. Documentation (design system, flows, nav, UI audit) reflects the change, and QA evidence is logged.
3. Web + Flutter parity (and admin/storage/security implications) are validated.

## Agent Roles & Handoffs
- **Implementation Agent (Web/Core)**: Owns Laravel + Blade delivery for each task, reusing shared services (`ProfileInsightsService`, `FeedRecommendationService`, etc.) and ensuring migrations/configs stay in sync with `logic_flows.md`.
- **Flutter Agent**: Mirrors every UI/flow change inside `Gigvora Flutter Mobile App/`, keeps `GigvoraThemeData` authoritative, and flags parity gaps in `docs/progress.md`.
- **Docs & Governance Agent**: Updates `docs/ui-audit.md`, `docs/nav-structure.md`, `docs/analytics-feature-flags.md`, and the relevant addon `about.md/functions.md` files whenever surfaces, tokens, or flows shift.
- **QA & Evidence Agent**: Builds persona-based test passes against `logic_flows.md` anchors, files regressions in `docs/qa-bugs.md`, and captures screenshots/logs referenced from `docs/progress.md`.
- **Security & Ops Agent**: Tracks migrations, env/setup changes, auditing hooks, and role/permission impacts; ensures `.env.example`, seeds, and monitoring docs reflect every release task.

## Reporting & Evidence Flow
1. Announce scope + linked task number before making changes; cite the relevant `logic_flows.md#anchor`.
2. Implement web + Flutter updates in lockstep, reusing tokens/components; highlight any unavoidable divergence in `docs/progress.md`.
3. Update documentation immediately after code changes so reviewers can cross-reference deltas with the canonical specs.
4. Capture QA notes (what was tested, personas, data states) and link any defects or open risks inside `docs/qa-bugs.md`.
5. Record snapshots in `docs/progress.md` summarizing work completed, outstanding follow-ups, and cross-addon dependencies that may block later tasks.

## QA & Security Gates
- Run relevant PHPUnit, npm, and Flutter test suites; document skipped suites with justification.
- Re-run `php artisan migrate --pretend`, `npm run build`, and Flutter `analyzer` after schema or token edits; attach outputs or summaries to the progress snapshot.
- Verify rate limiting, permission middleware, and CSRF protections on any composer/feed/profile actions touched.
- When migrations touch media, ensure `ffmpeg`/`ffprobe` availability satisfies `2025_11_30_013909_add_duration_flags_to_media_files_and_videos.php`.
- Confirm analytics events follow the centralized taxonomy before closing any task involving telemetry.

## Communication & Escalation
- Surface blockers immediately in `docs/progress.md` under an “Open Risks” subsection referencing impacted tasks.
- When discovering misaligned flows or missing specs, pause implementation and update `logic_flows.md` first to keep it authoritative.
- Treat third-party or addon-owned bugs as shared issues: open entries in `docs/qa-bugs.md` and tag the owning addon folder for traceability.
- Never revert another agent’s work without written confirmation from the requestor; layer fixes on top and document assumptions.

## Workspace Editing Constraints
- Default to ASCII unless the file already uses other character sets or a feature explicitly requires them.
- Prefer succinct, high-value comments only where the code is non-obvious; avoid narrating simple assignments or boilerplate.
- Use `apply_patch` for surgical file updates; reserve generators or formatters (e.g., `gofmt`, scaffolding scripts) for cases where manual diffs would be error-prone.
- Never overwrite or revert user-authored changes you did not make; integrate with existing diffs instead of scrubbing them.
- Skip destructive git commands (`git reset --hard`, `git checkout --`) unless the requestor gives written approval.

## Command & Tooling Guidance
- On the first terminal invocation, `cd` into `/Users/user/Downloads/Gigvora-Release-1` and respect the persistent shell state across subsequent commands.
- Prefer dedicated tools (`read_file`, `rg`, `codebase_search`, `apply_patch`) over ad-hoc shell commands for file reads, searches, and edits; they respect repo ignore rules and keep diffs reviewable.
- For long-running processes, ask before starting background jobs and mark them with `is_background=true` to avoid blocking the shell.
- When responding to “show output” style requests, summarize the meaningful lines instead of pasting entire logs.
- Confirm with the user before running migrations, build steps, or scripts that could mutate shared environments; document any skipped steps and why in `docs/progress.md`.

## Todo Management
- Create a todo list for multi-step efforts (beyond simple tasks) as the first action; set the initial item to `in_progress`.
- Update todos via `todo_write` (merge=true) after completing each step; keep only one `in_progress` item at a time.
- Cancel tasks that are no longer relevant and avoid single-step todo lists.
- Skip the todo tool entirely for the simplest ~25% of requests.

## Special User Requests
- Run simple informational commands (e.g., `date`) when explicitly asked rather than estimating answers.
- Treat “review” prompts as formal code reviews: list findings in severity order, focus on risks/regressions/tests, and keep summaries brief afterward.
- When the user modifies a proposed command before approval, honor their edits exactly.

## Final Response Guidelines
- Default to concise, friendly tone; use Markdown headings sparingly (short Title Case labels).
- Lead code-change summaries with a short explanation, then describe impacted files/sections and rationale.
- Reference files and symbols using backticks (e.g., `resources/css/gigvora/tokens.css`), never line numbers.
- Provide next steps or verification suggestions (tests/builds) whenever actionable; call out skipped work explicitly.
- For simple confirmations, skip heavy formatting; for substantial work, follow the structure rules above (bullets 4–6 items, no nesting).

