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
  - `/addons/talent-ai/headhunter/*` → Headhunter pipelines and mandate management (JS: `resources/js/addons/talent_ai/pipeline_board.js`).
  - `/addons/talent-ai/launchpad/*` → Launchpad programmes and applications (JS: `resources/js/addons/talent_ai/launchpad_progress.js`).
  - `/addons/talent-ai/ai-workspace` → AI tools and usage (JS: `resources/js/addons/talent_ai/ai_workspace.js`).
  - `/addons/talent-ai/volunteering/*` → Volunteering opportunities and filters (JS: `resources/js/addons/talent_ai/volunteering_filters.js`).
- **Styling:** The shared stylesheet `resources/css/addons/talent_ai/talent_ai.css` keeps cards, pipelines, and AI tiles visually aligned with Gigvora branding.
