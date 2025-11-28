# Sociopro Flutter Mobile App Integration

The core Sociopro Flutter shell can consume the Gigvora addons shipped in this repository without changing their code. Wire the phone app to the Laravel host (`Sociopro`) and the Flutter addon packages as follows:

## Addon dependencies
Add the advertisement and Talent & AI Flutter addons as local path dependencies in your app `pubspec.yaml`:

```yaml
dependencies:
  advertisement_flutter_addon:
    path: ../Gigvora-Addons-main/Advertisement-Addon/Advertisement_Flutter_addon
  pro_network_utilities_security_analytics:
    path: ../Gigvora-Addons-main/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-flutter_addon
```

## API client wiring
Both addons expect the Sociopro Laravel host to expose the following Sanctum-protected endpoints:

- Advertisement: `/api/advertisement/*`
- Talent & AI: `/api/addons/talent-ai/*` (headhunter, launchpad, volunteering, AI workspace)

Instantiate each client with the Sociopro base URL and an auth token provider:

```dart
final adsApi = AdvertisementApiClient(
  baseUrl: 'https://your-sociopro-host.com',
  tokenProvider: () async => authRepository.currentToken,
);

final talentApi = BaseApiService(
  baseUrl: 'https://your-sociopro-host.com',
  tokenProvider: () async => authRepository.currentToken,
);
```

## Navigation hooks
Expose the addon menus inside your app shell:

```dart
for (final item in defaultAdsMenu) {
  navigationRegistry.register(item.title, item.builder);
}

final talentMenu = buildTalentAiMenu(analyticsClient);
for (final item in talentMenu) {
  navigationRegistry.register(item.title, item.builder);
}
```

This keeps the phone app aligned with the Sociopro backend while preserving the addon isolation model.
