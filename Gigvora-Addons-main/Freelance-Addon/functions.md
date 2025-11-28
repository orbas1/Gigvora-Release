# functions.md

## Overview
The freelance package layers a complete marketplace experience on top of a social/LinkedIn-style host. The Laravel module exposes gigs, projects, contracts, escrow, disputes, reviews, and onboarding flows, while the Flutter add-on provides mobile pages that consume the same authenticated API. Both sides share users and permissions from the host platform so freelancers and clients can switch roles without duplicating accounts.

### Alignment with Sociopro/Gigvora host
- **Route shape**: API endpoints are grouped under the configurable `freelance.api.prefix` (default `api`); set `FREELANCE_API_PREFIX=api/freelance` to isolate the namespace while preserving Sanctum + `verified` + `role` middleware from the route file. Web middleware can be aligned with `FREELANCE_WEB_MIDDLEWARE` and optional `FREELANCE_WEB_PREFIX` for Blade/Llivewire pages.
- **Middleware**: The API group uses `freelance.api.middleware` (default `api`) and nests Sanctum + role middleware for authenticated flows, matching the Sociopro Gigvora guard pattern.
- **Policies/roles**: Controllers assume host roles `buyer`, `seller`, and `admin`; navigation/menu exposure in both web and Flutter should respect these roles when wiring links.
- **Navigation hooks**: Web menus live in `resources/views/components/navigation/freelance-menu.blade.php` (Gigvora layout-friendly with responsive BEM styling). Flutter exposes `buildFreelanceMenu(isFreelancer: bool, isClient: bool, includeGlobal: bool)` to merge items into the host drawer/tab structure.
- **Auth source of truth**: Login, registration, and password reset flows remain in the core Gigvora app; the freelance add-on consumes the shared session/token instead of publishing duplicate endpoints.

## Architecture & Modules
- **Laravel package (`freelance_laravel_package`)**
  - Service provider (`src/FreelanceServiceProvider.php`) loads publishable routes, migrations, translations, and views.
  - Controllers and services in `publishable/app/Http/Controllers/Api` and `publishable/app/Services` handle gigs, projects, disputes, escrow, profile enrichment, and tagging.
  - Livewire/Blade views in `publishable/resources/views/vendor/freelance` deliver onboarding, dashboards, gig/project workflows, and reviews.
- **Flutter add-on (`freelance_phone_addon`)**
  - API client (`lib/src/api/freelance_api_client.dart`) with repository wrappers (`lib/src/repositories/freelance_repository.dart`).
  - Riverpod state in `lib/src/state` (gigs, projects, disputes, escrow, dashboard, tags, management helpers).
  - UI screens under `lib/src/ui/screens` for gigs, projects, disputes, escrow, and management flows; menu wrappers in `lib/src/pages` hook host routes into these data-backed screens.
- **Core flow (diagram bullets)**
  - Onboarding → Role toggles + profile completeness → Dashboard metrics/recommendations.
  - Projects → Browse/search → Detail → Proposal submission → (Client) hire & escrow.
  - Gigs → List/manage → Detail → Orders & delivery → Reviews.
  - Contracts & milestones → Escrow funding → Release/refund → Reviews/disputes.
  - Disputes → Stage tracking → Admin resolution → Escrow decisions.

## Functions & Features (Laravel)
- **Gigs**
  - `GET /api/gigs` → `Api\GigController@index` (list with filters/pagination).
  - `GET /api/gig/{id}` → `Api\GigController@gigDetail` (detail, pricing, faqs, reviews).
  - `POST /api/gig/{id}/timeline|faq|addon|package|requirement|change|review` → `Api\GigManagementController` (timeline, FAQs, add-ons, packages, requirements, change requests, reviews).
  - `POST /api/gigs/custom` → `Api\GigManagementController@customGig` (custom gig brief).
- **Projects & Proposals**
  - `GET /api/projects` → `Api\ProjectController@index` (filters: keyword, price range, skills, languages, expertise, location, category, pagination).
  - `GET /api/project/{id}` → `Api\ProjectController@getProjectDetail` (full detail + milestones/tasks context).
  - `GET /api/recent-projects` → `Api\ProjectController@recentProjects` (feed tiles/recommendations).
  - `POST /api/submit-proposal/{id}` → `Api\ProposalController@submitProposal` (seller-only; attaches pricing/duration/cover letter, applies fees from `ProposalController@getFeeTax`).
  - `POST /api/project/{slug}/board|tasks|milestones|submission|time-log|invite|match|review` → `Api\ProjectManagementController` (board view, tasks, milestone funding/submission, hourly logging, invitations/matching, project reviews).
