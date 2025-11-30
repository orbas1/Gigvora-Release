# Gigvora Mobile Addon Integration (Flutter)

The Sociopro Flutter shell integrates Gigvora-branded addons for Advertisement and Talent & AI using the existing addon packages in this repository. The steps below align navigation, API clients, theming, and analytics with the Gigvora Laravel backend.

## Path dependencies
Add all addons as local dependencies in your app `pubspec.yaml` (see `App/pubspec.yaml` for a ready-to-use example):

```yaml
dependencies:
  advertisement_flutter_addon:
    path: ../Gigvora-Addons/Advertisement-Addon/Advertisement_Flutter_addon
  talent_ai_flutter_addon:
    path: ../Gigvora-Addons/Ai-Headhunter-Launchpad-Addon/Ai-Headhunter-E_Launchpad-flutter_addon
  freelance_phone_addon:
    path: ../Gigvora-Addons/Freelance-Addon/freelance_phone_addon
  webinar_networking_interview_podcast_flutter_addon:
    path: ../Gigvora-Addons/Interactive-Addon/Webinar_networking_interview_and_Podcast_Flutter_addon
  pro_network_utilities_security_analytics:
    path: ../Gigvora-Addons/Utilities-Addon/flutter_addon
  flutter_bloc: ^8.1.3
  provider: ^6.0.5
  flutter_riverpod: ^2.5.1
  http: ^0.13.6
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
- Freelance: `/freelance/onboarding`, `/freelance/freelancer/dashboard`, `/freelance/freelancer/gigs`, `/freelance/freelancer/gig-orders`, `/freelance/freelancer/projects`, `/freelance/freelancer/proposals`, `/freelance/freelancer/contracts`, `/freelance/client/dashboard`, `/freelance/client/projects`, `/freelance/client/contracts`, `/freelance/escrow`, `/freelance/disputes`, `/freelance/dispute`, `/freelance/review`, `/freelance/contract`.
- Live & Events: `/live/webinars`, `/live/webinars/:id`, `/live/webinars/waiting/:id`, `/live/webinars/live`, `/live/webinars/recording/:id`, `/live/networking`, `/live/networking/:id`, `/live/networking/waiting/:id`, `/live/networking/live`, `/live/podcasts`, `/live/podcasts/series/:id`, `/live/podcasts/episode/:id`, `/live/podcasts/live`, `/live/interviews`, `/live/interviews/:id`, `/live/interviews/waiting/:id`, `/live/interviews/live`, `/live/interviews/interviewer/:id`.

```dart
final routes = {
  ...GigvoraAddonNavigation.routes(
    advertisementFlags: AdvertisementFeatureFlags(enabled: config.adsEnabled),
    flags: TalentAiFeatureFlags(
      headhunters: config.headhuntersEnabled,
      launchpad: config.launchpadEnabled,
      aiWorkspace: config.aiWorkspaceEnabled,
      volunteering: config.volunteeringEnabled,
    ),
    apis: talentApis,
    freelance: FreelanceIntegrationOptions(
      enabled: config.freelanceEnabled,
      baseUrl: gigvoraBaseUrl,
      apiPrefix: 'api/freelance',
      tokenProvider: () => authRepository.currentToken ?? '',
      showFreelancerMenu: currentUser.roles.contains('freelancer'),
      showClientMenu: currentUser.roles.contains('client'),
    ),
    live: LiveEventsIntegrationOptions(
      enabled: config.liveEventsEnabled,
      baseUrl: gigvoraBaseUrl,
      apiPrefix: 'api/live',
      tokenProvider: () => authRepository.currentToken,
      includeInterviewsInMainNav: config.showInterviewsInLiveNav,
      includeCandidateInterviewMenu: currentUser.roles.contains('candidate'),
      includeEmployerInterviewMenu: currentUser.roles.contains('employer'),
    ),
  ),
  ...hostRoutes,
};
```

Menu labels/icons mirror the Laravel web menus:

- **Ads Manager**, **Campaigns**, **Ads Reports** (Material icons: `campaign_outlined`, `list_alt_outlined`, `analytics_outlined`).
- **Talent & AI** with children **Headhunters** (`work_outline`), **Experience Launchpad** (`school_outlined`), **AI Workspace** (`smart_toy_outlined`), **Volunteering** (`volunteer_activism_outlined`).
- **Freelance** entries mirror the Laravel workspace: **Freelance Setup**, **Freelance Dashboard**, **My Gigs**, **Browse Projects**, **My Proposals**, **Contracts**, **Client Dashboard**, **Client Projects**, **Client Contracts**, **Escrow**, **Disputes** (see `freelance_phone_addon/lib/src/menu.dart` for the full icon list).
- **Live & Events** mirrors the Interactive addon: **Webinars**, **Networking**, **Podcasts**, and optional **Interviews** (candidate/employer panels) driven by `LiveAddonIntegration` menu helpers and the Gigvora feature flags so events sit inside the same drawer/tab styling as Sociopro.

Feature flags from the backend (`advertisement.enabled`, `gigvora_talent_ai.enabled`, `freelance.enabled`, and nested module flags) should hide corresponding menu entries.

```dart
final addonMenuEntries = GigvoraAddonNavigation.menuItems(
  advertisementFlags: AdvertisementFeatureFlags(enabled: config.adsEnabled),
  talentAiFeatureFlags: TalentAiFeatureFlags(
    headhunters: config.headhuntersEnabled,
    launchpad: config.launchpadEnabled,
    aiWorkspace: config.aiWorkspaceEnabled,
    volunteering: config.volunteeringEnabled,
  ),
  freelance: FreelanceIntegrationOptions(
    enabled: config.freelanceEnabled,
    baseUrl: gigvoraBaseUrl,
    tokenProvider: () => authRepository.currentToken ?? '',
    showFreelancerMenu: currentUser.roles.contains('freelancer'),
    showClientMenu: currentUser.roles.contains('client'),
  ),
  live: LiveEventsIntegrationOptions(
    enabled: config.liveEventsEnabled,
    baseUrl: gigvoraBaseUrl,
    tokenProvider: () => authRepository.currentToken,
    includeCandidateInterviewMenu: currentUser.roles.contains('candidate'),
    includeEmployerInterviewMenu: currentUser.roles.contains('employer'),
  ),
);
```

The freelance routes rely on Riverpod `ProviderScope` overrides (base URL, API prefix, auth token, optional timeout). `GigvoraAddonNavigation.routes` handles the wrapping, but you must expose a synchronous `tokenProvider` that returns the latest bearer token from secure storage or memory to avoid awaiting async work on every HTTP call. Live & Events routes use `LiveAddonIntegration.createClient` behind the scenes, so give it the same base URL + `/api/live` prefix and a `FutureOr<String?> Function()` token provider—waiting rooms, live shells, and player screens keep the host `ThemeData` via the add-on’s `LiveMobileTheme`.

## Providers & theming
Wrap the root app with addon providers so state and routes are available everywhere. `App/lib/addons_integration.dart` exposes helpers:

- `GigvoraAddonProviders.ads(...)` – creates `BlocProvider`s for Campaign, Creative, Analytics, Forecast, Keyword Planner, and Affiliate flows.
- `GigvoraAddonProviders.talentAi(...)` – creates `ChangeNotifierProvider`s for Headhunters, Launchpad, AI Workspace, and Volunteering.
- `GigvoraAddonNavigation.routes(...)` – merges the addon route maps with your existing router.

All addon screens inherit the host `ThemeData` colours and typography; avoid overriding fonts or colours to stay on-brand.

## Shared navigation config (API-driven)

Web + Flutter now share the same IA exposed from Laravel via `GET /api/navigation`. Use `GigvoraNavigationClient` (exported from `App/lib/gigvora_navigation.dart`) to hydrate mobile tabs/drawers so Jobs, Freelance, Ads, Talent & AI, Live/Interactive, Utilities, and Admin links always mirror the web header.

```dart
final navClient = GigvoraNavigationClient(
  baseUrl: gigvoraBaseUrl,
  tokenProvider: () async => authRepository.currentToken,
);

