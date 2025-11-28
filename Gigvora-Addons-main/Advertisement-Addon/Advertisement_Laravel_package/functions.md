# Advertisement Backend Functions & Endpoints

## Web
- `GET /addons/advertisement/dashboard` → `view('advertisement::dashboard')`
  - Shows the advertiser dashboard surfaced in Sociopro; requires `web` + `auth` middleware and `advertisement.enabled` flag.

## API (all behind `api` + `auth:sanctum` and `advertisement.enabled`)
- `GET /api/advertisement/advertisers` → `AdvertiserController@index`
  - Lists advertisers for the authenticated user. Name prefix: `api.advertisement.advertisers.index`.
- `POST /api/advertisement/advertisers` → `AdvertiserController@store`
  - Creates an advertiser profile. Name prefix: `api.advertisement.advertisers.store`.
- `PUT /api/advertisement/advertisers/{advertiser}` → `AdvertiserController@update`
  - Updates advertiser details. Name prefix: `api.advertisement.advertisers.update`.
- `GET /api/advertisement/campaigns` → `CampaignController@index`
  - Lists campaigns with creatives and targeting metadata. Name prefix: `api.advertisement.campaigns.index`.
- `GET /api/advertisement/campaigns/{campaign}` → `CampaignController@show`
  - Shows a single campaign. Name prefix: `api.advertisement.campaigns.show`.
- `POST /api/advertisement/campaigns` → `CampaignController@store`
  - Creates a campaign respecting bidding and placement config. Name prefix: `api.advertisement.campaigns.store`.
- `PUT /api/advertisement/campaigns/{campaign}` → `CampaignController@update`
  - Updates campaign budgets/targets. Name prefix: `api.advertisement.campaigns.update`.
- `POST /api/advertisement/campaigns/{campaign}/forecast` → `CampaignController@forecast`
  - Runs forecasting against config costs. Name prefix: `api.advertisement.campaigns.forecast`.
- `GET /api/advertisement/creatives` → `CreativeController@index`
  - Lists creatives. Name prefix: `api.advertisement.creatives.index`.
- `POST /api/advertisement/creatives` → `CreativeController@store`
  - Stores a creative asset/definition. Name prefix: `api.advertisement.creatives.store`.
- `PUT /api/advertisement/creatives/{creative}` → `CreativeController@update`
  - Updates creative content. Name prefix: `api.advertisement.creatives.update`.
- `POST /api/advertisement/campaigns/{campaign}/targeting` → `TargetingController@store`
  - Saves targeting parameters for a campaign. Name prefix: `api.advertisement.targeting.store`.
- `GET /api/advertisement/campaigns/{campaign}/reports` → `ReportController@index`
  - Lists metrics for a campaign. Name prefix: `api.advertisement.reports.index`.
- `POST /api/advertisement/campaigns/{campaign}/reports` → `ReportController@store`
  - Records custom report snapshots. Name prefix: `api.advertisement.reports.store`.
- `POST /api/advertisement/keyword-planner` → `KeywordPlannerController` (invokable)
  - Calculates CPC/CPA/CPM keyword pricing. Name prefix: `api.advertisement.keyword_planner.store`.
- `GET /api/advertisement/affiliates/referrals` → `AffiliateController@referrals`
  - Retrieves referral records for affiliate partners. Name prefix: `api.advertisement.affiliates.referrals`.
- `POST /api/advertisement/affiliates/referrals` → `AffiliateController@storeReferral`
  - Creates a new referral. Name prefix: `api.advertisement.affiliates.referrals.store`.
- `POST /api/advertisement/affiliates/payouts` → `AffiliateController@requestPayout`
  - Requests affiliate payouts using config thresholds. Name prefix: `api.advertisement.affiliates.payouts.store`.
- `GET /api/advertisement/affiliates/payouts` → `AffiliateController@payouts`
  - Lists payout requests. Name prefix: `api.advertisement.affiliates.payouts`.

## Permissions & Policies
- Policies mapped for `Advertisement\Models\Campaign` via `CampaignPolicy`.
- `manage_advertisement` gate restricts administrative actions to Sociopro admins (`user_role === 'admin'`).