- **Escrow & Transactions**
  - `GET /api/escrows/manage` → `Api\EscrowManagementController@index` (escrow list with actions).
  - `POST /api/escrow/{id}/partial-release` → `Api\EscrowManagementController@partialRelease`.
  - `POST /api/escrow/{id}/decision` → `Api\EscrowManagementController@adminDecision`.
  - Webhook: `POST /escrow-transaction-updates` → `Webhook\WebhookController@EscrowTransactionUpdates` (payment gateway callbacks).
- **Disputes**
  - `GET /api/disputes` → `Api\DisputeController@getDisputes` (user-filtered list).
  - `GET /api/dispute/{id}/stages` → `Api\DisputeStageController@stages` (stage timeline).
  - `POST /api/dispute/{id}/advance` → `Api\DisputeStageController@advance` (admin/facilitator stage changes).
- **Profile Enrichment & Tags**
  - `GET/POST/PUT/DELETE /api/profile/{portfolio|education|certification}` → `Api\ProfileEnrichmentController` (portfolios, education, certifications).
  - `GET /api/profile/reviews` + `POST /api/profile/review` → `Api\ProfileEnrichmentController` (profile reviews + averages).
  - `GET /api/freelance/tags` + `POST /api/freelance/profile/tags` + `POST /api/gig/{id}/tags` → `Api\ProfileTagController` (taxonomy fetch and updates; admin CRUD under `/api/admin/freelance/tags`).
  - **Accounts & payouts**
    - `GET /api/account-stats`, `/api/payout-history`, `/api/payout-method` and `POST /api/setup-payout-method|withdraw-amount` → `Api\AccountController` (earnings + payouts). Authentication and tokens come from the host login session.

## Functions & Features (Flutter)
- **Routing (menu wrappers in `lib/src/menu.dart`)**
  - `/freelance/onboarding` → `FreelanceOnboardingScreen` (role toggles + profile info validation).
  - `/freelance/freelancer/dashboard` → `FreelancerDashboardScreen` (metrics via `dashboardSnapshotProvider`, recommendations, escrow preview).
  - `/freelance/freelancer/gigs` → `GigsListPage` (filters, favourite toggle, detail navigation).
  - `/freelance/freelancer/projects` → `ProjectListPage` (browse/search, opens `ProjectDetailPage`).
  - `/freelance/freelancer/project` → `ProjectDetailScreen` wrapper over `ProjectDetailPage` with proposal CTA.
  - `/freelance/freelancer/proposal` → `ProposalEditScreen` (submits bid via repository `placeBid`).
  - `/freelance/freelancer/proposals` → `MyProposalsScreen` (shows recommendation-backed submissions list, refreshable).
  - `/freelance/freelancer/contracts` → `FreelancerContractsScreen` (contract placeholder pending API wiring).
  - `/freelance/contract` → `ContractDetailScreen` (milestone view; expects contract argument).
  - `/freelance/client/dashboard` → `ClientDashboardScreen` (project/gig/dispute/escrow metrics, recommendations).
  - `/freelance/client/projects` → `ClientProjectsScreen` (client-side list wrapper).
  - `/freelance/client/contracts` → `ClientContractsScreen` (contract placeholder pending API wiring).
  - `/freelance/escrow` → `EscrowStatusPage` (escrow list with balances/status).
  - `/freelance/disputes` → `DisputeListPage` (list + creation + stage chips per dispute).
  - `/freelance/dispute` → `DisputeDetailScreen` (stage timeline + messages view).
  - `/freelance/review` → `ReviewScreen` (profile review submission via repository `submitProfileReview`).
- **State & Services**
  - `FreelanceApiClient` normalises base URL/token, enforces 20s timeout, and decodes Laravel API shapes.
  - `FreelanceRepository` wraps API for gigs/projects/disputes/escrow/tags/portfolios/education/certifications/reviews/recommendations and bid/dispute actions.
  - Providers: `gigsProvider`, `projectsProvider`, `disputesProvider`, `disputeStagesProvider`, `escrowProvider`, `tagActionsProvider`, `dashboardSnapshotProvider`.
  - UI widgets: `GigCard`, `ProjectCard`, `MetricGrid`, `MilestoneList`, etc.

