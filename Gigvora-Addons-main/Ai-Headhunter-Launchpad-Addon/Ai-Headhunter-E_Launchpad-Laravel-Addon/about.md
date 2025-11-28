# Talent & AI Addon — Gigvora Integration

The Talent & AI Laravel addon is now integrated into the Gigvora host via Composer path repositories and the `Gigvora\\TalentAi\\Providers\\TalentAiServiceProvider`, which is also registered in `config/app.php`.

Routes are exposed under Gigvora using the following prefixes:
- Web: `/addons/talent-ai/*` (headhunter, launchpad, volunteering admin flows)
- API: `/api/addons/talent-ai/*` (headhunter, launchpad, volunteering, and AI workspace endpoints protected by Sanctum for both web and phone clients)

Feature flags live in `config/gigvora_talent_ai.php` and map to env variables:
- `enabled`
- `modules.headhunters.enabled`
- `modules.launchpad.enabled`
- `modules.ai_workspace.enabled`
- `modules.volunteering.enabled`

BYOK/AI provider settings are controlled through `GIGVORA_TALENT_AI_PROVIDER`, `GIGVORA_TALENT_AI_BYOK_ENABLED`, and `GIGVORA_TALENT_AI_PLATFORM_KEYS_ENABLED` env vars with pricing, guardrails, and rate limits namespaced under `gigvora_talent_ai`.

Policies and the `manage_talent_ai` gate enforce Gigvora admin-only access to admin screens while keeping module access behind authentication and per-module toggles. Mobile clients (Flutter addons) can now consume the published `/api/addons/talent-ai/*` routes for headhunters, launchpad programmes, volunteering, and AI workspace status without conflicting with Gigvora core.

## Web UI & Branding
- **Base layout:** All Blade views extend Gigvora’s shared layout (`layouts.app`) to inherit the global header, footer, and spacing scale.
- **Navigation placement:** Surface a top-level **Talent & AI** menu with sub-items **Headhunters**, **Experience Launchpad**, **AI Workspace**, and **Volunteering**. Menu visibility respects `gigvora_talent_ai.enabled` and each `gigvora_talent_ai.modules.*.enabled` toggle; admin-only areas remain behind the `manage_talent_ai` gate.
- **Key screens & routes:**
  - `/addons/talent-ai/headhunter/*` → Headhunter pipelines and mandate management (JS mix entrypoint: `js/addons/talent_ai/pipeline_board.js`).
  - `/addons/talent-ai/launchpad/*` → Launchpad programmes and applications (JS mix entrypoint: `js/addons/talent_ai/launchpad_progress.js`).
  - `/addons/talent-ai/ai-workspace` → AI tools and usage (JS mix entrypoint: `js/addons/talent_ai/ai_workspace.js`).
  - `/addons/talent-ai/volunteering/*` → Volunteering opportunities and filters (JS mix entrypoint: `js/addons/talent_ai/volunteering_filters.js`).
- **Styling:** The shared stylesheet `css/addons/talent_ai/talent_ai.css` (served via Mix) keeps cards, pipelines, and AI tiles visually aligned with Gigvora branding.

## Mobile Integration
- **Dependencies:** `talent_ai_flutter_addon` added as a local dependency in the Flutter shell (`../Gigvora-Addons-main/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-flutter_addon`).
- **API base:** Targets the same Gigvora host endpoints under `/api/addons/talent-ai/*` with the mobile auth token injected via the shared provider.
- **Navigation:** Mobile routes include `/talent-ai/headhunters`, `/talent-ai/headhunters/mandates/:id`, `/talent-ai/launchpad`, `/talent-ai/launchpad/:id`, `/talent-ai/launchpad/applications/:id`, `/talent-ai/ai-workspace`, `/talent-ai/volunteering`, `/talent-ai/volunteering/:id` surfaced beneath the **Talent & AI** menu. Icons mirror the web set (work_outline, school_outlined, smart_toy_outlined, volunteer_activism_outlined).
- **Providers:** `GigvoraAddonProviders.talentAi` (see `Sociopro Flutter Mobile App/App/lib/addons_integration.dart`) attaches ChangeNotifier providers for Headhunter, Launchpad, AI Workspace, and Volunteering states so screens render live data and analytics.

## Database Schema & Seeders
- **Headhunters:** `headhunter_profiles` (FK `user_id`, indexed status), `headhunter_mandates` (FK `organisation_id` → `organizations`, indexed status), `headhunter_candidates`, `headhunter_pipeline_items` (unique per mandate/candidate, indexed stage), `headhunter_interviews` (FK `scheduled_by` → `users`, indexed status/schedule).
- **Launchpad:** `launchpad_programmes`, `launchpad_tasks` (indexed order), `launchpad_applications` (indexed user/status), `launchpad_application_task_progress`, and `launchpad_interviews` (indexed status).
- **AI Workspace:** `ai_sessions` (indexed by `user_id`, tool, and status), `ai_byok_credentials`, `ai_subscription_plans`, `ai_user_subscriptions` (unique per user/plan with status index), `ai_usage_aggregates` (indexed period/user).
- **Volunteering:** `volunteering_opportunities` (FK `organisation_id` → `organizations`, creator/status indexes) and `volunteering_applications` (indexed user/status).
- **Seeders:** `Database\Seeders\TalentAiSeeder` provisions AI subscription plans from `config/gigvora_talent_ai.php` and is invoked automatically from the host `DatabaseSeeder`.
