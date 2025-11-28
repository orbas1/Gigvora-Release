# Advertisement Laravel Addon — Sociopro Integration

This package is now wired into the Sociopro Laravel host through Composer path repositories and the registered `Advertisement\\Providers\\AdvertisementServiceProvider`. Routes are exposed under:

- Web: `/addons/advertisement/*` (e.g., `/addons/advertisement/dashboard`)
- API: `/api/advertisement/*` (authenticated via Sanctum)

Toggle availability with `config/advertisement.php` using the `enabled` flag and supporting env vars such as `ADS_*` and `AFFILIATE_*` for bidding, approvals, placements, and affiliate payouts.

Policies and the `manage_advertisement` gate leverage the Sociopro admin role so only administrators can manage sensitive operations while authenticated users access their own campaign workflows.

The addon is designed to interoperate with the Sociopro core feed, search, gigs, and jobs surfaces through placement definitions in the config file and Blade view hooks.
