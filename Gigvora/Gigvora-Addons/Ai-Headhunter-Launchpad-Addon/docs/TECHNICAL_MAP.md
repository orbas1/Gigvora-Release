# Talent & AI Addon – Technical Map

This document maps the primary classes, routes, and UI assets for the Talent & AI addon. Use it alongside `AGENTS.md` and the codebase for deeper implementation details.

## Backend (Laravel)

### Headhunters
- **Models**: `Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterProfile`, `HeadhunterMandate`, `HeadhunterCandidate`, `HeadhunterPipelineItem`, `HeadhunterInterview`.
- **Services**: `HeadhunterService` (profiles/mandates/candidates), `HeadhunterPipelineService` (stage moves + notes), `HeadhunterInterviewService` (interview scheduling/outcomes).
- **Controllers**: `HeadhunterProfileController` (profile CRUD), `MandateController` (mandate CRUD), `CandidateController` (candidate CRUD), `PipelineController` (create + move items), `InterviewController` (log/update interviews).
- **Routes**: Web routes prefixed with `/addons/talent-ai/headhunter/*`, namespaced `addons.talent_ai.headhunter.*`. Pipeline move endpoint: `POST /addons/talent-ai/headhunter/pipeline/{pipelineItem}/move`. Policies guard each model.

### Experience Launchpad
- **Models**: `LaunchpadProgramme`, `LaunchpadTask`, `LaunchpadApplication`, `LaunchpadInterview` under `Gigvora\TalentAi\Domain\Launchpad\Models`.
- **Services**: `LaunchpadProgrammeService`, `LaunchpadApplicationService`, `LaunchpadProgressService` (tasks/progress logic).
- **Controllers**: `ProgrammeController` (create/update/publish/close), `ApplicationController` (apply + status updates), `InterviewController` (schedule/update interviews).
- **Routes**: Web prefix `/addons/talent-ai/launchpad/*`, namespaced `addons.talent_ai.launchpad.*` (e.g., `launchpad.programme.store`, `launchpad.application.store`).

### AI Workspace
- **Models**: `AiSession`, `AiByokCredential`, `AiSubscriptionPlan`, `AiUserSubscription`, `AiUsageAggregate` under `Domain\AiWorkspace\Models`.
- **Services**: `AiWorkspaceService` (tool orchestration), `AiProviderService` (provider selection/BYOK), `AiBillingService` (usage and cost aggregation).
- **Controllers**: `ToolController` exposes tool endpoints (CV writer, outreach, social calendar, coach, repurpose, interview prep, image canvas, writer, marketing bot). `ByokController` manages BYOK credentials.
- **Routes**: API prefix `/api/addons/talent-ai/ai/*`, namespaced `api.addons.talent_ai.ai.*`. BYOK routes for storing/destroying credentials; guarded by Sanctum and module flags.

### Volunteering
- **Models**: `VolunteeringOpportunity`, `VolunteeringApplication` under `Domain\Volunteering\Models`.
- **Services**: `VolunteeringService` (opportunity CRUD/publishing), `VolunteeringMatchingService` (matching + progress helpers).
- **Controllers**: `OpportunityController` (create/update/publish/close), `ApplicationController` (apply + status updates).
- **Routes**: Web prefix `/addons/talent-ai/volunteering/*`, namespaced `addons.talent_ai.volunteering.*` (e.g., `volunteering.opportunity.store`, `volunteering.application.status`).

### Admin / Shared
- **Config**: `config/gigvora_talent_ai.php` defines `enabled`, per-module flags, AI provider options, pricing tiers, guardrails, defaults for pipeline stages/categories, and analytics log channels.
- **Service Provider**: `Gigvora\TalentAi\Providers\TalentAiServiceProvider` merges config, loads routes/views/translations/migrations, registers policies (`Headhunter*`, `Launchpad*`, `Volunteering*`), defines `manage_talent_ai` gate, and injects an admin menu partial into admin layouts.
- **Controllers**: `AdminController` (settings and plan management) with routes under `/addons/talent-ai/admin/*`.
- **Policies**: Located in `src/Policies` for each model plus `AiAdminPolicy` for admin gate.
- **Analytics**: Controlled via `analytics.emit_events` and log channels in config; emitted through domain services where enabled.

## Web UI
- **Blade Views**: User-facing views under `resources/views/talent_ai/` covering headhunters (dashboard/mandates/pipeline/candidate detail), launchpad (programmes/applications), AI workspace index, and volunteering (opportunities/applications). Admin screens live under `resources/views/talent_ai/admin/` including settings, plans, moderation, and a sidebar partial.
- **JavaScript**: Interactivity under `resources/js/addons/talent_ai/` (`pipeline_board.js`, `launchpad_progress.js`, `ai_workspace.js`, `volunteering_filters.js`, `admin_settings.js`, `talent_ai.js`).
- **Styles**: Scoped stylesheet `resources/css/addons/talent_ai/talent_ai.css` for addon UI components.
- **Translations**: English strings in `resources/lang/en/addons_talent_ai.php` registered via the service provider.

## Flutter Addon
- **Models**: All Talent & AI DTOs in `lib/models/talent_ai_models.dart` (headhunter profiles/mandates/candidates/pipeline/interviews, launchpad programmes/tasks/applications/interviews, AI workspace sessions/subscriptions/usages, volunteering opportunities/applications) exported through `lib/models/models.dart`.
- **API Services**: `lib/services/talent_ai_api.dart` wraps HTTP calls to `/api/addons/talent-ai/...` endpoints for each pillar; exported via `lib/services/services.dart`.
- **State**: `lib/state/talent_ai_state.dart` provides ChangeNotifier-backed state for mandates/pipelines, programmes/applications, AI workspace sessions/tool runs, and volunteering flows.
- **Screens**: Primary UI in `lib/ui/talent_ai.dart` (headhunter dashboard, mandate detail + pipeline board, candidate detail with AI suggestions, launchpad programme/applications, AI workspace tools, volunteering lists and applications). Menu configuration comes from `lib/menu/menu.dart` with feature-flagged entries. Analytics helper lives in `lib/analytics/talent_ai_analytics.dart`.

## Integration Notes
- Module flags (`enabled` and `modules.*.enabled`) gate both web and API routes; ensure they are set before exposing menus.
- Web and Flutter UIs consume the same Sanctum-protected API namespace (`/api/addons/talent-ai/*`).
- Admin navigation is injected via a view composer (`talent_ai::admin.partials.menu`) and requires the `manage_talent_ai` gate.
- AI workspace calls are proxied through backend tool endpoints—no provider keys are stored on the client. BYOK is stored via `ByokController` and enforced in `AiProviderService`.
- Analytics emission is optional and should respect the `analytics.emit_events` flag; mobile clients send events via `TalentAiAnalyticsClient`.
