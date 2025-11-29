# Gigvora Mobile Addon Integration (Flutter)

The Sociopro Flutter shell integrates Gigvora-branded addons for Advertisement and Talent & AI using the existing addon packages in this repository. The steps below align navigation, API clients, theming, and analytics with the Gigvora Laravel backend.

## Path dependencies
Add both addons as local dependencies in your app `pubspec.yaml` (see `App/pubspec.yaml` for a ready-to-use example):

```yaml
dependencies:
  advertisement_flutter_addon:
    path: ../Gigvora-Addons-main/Advertisement-Addon/Advertisement_Flutter_addon
  talent_ai_flutter_addon:
    path: ../Gigvora-Addons-main/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-flutter_addon
  flutter_bloc: ^8.1.3
  provider: ^6.0.5
```

Run `flutter pub get` after adding the paths.

## API client wiring
Point both addons at the Gigvora Laravel host (`https://your-gigvora-host.com`) and reuse the shell auth token provider:

```dart
final tokenProvider = () async => authRepository.currentToken;
final adsApi = AdvertisementApiClient(
  baseUrl: gigvoraBaseUrl,
  tokenProvider: tokenProvider,
);
final talentApis = TalentAiApis.fromBaseUrl(
  baseUrl: gigvoraBaseUrl,
  tokenProvider: tokenProvider,
);
```

## Navigation & routes
Merge addon routes into the host router:

- Advertisement: `/ads/home`, `/ads/campaigns`, `/ads/campaigns/:id`, `/ads/campaigns/create`, `/ads/creatives`, `/ads/keyword-planner`, `/ads/forecast`, `/ads/reports`.
- Talent & AI: `/talent-ai/headhunters`, `/talent-ai/headhunters/mandates/:id`, `/talent-ai/launchpad`, `/talent-ai/launchpad/:id`, `/talent-ai/launchpad/applications/:id`, `/talent-ai/ai-workspace`, `/talent-ai/volunteering`, `/talent-ai/volunteering/:id`.

Menu labels/icons mirror the Laravel web menus:

- **Ads Manager**, **Campaigns**, **Ads Reports** (Material icons: `campaign_outlined`, `list_alt_outlined`, `analytics_outlined`).
- **Talent & AI** with children **Headhunters** (`work_outline`), **Experience Launchpad** (`school_outlined`), **AI Workspace** (`smart_toy_outlined`), **Volunteering** (`volunteer_activism_outlined`).

Feature flags from the backend (`advertisement.enabled`, `gigvora_talent_ai.enabled`, and nested module flags) should hide corresponding menu entries.

## Providers & theming
Wrap the root app with addon providers so state and routes are available everywhere. `App/lib/addons_integration.dart` exposes helpers:

- `GigvoraAddonProviders.ads(...)` – creates `BlocProvider`s for Campaign, Creative, Analytics, Forecast, Keyword Planner, and Affiliate flows.
- `GigvoraAddonProviders.talentAi(...)` – creates `ChangeNotifierProvider`s for Headhunters, Launchpad, AI Workspace, and Volunteering.
- `GigvoraAddonNavigation.routes(...)` – merges the addon route maps with your existing router.

All addon screens inherit the host `ThemeData` colours and typography; avoid overriding fonts or colours to stay on-brand.

## Analytics & security
Forward addon events into the host analytics layer via `TalentAiAnalyticsClient` and your existing analytics hooks (e.g., `ads_campaign_created`, `headhunter_pipeline_stage_moved`, `ai_tool_ran`, `launchpad_applied`, `volunteering_applied`). AI calls remain server-side; no AI keys are stored on device.
