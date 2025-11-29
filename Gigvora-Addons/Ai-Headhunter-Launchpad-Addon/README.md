# Talent & AI Addon

This repository contains the Sociopro Talent & AI addon that combines Headhunters, Experience Launchpad, AI Workspace, and Volunteering into a single package for Laravel (web) and Flutter (mobile). It layers functionality on top of existing Sociopro accounts and permissions without altering core behaviour.

## Features

### Headhunters
- Headhunter profiles with mandate creation and candidate intake.
- Pipeline board with configurable stages, interview logging, and stage moves.
- Candidate detail pages with notes and AI suggestions sections.

### Experience Launchpad
- Programme creation and publishing with tasks, hours/weeks, and outcome details.
- Candidate browsing and application submission with status updates.
- Progress tracking across tasks and interviews.

### AI Workspace
- Unified toolset (CV/Profile writer, outreach, social calendar, repurposing, interview prep, images/canvas, writer/chat, marketing bot).
- BYOK support and platform key usage governed by pricing tiers and guardrails.
- Usage tracking and subscription-aware behaviours.

### Volunteering
- Opportunity creation/publishing with verification and moderation hooks.
- Candidate browsing, filtering, and applying to unpaid roles.
- Application status and progress visibility.

### Admin & Settings
- Module enablement toggles and AI provider/guardrail summaries.
- Subscription plan management and moderation panels for Launchpad and Volunteering.
- Admin sidebar/menu partial registered via the service provider.

## Installation (Laravel)
1. Require the package in your host app (path or VCS source), for example:
   ```bash
   composer require gigvora/ai-headhunter-launchpad-addon
   ```
2. Ensure the service provider is loaded (auto-discovered via `composer.json`). If discovery is disabled, register `Gigvora\TalentAi\Providers\TalentAiServiceProvider` manually.
3. Copy `config/gigvora_talent_ai.php` into your host application's config directory to override defaults (the file is merged automatically if left untouched).
4. Run database migrations to create addon tables:
   ```bash
   php artisan migrate
   ```
5. Build/publish web assets if you surface the provided JS/CSS:
   ```bash
   npm install
   npm run build
   ```
   or integrate the resources into your existing Vite/Mix pipeline.

## Configuration
Key options live in `config/gigvora_talent_ai.php`:

- `enabled`: master flag for the addon.
- `modules.*.enabled`: per-pillar toggles (`headhunters`, `launchpad`, `ai_workspace`, `volunteering`).
- `ai.provider`: selected AI backend; `ai.byok.enabled` and `ai.platform_keys.enabled` control credential sources.
- `ai.pricing_tiers`, `ai.cost_model`, `ai.usage_limits`, `ai.guardrails`: govern AI plan limits, pricing hints, and safety boundaries.
- `headhunters.default_pipeline_stages`, `headhunters.roles`, `headhunters.fee_models`: headhunter defaults and permissions.
- `launchpad.default_categories` and `launchpad.rules`: programme defaults and publishing requirements.
- `volunteering.default_categories`, `volunteering.verification_required`, `volunteering.moderation_enabled`: volunteering defaults.
- `analytics.emit_events` and related log channels for AI usage and abuse monitoring.

## Usage
- Web UI mounts under `/addons/talent-ai/*` for authenticated users, with Blade views under `resources/views/talent_ai/...` and admin entries injected into admin layouts.
- API endpoints are namespaced under `/api/addons/talent-ai/*` and enforce Sanctum auth plus module flags.
- Flutter addon screens live under `Ai-Headhunter-E_Launchpad-flutter_addon/lib/ui/talent_ai.dart` with feature-flagged menu entries from `lib/menu/menu.dart`.
- Ensure module flags are enabled and routes are behind appropriate policies/guards before exposing menu links in the host app.

## Flutter Addon
The Flutter package mirrors the Laravel API:
- Models and API services for headhunters, launchpad, AI workspace, and volunteering live under `lib/models/talent_ai_models.dart` and `lib/services/talent_ai_api.dart`.
- State management (`lib/state/talent_ai_state.dart`) and screens (`lib/ui/talent_ai.dart`) provide mobile workflows, with analytics hooks in `lib/analytics/talent_ai_analytics.dart`.
- Import `lib/menu/menu.dart` to surface feature-flagged navigation entries in the host mobile app.

## Support
Review `docs/TECHNICAL_MAP.md` for a developer-focused map of namespaces, routes, and UI components. Honor the `AGENTS.md` guidance for scope, safety, and symmetry between web and mobile.
