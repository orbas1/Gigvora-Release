# Talent & AI Laravel Package

This package provides the Laravel backend and web UI for the Talent & AI addon, covering Headhunters, Experience Launchpad, AI Workspace, and Volunteering. It is intended to be installed into a Gigvora-based host app and paired with the Flutter addon when mobile support is required.

## Getting Started
- Require the package via Composer and ensure `Gigvora\\TalentAi\\Providers\\TalentAiServiceProvider` is loaded (auto-discovery is enabled).
- Configure `config/gigvora_talent_ai.php` for module flags, AI provider options (BYOK/platform keys), pricing tiers, and guardrails.
- Run `php artisan migrate` to install addon tables and include the JS/CSS assets in your Vite/Mix build if you expose the provided Blade views.
- Web routes live under `/addons/talent-ai/*` with Sanctum-protected API routes at `/api/addons/talent-ai/*`; all are gated by the `enabled` flag and per-module toggles.

## Structure
- **Domain**: Models and services under `src/Domain/*` for each pillar (headhunter, launchpad, AI workspace, volunteering).
- **HTTP**: Controllers and requests under `src/Http` with policies in `src/Policies` registered by the service provider.
- **UI**: Blade views in `resources/views/talent_ai`, JS in `resources/js/addons/talent_ai`, styles in `resources/css/addons/talent_ai`, and translations under `resources/lang`.
- **Admin**: Settings, plan management, moderation screens, and an admin sidebar partial exposed via `talent_ai::admin.partials.menu`.

Refer to the repository-level `README.md` and `docs/TECHNICAL_MAP.md` for full context and integration notes.
