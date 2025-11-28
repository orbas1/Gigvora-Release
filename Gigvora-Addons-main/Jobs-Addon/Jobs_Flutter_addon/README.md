# Jobs Flutter Addon

Mobile addon that mirrors the Laravel jobs package for job listings, applications, ATS pipelines, CVs, cover letters, subscriptions, and interview scheduling. Wire it into your host Flutter app by creating a `JobsMenuBuilder` with the base URL for the Laravel API and embedding the provided screens.

## Getting started

```dart
final menu = JobsMenuBuilder(
  baseUrl: 'https://your-api.test',
  headers: {'Authorization': 'Bearer <token>'},
);
final tabs = menu.buildScreens();
```

The addon ships with repositories and models to call the Laravel package endpoints. Extend the UI or state notifiers to match your host design system, and feed `JobsApiClient` into your analytics or notification pipelines to keep live feeds and recommendations current.
