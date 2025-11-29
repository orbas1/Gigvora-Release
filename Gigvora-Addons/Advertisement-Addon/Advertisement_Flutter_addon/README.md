# Advertisement Flutter Addon

A production-ready Flutter addon that mirrors the Laravel advertisement package. It provides full advertiser workflows (campaigns, creatives, targeting, analytics, forecasting, keyword planner, affiliate referrals/payouts) with navigation hooks for both web and phone apps.

## Features
- Campaign CRUD with ad groups, creatives, placements, budgets, schedules, and targeting.
- Metrics dashboard with impressions, clicks, conversions, CTR, CPC, CPA, CPM, and cost over time.
- Forecasting and keyword planner with CPC/CPA/CPM pricing.
- Affiliate tracking for referrals and payouts.
- Ready-made navigation menu entries for quick host app integration.
- Bloc-based state management and repository layer over HTTP API.

## Getting Started
1. Add the package via a path dependency:
```yaml
  advertisement_flutter_addon:
    path: ../Advertisement_package_addon/Advertisement_Flutter_addon
```

2. Configure the API client:
```dart
final api = AdvertisementApiClient(
  baseUrl: 'https://your-app.test',
  tokenProvider: () async => authRepository.currentToken,
);
final repository = AdvertisementRepository(api: api);
```

3. Provide blocs/notifiers in your app root:
```dart
MultiBlocProvider(
  providers: [
    BlocProvider(create: (_) => CampaignBloc(repository)),
    BlocProvider(create: (_) => CreativeBloc(repository)),
    BlocProvider(create: (_) => AnalyticsBloc(repository)),
    BlocProvider(create: (_) => ForecastBloc(repository)),
    BlocProvider(create: (_) => KeywordPlannerBloc(repository)),
    BlocProvider(create: (_) => AffiliateBloc(repository)),
  ],
  child: AdsDashboardPage(menu: defaultAdsMenu),
);
```

4. Plug navigation into your host app via `menu.dart`:
```dart
for (final item in defaultAdsMenu) {
  hostNavigation.register(item.title, item.builder);
}
```

## API Expectations
The addon expects the Laravel advertisement package routes:
- `api/advertisement/advertisers`, `campaigns`, `ad-groups`, `creatives`, `placements`, `targeting`, `metrics`, `forecasts`, `keyword-prices`, `affiliate/referrals`, `affiliate/payouts`.
- Bearer token authentication is required. Supply tokens through `tokenProvider`.

## Running Tests
```
flutter test
```

## Production Readiness Checklist
- ✅ Strongly typed models with JSON parsing.
- ✅ Repository and service layers with retry-friendly helpers and auth token injection.
- ✅ UI screens for campaigns, creatives, analytics, forecast, keyword planner, and affiliates.
- ✅ Navigation menu entries for both phone and web builds.
- ✅ Compatible with the Laravel advertisement package shipped in this repository.
