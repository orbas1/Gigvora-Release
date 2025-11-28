# functions.md

## Overview
This package delivers a full events and media stack for webinars, networking sessions, podcast series/episodes, and interview scheduling. It is designed for LinkedIn-style platforms that need live sessions, recordings, and attendee workflows across Laravel (web + API) and the Flutter addon.

## Architecture & Modules
- **Laravel**
  - Controllers: REST APIs for webinars, networking, podcasts, interviews plus Blade page controllers for catalogues, detail pages, waiting rooms, and live shells.
  - Models: Eloquent models for Webinars, NetworkingSessions/Participants, PodcastSeries/Episodes, Interviews/Slots/Scores, Tickets, Recordings.
  - Policies: Role-aware access rules for hosts, admins, interviewers, and attendees.
  - Support: Feed transformers, analytics dispatcher, and a generic search service.
- **Flutter**
  - Services: `webinar_service.dart`, `networking_service.dart`, `podcast_service.dart`, `interview_service.dart` backed by `wnip_api_client.dart`.
  - State: `webinar_state.dart`, `networking_state.dart`, `podcast_state.dart`, `interview_state.dart` manage loading/error/empty states.
  - UI: Screens under `lib/src/pages/**` for listings, details, waiting rooms, and live shells, navigated via `menu.dart`.

Core flow bullets:
- Webinars: list → detail → register → waiting room → live shell → replay.
- Networking: list → detail/register → waiting room → live rotation and notes.
- Podcasts: catalogue → series detail → episode playback.
- Interviews: schedule list → detail → waiting room → scoring.

## Functions & Features (Laravel)
### Webinars
- **Routes**
  - `GET /events/webinars` → `WebinarPageController@index` (filters: `q`, `upcoming`, `past`, `paid`).
  - `GET /events/webinars/{webinar}` → `WebinarPageController@show` (details + recordings + registration state).
  - `POST /events/webinars/{webinar}/register` → `WebinarPageController@register` (auth, optional `ticket_id`).
  - `GET /events/webinars/{webinar}/waiting-room` → `WebinarPageController@waitingRoom` (countdown + announcement).
  - `GET /events/webinars/{webinar}/live` → `WebinarPageController@live` (host/attendee shell).
  - API: CRUD + register + toggle live via `WebinarController` under `/api/wnip/webinars*` (Sanctum auth).
- **Data**: Eager-load host/registrations, enforce policies, analytics events `webinar_created`, `webinar_started/ended`, `webinar_registered`.

### Networking Sessions
- **Routes**
  - `GET /events/networking` → `NetworkingPageController@index` (searchable list).
  - `GET /events/networking/{session}` → `NetworkingPageController@show` (participants + register CTA).
  - `POST /events/networking/{session}/register` → `NetworkingPageController@register` (auth required).
  - `GET /events/networking/{session}/waiting` → `NetworkingPageController@waitingRoom` (countdown, join button).
  - `GET /events/networking/{session}/live` → `NetworkingPageController@live` (rotation roster & notes shell).
  - API: CRUD/register/rotate at `/api/wnip/networking*` via `NetworkingController`.
- **Data**: Tracks `networking_session_created`, `networking_session_joined`, `networking_rotation_completed` analytics.

### Podcasts
- **Routes**
  - `GET /events/podcasts` → `PodcastPageController@index` (catalogue with latest episodes per series).
  - `GET /events/podcasts/{series}` → `PodcastPageController@show` (episode list + playback links).
  - API: series CRUD + episodes create/publish under `/api/wnip/podcast-series*` and `/api/wnip/podcasts`.
- **Data**: Analytics events `podcast_series_created`, `podcast_episode_created`, `podcast_episode_published`.

### Interviews
- **Routes**
  - `GET /events/interviews` → `InterviewPageController@index` (schedule list with search).
  - `GET /events/interviews/{interview}` → `InterviewPageController@show` (slots + scores + waiting link).
  - `GET /events/interviews/{interview}/waiting-room` → `InterviewPageController@waitingRoom` (countdown to start).
  - `POST /events/interviews/{interview}/slots/{slot}/score` → `InterviewPageController@score` (auth, criteria + scores arrays).
  - API: CRUD + slot add + score at `/api/wnip/interviews*` via `InterviewController`.
- **Data**: Analytics events `interview_scheduled`, `interview_joined`, `interview_scored`.

### Feed & Search Helpers
- `Support\Feed\FeedTransformer`: map Webinars, NetworkingSessions, PodcastEpisodes, and Interviews into `FeedItem` arrays (title, summary, URL, timestamp, meta). Use routes `wnip.webinars.show`, `wnip.networking.show`, `wnip.podcasts.series`, `wnip.interviews.show` for deep links.
- `Services\SearchService@search($query, $filters)`: unified LIKE-based search across titles/descriptions for all entities; returns paginated array with `title`, `type`, `description`, `date`, `id` for quick drop-in search endpoints or feed ranking.

