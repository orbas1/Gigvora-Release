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

2. **Feature flags**
   - Advertisement routes are enabled through `AdvertisementFeatureFlags` and the `advertisement.enabled` backend flag.
   - Jobs and Freelance menus depend on `JobsIntegrationOptions`/`FreelanceIntegrationOptions` (e.g., `showEmployerMenu`, `showSeekerMenu`). Ensure these reflect the user role/permissions resolved on the web side.

3. **Addons**
   - Dependencies live in the monorepo and are referenced via relative paths in `Gigvora Flutter Mobile App/App/pubspec.yaml`. Keep the monorepo layout intact so `flutter pub get` can locate advertisement, jobs, freelance, live (webinar/networking/interview/podcast), talent AI, and utilities addons.

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