final navConfig = await navClient.fetch();

return Scaffold(
  body: currentBody,
  bottomNavigationBar: BottomNavigationBar(
    currentIndex: state.index,
    items: navConfig.toBottomNavItems(),
    onTap: (index) => controller.onTabSelected(navConfig.mobile.tabs[index]),
  ),
  drawer: GigvoraDrawer(
    sections: navConfig.drawerSections(),
    onNavigate: controller.handleDrawerTap,
  ),
);
```

Each nav item includes the same semantic icon key used on the web (`home`, `briefcase`, `handshake`, `broadcast`, etc.), so calling `gigvoraNavIcon(item.icon)` renders a consistent icon-first UI. The client handles authentication and timeouts; just pass the base URL of your Laravel host and the bearer token provider.

### Utilities addon widgets (Flutter)

Reuse the packaged `pro_network_utilities_security_analytics` addon for notifications, bookmarks, calendar/reminders, and the floating utilities bubble in Flutter:

```dart
dependencies:
  pro_network_utilities_security_analytics:
    path: ../Gigvora-Addons/Utilities-Addon/flutter_addon
```

```dart
import 'package:pro_network_utilities_security_analytics/pro_network_utilities_security_analytics.dart';

final utilitiesMenu = proNetworkMenuItems(
  baseUrl: gigvoraBaseUrl,
  tokenProvider: () async => authRepository.currentToken,
);
```

Wire the provided menu items into your drawer/bottom sheets so Utilities (notifications, saved, reminders, quick tools) appear beside Jobs/Freelance/Live/Ads/Talent tabs.

## Analytics & security
Forward addon events into the host analytics layer via `TalentAiAnalyticsClient` and your existing analytics hooks (e.g., `ads_campaign_created`, `headhunter_pipeline_stage_moved`, `ai_tool_ran`, `launchpad_applied`, `volunteering_applied`). AI calls remain server-side; no AI keys are stored on device.