## Integration Guide – Feed & Search
- **Feed**
  - Laravel: include gig/project cards by calling `ProjectController@recentProjects` or `GigController@popularGigs`, then render `resources/views/vendor/freelance/components` partials or transform to your feed item schema (title, summary, URL, owner, timestamp).
  - Flutter: use `freelanceRepository.fetchRecommendations()` to pull recommended gigs/projects; map to your feed tiles or attach to home tab.
- **Search**
  - Laravel: `GET /api/projects`, `GET /api/gigs`, `GET /api/sellers`, `GET /api/tags` provide query + filter support. Index fields: project/gig title, description, tags, skills, location, price ranges.
  - Flutter: use `freelanceRepository.fetchProjects`/`fetchGigs` with filters, or `freelanceRepository.searchFreelance` for unified results; handle pagination via returned `Pagination`.

## Integration Guide – Analytics
- **Events** (emit in host analytics pipeline when these actions occur):
  - `freelancer_onboarded`, `client_onboarded`, `gig_created`, `gig_order_created`, `project_posted`, `proposal_submitted`, `proposal_accepted`, `proposal_rejected`, `contract_created`, `contract_completed`, `escrow_funded`, `escrow_released`, `escrow_refunded`, `dispute_opened`, `dispute_resolved`, `review_submitted`.
- **Backend hooks**: listen to controller actions (proposal submission, escrow actions, dispute stage changes, profile review creation) and dispatch events to your analytics service. Livewire/Blade buttons can call a shared helper or queued job to log events.
- **Flutter hooks**: wrap repository calls (e.g., `placeBid`, `openDispute`, `submitProfileReview`) with callbacks to your analytics client; screens are organised via Riverpod so you can `ref.listen` providers for success states.

## Security, Reliability & Performance Notes
- Auth: API routes use Sanctum + `verified` middleware and role gates (`buyer|seller|admin`). Protect admin routes (`admin.php`) separately.
- Validation: Form Requests under `publishable/app/Http/Requests` enforce input rules; Blade uses escaped output by default. Mobile forms validate basics before submission.
- Authorisation: Controllers rely on role middleware and per-resource checks inside services; ensure policies restrict cross-user access to projects, gigs, escrow, and disputes.
- Pagination: API collections return `{list, pagination}`; always pass `per_page` and honour pagination on the client.
- Performance: Reuse eager-loading in services (projects/gigs with relations) and enable indexes on foreign keys, price ranges, and status columns from migrations.
- Error handling: API responses use a consistent JSON envelope with messages; client surfaces errors via SnackBars and keeps AsyncValue error states visible.
- File uploads: routes go through middleware like `verify-payment-gateway` and profile photo upload validations; enforce mime/size on host app storage configuration.

## Configuration & Environment
- Publishable config: `config/freelance.php` toggles features (`features.publish_routes`, queue/search/feed knobs) and commission defaults.
- `.env` keys to set in host: database credentials, Sanctum config, cache/queue drivers, payment gateway secrets (for escrow), mail settings for notifications.
- After installation: run `php artisan vendor:publish --tag=freelance-*`, then `php artisan migrate`. Ensure queue workers are running for search/feed jobs and broadcast channels configured if using live updates.

## Quick Start – Integration Steps
1. **Laravel**
   - Add repository path in `composer.json`; `composer require taskup/freelance-laravel-package`.
   - Register `Taskup\Freelance\FreelanceServiceProvider::class` if auto-discovery is disabled.
   - Publish assets (`freelance-config`, `freelance-app`, `freelance-migrations`, `freelance-routes`, `freelance-views`, `freelance-lang`).
   - Run `php artisan migrate` and seed any initial taxonomies/tags.
   - Wire navigation links to `/dashboard`, `/gigs-listing`, `/projects`, `/create-project`, `/create-gig`, `/dispute-list`, `/gig-activity/{slug}`.
2. **Flutter add-on**
   - Add dependency in host `pubspec.yaml` pointing to `freelance_phone_addon`.
   - Wrap app with `ProviderScope`; override `baseUrlProvider` and `tokenProviderOverride` to point at your Laravel `/api/` base and auth token getter.
   - Register routes from `buildRoutes()` and expose menu items (`freelancerMenu`, `clientMenu`, `globalMenu`).
   - Add analytics hooks around repository calls for the events listed above.
   - Use UI screens from `lib/src/ui/screens` for gigs/projects/disputes/escrow; dashboards and proposal/review flows are wired to live API data via Riverpod providers.
