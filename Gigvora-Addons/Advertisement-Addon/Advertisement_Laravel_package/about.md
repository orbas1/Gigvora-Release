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
- **Navigation placement:** The Gigvora navigation exposes an **Ads Manager** dropdown (visible for `manage_advertisement` users) with links to Ads Overview, Campaigns, and Ads Reports. Icons mirror the Flutter shell’s `campaign_outlined`, `list_alt_outlined`, and `analytics_outlined` set for consistency.
- **Icons & styling:** Use the host icon set (e.g., Font Awesome) and Gigvora button/card classes; the dashboard view now pulls its scripts through Laravel Mix (`mix('js/advertisement/dashboard.js')`) to stay on-brand with the host build pipeline. Mix entrypoints include `resources/js/advertisement/dashboard.js`, with matching placeholders ready for campaigns, creatives, reports, keyword planner, forecast, and admin screens.
- **Shared components:** Reusable ad placements live under `advertisement::components.*` (feed card, banner, search result) and ship with Gigvora-scoped utility classes (`gigvora-ad*`) plus ARIA labels so host surfaces can include them without bespoke styling or accessibility regressions. Feed/profile/search surfaces include these partials via `AdvertisementSurfaceService`.
- **Stylesheet:** `mix('css/advertisement/addon.css')` namespaces every ads view behind `.ads-shell`, ensuring typography, spacing, and button styles mirror Sociopro while preventing global overrides.

## Mobile Integration
- **Add-on dependency:** `advertisement_flutter_addon` added via path dependency in the Sociopro/Gigvora Flutter shell (`../Gigvora-Addons/Advertisement-Addon/Advertisement_Flutter_addon`).
- **API base:** Points to the same Gigvora Laravel host at `/api/advertisement/*` with bearer token injection via the shell’s auth repository.
- **Navigation:** Mobile routes mirror web: `/ads/home`, `/ads/campaigns`, `/ads/campaigns/:id`, `/ads/campaigns/create`, `/ads/creatives`, `/ads/keyword-planner`, `/ads/forecast`, `/ads/reports`, surfaced under the **Ads Manager** menu with matching icons.
- **Providers:** `GigvoraAddonProviders.ads` (see `Sociopro Flutter Mobile App/App/lib/addons_integration.dart`) wires the Campaign, Creative, Analytics, Forecast, Keyword Planner, and Affiliate blocs so screens render live data.

## Database Schema & Seeders
- **Tables:** advertisers (links to `users` + optional `affiliate_id`), campaigns → ad_groups → creatives, placements, targeting_rules, metrics, forecasts, keyword_prices, affiliate_referrals, and affiliate_payouts. Campaign status/placement/approval columns and related status fields are indexed for fast dashboards, with placements uniquely keyed by name and metrics indexed by `campaign_id` + `recorded_at`.
- **Foreign keys:** advertisers, affiliate_referrals, and affiliate_payouts enforce relationships to the core `users` table; campaign trees cascade on delete; placement/channel rows stay constrained via ad group/creative relationships.
- **Seeders:** `Database\Seeders\AdvertisementSeeder` seeds safe defaults for placements and deterministic keyword pricing and is wired into the host `DatabaseSeeder` so `php artisan db:seed` pulls addon data automatically. Re-running the seeder is safe because `firstOrCreate` guards deduplicate rows.
- **Indexes & ordering:** every migration ships with a `2024_01_01_*` prefix so the addon schema lands after base Sociopro tables. Status/date columns, FK pairs, and placement names are indexed to keep `/addons/advertisement/*` dashboards and `/api/advertisement/*` endpoints performant.
