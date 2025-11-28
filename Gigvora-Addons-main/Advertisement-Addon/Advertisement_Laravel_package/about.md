# Advertisement Laravel Addon — Gigvora Integration

This package is now wired into the Gigvora Laravel host through Composer path repositories and the registered `Advertisement\\Providers\\AdvertisementServiceProvider`. Routes are exposed under:

- Web: `/addons/advertisement/*` (e.g., `/addons/advertisement/dashboard`)
- API: `/api/advertisement/*` (authenticated via Sanctum)

Toggle availability with `config/advertisement.php` using the `enabled` flag and supporting env vars such as `ADS_*` and `AFFILIATE_*` for bidding, approvals, placements, and affiliate payouts.

Policies and the `manage_advertisement` gate leverage the Gigvora admin role so only administrators can manage sensitive operations while authenticated users access their own campaign workflows.

The addon is designed to interoperate with the Gigvora core feed, search, gigs, and jobs surfaces through placement definitions in the config file and Blade view hooks.

## Web UI & Branding
- **Base layout:** All views extend the Gigvora host layout (`layouts.app`) so headers, footers, and typography match the core experience.
- **Primary screen:** `/addons/advertisement/dashboard` renders the “Gigvora Ads Manager” dashboard with campaign, creative, targeting, and affiliate highlights.
- **Navigation placement:** Add a top-level **Ads Manager** entry in the Gigvora navigation with children for Campaigns, Creatives, Reports, Keyword Planner, Forecast, and Admin (visiblity controlled by `advertisement.enabled` and `manage_advertisement`).
- **Icons & styling:** Use the host icon set (e.g., Font Awesome) and Gigvora button/card classes; the dashboard view now pulls its scripts through Laravel Mix (`mix('js/advertisement/dashboard.js')`) to stay on-brand with the host build pipeline. Mix entrypoints include `resources/js/advertisement/dashboard.js`, with matching placeholders ready for campaigns, creatives, reports, keyword planner, forecast, and admin screens.