## Functions & Features (Flutter)
- **Screens**
  - Webinars: `webinars_home_screen.dart` (tabs for upcoming/my/recordings), `webinar_detail_screen.dart` (register + navigate to waiting room), `webinar_waiting_room_screen.dart` (real countdown), `webinar_live_screen.dart`, `webinar_recording_player_screen.dart`.
  - Networking: `networking_home_screen.dart`, `networking_session_detail_screen.dart` (register + waiting room), `networking_waiting_room_screen.dart` (start-aware countdown), `networking_live_screen.dart`.
  - Podcasts: `podcast_catalogue_screen.dart`, `podcast_series_detail_screen.dart`, `podcast_episode_player_screen.dart`, `podcast_live_recording_screen.dart`.
  - Interviews: `interview_schedule_screen.dart`, `interview_detail_screen.dart`, `interview_waiting_room_screen.dart`, `interviewer_panel_screen.dart`, `interview_live_screen.dart`.
- **Services/State**
  - `wnip_api_client.dart` consumes Laravel endpoints with auth headers and structured error handling.
  - Service wrappers (`webinar_service.dart`, `networking_service.dart`, `podcast_service.dart`, `interview_service.dart`) provide typed methods that populate view states.
  - States (`webinar_state.dart`, `networking_state.dart`, `podcast_state.dart`, `interview_state.dart`) expose loading/error/empty/data patterns for the screens.
- **Navigation**
  - `menu.dart` registers `/live/...` routes. Waiting room routes now accept maps containing `title`, `startsAt` (DateTime), optional `message`, and `isLive` to render real-time countdowns.

## Integration Guide – Feed & Search
- **Feed**: Use `FeedTransformer` to convert models to `FeedItem` arrays. Example:
  ```php
  use Jobi\WebinarNetworkingInterviewPodcast\Support\Feed\FeedTransformer;
  $items = [
      FeedTransformer::fromWebinar($webinar)->toArray(),
      FeedTransformer::fromNetworking($session)->toArray(),
  ];
  ```
  Render these in your platform feed to highlight upcoming webinars, networking sessions, published podcast episodes, and interview invites.
- **Search**: Inject `Services\SearchService` and call `search($query, ['per_page' => 20])` to return a mixed list across titles/topics/speakers. Plug this into Laravel Scout/Meilisearch by indexing the same fields (`title`, `description`, `starts_at/published_at/scheduled_at`).

## Integration Guide – Analytics
- Backend: call `Support\Analytics\Analytics::track($event, $payload)` (already wired in controllers) to emit events such as `webinar_created`, `webinar_started/ended`, `webinar_registered`, `networking_session_created/joined`, `networking_rotation_completed`, `podcast_series_created`, `podcast_episode_published`, `interview_scheduled`, `interview_joined`, `interview_scored`.
- Flutter: inject an analytics provider into your host app and subscribe to navigation hooks; waiting-room transitions and registration actions are ideal points to emit `webinar_attended`, `networking_session_joined`, `podcast_played`, `interview_joined` events.

## Security, Reliability & Performance Notes
- Policies guard create/update/delete, plus view restrictions for paid events and role-specific interview scoring.
- Web/API registration routes require auth and CSRF (web) or Sanctum (API). Inputs are validated for type, dates, and numeric bounds.
- Lists use pagination to avoid heavy payloads; controllers eager-load hosts/relations to reduce N+1 queries.
- Recording paths and pricing are configurable; keep media on the configured `recording.storage_disk`.
- Consider adding DB indexes on `starts_at`, `scheduled_at`, `published_at`, and `title` for larger datasets.

## Configuration & Environment
- `.env` keys: `WEBINAR_STREAM_PROVIDER`, `WEBINAR_RTMP_ENDPOINT`, `WEBINAR_ZOOM_*`, `WEBINAR_TEAMS_*`, `WEBINAR_MEDIA_DISK`, `WNIP_SEEDER_HOST_ID`.
- Config file: `config/webinar_networking_interview_podcast.php` toggles feature flags, permissions, pricing defaults, streaming providers, networking limits, interview scoring defaults, and recording storage.
- Publish assets: `php artisan vendor:publish --tag=wnip-config|wnip-views|wnip-lang|wnip-database`.
- Migrations are auto-loaded; ensure queues are configured if you extend analytics or recordings with jobs.

## Quick Start – Integration Steps
- **Laravel package**
  1. Install via Composer and register the service provider if not auto-discovered.
  2. Run `php artisan migrate` (or publish migrations if preferred).
  3. Publish config/views/lang as needed.
  4. Mount routes: the package registers `/events/*` (web) and `/api/wnip/*` (API). Protect with `auth` middleware in your app layout.
  5. Add menu links to `/events/webinars`, `/events/networking`, `/events/podcasts`, `/events/interviews`.
- **Flutter addon**
  1. Add dependency and import `webinar_networking_interview_podcast_flutter_addon.dart`.
  2. Instantiate `WnipApiClient(baseUrl: '<api-base>', tokenProvider: ...)` and pass to `buildAddonRoutes` + `buildMenuItems`.
  3. Register the provided routes inside your host router; ensure waiting room routes receive `title`, `startsAt`, and optional `message/isLive` maps.
  4. Hook your analytics provider into registration and playback screens using the navigation callbacks described above.
