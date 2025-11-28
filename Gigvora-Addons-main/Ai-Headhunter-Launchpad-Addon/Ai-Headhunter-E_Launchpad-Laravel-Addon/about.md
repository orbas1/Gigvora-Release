# Talent & AI Addon — Sociopro Integration

The Talent & AI Laravel addon is now integrated into the Sociopro host via Composer path repositories and the `Gigvora\\TalentAi\\Providers\\TalentAiServiceProvider`, which is also registered in `config/app.php`.

Routes are exposed under Sociopro using the following prefixes:
- Web: `/addons/talent-ai/*` (headhunter, launchpad, volunteering admin flows)
- API: `/api/addons/talent-ai/*` (AI workspace endpoints protected by Sanctum)

Feature flags live in `config/gigvora_talent_ai.php` and map to env variables:
- `enabled`
- `modules.headhunters.enabled`
- `modules.launchpad.enabled`
- `modules.ai_workspace.enabled`
- `modules.volunteering.enabled`

BYOK/AI provider settings are controlled through `GIGVORA_TALENT_AI_PROVIDER`, `GIGVORA_TALENT_AI_BYOK_ENABLED`, and `GIGVORA_TALENT_AI_PLATFORM_KEYS_ENABLED` env vars with pricing, guardrails, and rate limits namespaced under `gigvora_talent_ai`.

Policies and the `manage_talent_ai` gate enforce Sociopro admin-only access to admin screens while keeping module access behind authentication and per-module toggles.
