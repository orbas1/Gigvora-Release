# Security Settings

## Environment Variables and Routing
- `ADMIN_URL_PREFIX` (optional): set a custom admin path and mirror it in reverse proxies.
- Sanctum and HTTPS: enforce `SESSION_SECURE_COOKIE=true`, `SANCTUM_STATEFUL_DOMAINS` for trusted origins, and `APP_URL`/`FRONTEND_URL` over HTTPS only.
- Rate limiting: admin APIs run under `throttle:60,1`; adjust via `ADMIN_RATE_LIMIT` if extending middleware.
- Admin bootstrap requires `GIGVORA_ADMIN_PASSWORD` to seed the first admin account; do not use weak defaults. Provide the value at deploy time and rotate as needed.

## Authentication and Sessions
- Admin access requires `auth:sanctum`, email verification, and `admin` middleware. Shorten admin session TTLs via `SESSION_LIFETIME` for stricter control.
- Encourage 2FA for admins through the existing authentication stack; reject weak passwords using Laravel validators in onboarding forms.
- Lock screens and dashboards behind role checks so non-admins cannot hit `/api/admin/*` routes.

## Data Protection
- GDPR tooling lives behind `/api/admin/gdpr/users/{id}` and logs `gdpr.export` / `gdpr.erase` actions in `audit_logs`.
- Erasure anonymizes user profiles, drops posts/media/stories/live streams, and removes utilities calendar + notification records.
- Exports are raw JSON payloads; transport only over HTTPS and store securely if retained for review.

## Monitoring and Logging
- Audit logs capture admin actions, incidents, integration errors, and role changes. Filter via `/api/admin/audit-logs`.
- Queue health and media processing signals surface through `/api/admin/overview` and include counts for jobs, failed jobs, live sessions, reels vs long video, and utilities activity.
- Integration issues should be recorded to `AuditLog` with `integration.error`, `payment.error`, `streaming.error`, or `ai.error` actions for visibility.
- Content moderation runs on comments via `ContentModerationService`, escalating shadow bans and permanent bans for severe language while logging outcomes to `audit_logs`.

## Allowed File Types and Media Safety
- Upload validation leverages MIME checks for images/videos in `MainController` and `Media_files` metadata. Keep the FFmpeg/FFprobe binaries available for duration detection and transcoding.
- Respect the resolution ladder for stories/reels/live video and avoid bypassing server-side processing pipelines.

## CORS and Content Security
- Restrict CORS origins to trusted apps via `config/cors.php` and keep `ALLOW_ADMIN_IFRAMES` disabled.
- Block inline script/style injection in Blade templates; prefer tokenized components and compiled assets.

## Log Review Cadence
- Rotate application logs regularly and forward to the chosen aggregator (e.g., ELK). Include audit + queue status in weekly checks.
- During incidents, capture a dedicated `incident.report` entry and attach remediation notes in `docs/progress.md`.
