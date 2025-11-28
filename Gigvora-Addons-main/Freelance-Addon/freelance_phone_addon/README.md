# Freelance Phone Addon (Flutter)

This Flutter package mirrors the Taskup freelance experience that was originally delivered in React Native. It exposes screens, providers, and models to plug into a host Flutter application while talking to the Laravel freelance API.

## Getting Started

1. Add the package as a local dependency in your host `pubspec.yaml`:

```yaml
dependencies:
  freelance_phone_addon:
    path: ../freelance_package/freelance_phone_addon
```

2. Wrap your application with a `ProviderScope` if it is not already using Riverpod.
3. Inject your API base URL (including the `/api/` prefix) and an auth token provider when building the menu entries:

```dart
final menuEntries = buildFreelanceMenuEntries(
  baseUrl: 'https://your-domain.com/api/',
  tokenProvider: () => authState.token,
);
```

4. Add the generated `FreelanceMenuEntry` widgets to your navigation shell. Each entry bootstraps its own `ProviderScope` with the injected overrides so that API calls are authenticated against the Laravel freelance package.

5. Use the repository helpers for **search** (`searchFreelance`) and **personalised recommendations** (`fetchRecommendations`) to integrate freelance content into your home feed or global search surfaces. Both methods reuse the same auth/token overrides so results stay personalised and access-controlled.

## Features

- Gig and project browsing with filters, favourites, and detail views.
- Project bidding workflow including amount and optional cover letter.
- Project management board (tasks, milestones, submissions, hourly logs, invitations, reviews).
- Gig management console (timeline, FAQs, add-ons, packages, requirements, change requests, reviews, custom gigs).
- Dispute creation, stage tracking (initial → mediation → refunds → arbitration), and listing with basic filtering.
- Escrow status overview plus partial release/admin management utilities.
- Profile tagging (freelancer tags, gig tags, skills) with helpers to fetch and update the shared taxonomy, including admin tag maintenance endpoints.
- Profile enrichment endpoints for project portfolios, education/qualifications, and profile reviews with average rating helpers.
- Reusable repository and API client that map to the Laravel freelance endpoints, including feed-friendly search and recommendation helpers.

## Notes

- Network failures surface through `FreelanceApiException`; handle these at the application level if you want custom UI.
- Models follow the Laravel API responses; adjust them if the backend payloads change.
- The package avoids any binary assets to keep it lightweight and portable.
- The API client enforces a 20s timeout by default and normalises base URLs with and without trailing slashes to avoid subtle
  production misconfigurations.
