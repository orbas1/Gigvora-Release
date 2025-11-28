# Advertisement Laravel Package

This package extracts the Sngine advertisement logic into a reusable Laravel module with campaign management, creatives, placements, targeting, reporting, forecasting, keyword pricing, and affiliate tracking for both web and mobile clients.

## Installation

1. Add the package to `composer.json` using a path repository:

```json
{
  "repositories": [
    {"type": "path", "url": "../Advertisement_Laravel_package"}
  ],
  "require": {
    "advertisement/package": "*@dev"
  }
}
```

2. Run `composer install` and ensure the provider is auto-discovered or register `Advertisement\\Providers\\AdvertisementServiceProvider` manually.
3. Publish assets and config:

```bash
php artisan vendor:publish --tag=advertisement-config
php artisan vendor:publish --tag=advertisement-views
php artisan vendor:publish --tag=advertisement-seeders
```

4. Run migrations and seeders:

```bash
php artisan migrate
php artisan db:seed --class=AdvertisementSeeder
```

## Features

- Advertisers, campaigns, ad groups, creatives, placements, targeting, metrics, and forecasts.
- Pricing models for CPC, CPM, and CPA with keyword planner support.
- Campaign forecasting service built from Sngine campaign logic.
- Affiliate referrals and payout requests mirroring the Sngine affiliate bootstrap hooks.
- API and web routes guarded by policies with default dashboard view.

## API Overview

- `GET /api/advertisement/campaigns` – list campaigns with creatives and metrics.
- `POST /api/advertisement/campaigns` – create campaigns with validation matching Sngine checks.
- `POST /api/advertisement/campaigns/{campaign}/forecast` – forecast reach/clicks/conversions.
- `POST /api/advertisement/keyword-planner` – price keywords (CPC/CPA/CPM).
- `POST /api/advertisement/affiliates/referrals` – register affiliate referrals tied to campaigns.
- `POST /api/advertisement/affiliates/payouts` – request payouts with thresholds from config.

## Frontend Integration

- Blade dashboard at `/advertisement/dashboard` summarizing ads, forecasts, and affiliate information.
- API ready for Flutter addon consumption with consistent JSON payloads.

## Configuration

All tunables live in `config/advertisement.php` and mirror Sngine defaults for cost-per-view, cost-per-click, approval toggles, placements, and affiliate commission thresholds.
