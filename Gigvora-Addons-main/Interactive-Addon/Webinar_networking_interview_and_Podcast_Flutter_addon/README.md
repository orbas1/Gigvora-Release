# Webinar, Networking, Interview & Podcast Flutter Addon

This package provides the Flutter/mobile counterpart to the Laravel `webinar_networking_interview_podcast` package. It exposes API clients, screens, and navigation menu entries that mirror the webinar, networking, podcast, and interview flows defined by the Laravel routes under the `/wnip` prefix.

## Installation

Add the package as a local dependency in your host Flutter project:

```yaml
dependencies:
  webinar_networking_interview_podcast_flutter_addon:
    path: ../LaravelAndPhonePackages/Webinar_networking_interview_and_Podcast_Flutter_addon
```

Ensure your Flutter SDK meets the constraints in `pubspec.yaml`.

## Usage

1. **Create an API client** that points to the backend base URL and optional bearer token provider:

```dart
final client = WnipApiClient(
  baseUrl: 'https://your-api.test/api',
  tokenProvider: () async => authRepository.accessToken,
);
```

2. **Wire menu entries** into your host navigation:

```dart
final items = buildAddonMenu(client);
// Use items to build tabs, a drawer, or route table.
```

3. **Push screens directly** if you only need a subset:

```dart
Navigator.of(context).push(
  MaterialPageRoute(
    builder: (_) => WebinarListPage(apiClient: client),
  ),
);
```

## Feature Coverage

- **Webinars:** list, detail, registration, live toggle, recording display.
- **Networking:** list, session detail, participant registration, rotation trigger.
- **Podcasts:** series list, series detail with episodes, episode publish hook.
- **Interviews:** list, detail with slots and scores, score submission form.

All network calls map to the Laravel package endpoints and include pagination support where provided by the backend.
