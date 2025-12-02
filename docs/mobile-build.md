# Mobile Build Guide

This guide explains how to configure and build the Flutter mobile shell so it remains in lockstep with the Laravel host and addon APIs.

## Prerequisites

- Flutter SDK 3.x with Dart 3.x.
- Access to the Laravel API base URL over HTTPS (same host used by the web app).
- Addon packages are pulled locally from `Gigvora-Addons/*` per `Gigvora Flutter Mobile App/App/pubspec.yaml`.

## Configuration

1. **API base URL + auth**
   - Supply the host API base URL and bearer token provider when constructing the shared clients:
     - `GigvoraNavigationClient(baseUrl: ..., tokenProvider: ...)` for `/api/navigation`.
     - `GigvoraQuickToolsClient(baseUrl: ..., tokenProvider: ...)` for `/utilities/quick-tools`.
     - Addon configurators in `GigvoraAddonNavigation.routes` accept `baseUrl` and token providers for Jobs, Freelance, Ads, Interactive, and Talent & AI so every screen calls live services.
   - Keep tokens in secure storage on the host app before passing them into these constructors; do not hard-code secrets.

2. **Environment variables / dart-defines**
   - `BASE_URL` (required): HTTPS host that serves the Laravel APIs (same as web). Inject via `--dart-define BASE_URL=https://your-host` and read it in your app entrypoint before building clients.
   - `PUSH_PROJECT_ID` / `FCM_SENDER_ID` (if applicable): consumed by the native runners only; register the resulting device token against `/notifications` using the authenticated API token.
   - Optional feature toggles can be passed through `--dart-define` to match backend flags (e.g., advertisement enabled, talent AI feature flags) but **must** reflect live server config—avoid enabling screens without backend support.

3. **Feature flags**
   - Advertisement routes are enabled through `AdvertisementFeatureFlags` and the `advertisement.enabled` backend flag.
   - Jobs and Freelance menus depend on `JobsIntegrationOptions`/`FreelanceIntegrationOptions` (e.g., `showEmployerMenu`, `showSeekerMenu`). Ensure these reflect the user role/permissions resolved on the web side.

3. **Addons**
   - Dependencies live in the monorepo and are referenced via relative paths in `Gigvora Flutter Mobile App/App/pubspec.yaml`. Keep the monorepo layout intact so `flutter pub get` can locate advertisement, jobs, freelance, live (webinar/networking/interview/podcast), talent AI, and utilities addons.
   - The same HTTP clients used in the host shell are forwarded into addon constructors (see `GigvoraAddonNavigation.routes`). Do not substitute mock repositories; all screens should hit the Laravel endpoints listed in `logic_flows.md` and addon `functions.md` files.

## Build commands

Run these from `Gigvora Flutter Mobile App/App` after configuring base URLs/tokens in your app entrypoint:

- Fetch dependencies: `flutter pub get`
- Static analysis: `flutter analyze`
- Android debug: `flutter run --dart-define BASE_URL=https://your-host`
- Android release: `flutter build apk --release --dart-define BASE_URL=https://your-host`
- iOS release (on macOS): `flutter build ipa --release --dart-define BASE_URL=https://your-host`

Ensure the app entrypoint reads `BASE_URL` (or your preferred define) and passes it into the navigation/quick tools/addon clients above so every route hits the live Laravel endpoints.

## Push notifications

Use the Utilities addon push/notification pipeline from the web stack. Register the device token after sign-in and send it to the Laravel notifications service; the same auth token used for content APIs should be used for notification registration. Keep platform-specific setup (APNs/FCM) in your native runner and avoid storing keys in the Dart layer.

## Deep links & offline states

- **Deep links**: Wire your `onGenerateRoute` to the combined route map from `GigvoraAddonNavigation.routes` plus nav API outputs so links to feed posts, profiles, jobs, freelance projects, live events, stories/reels, and utilities all land on live-backed screens. Validate both cold-start and warm-path behavior.
- **Offline handling**: The feed/comments/jobs/freelance clients expect live responses; add retry/backoff at the app layer and surface offline indicators. Do not return placeholder data—if offline, block the action and retry once connectivity resumes.
