# Functions & Integration Guide – Utilities Addon (Gigvora/Sociopro)

The Utilities addon is a shared support layer for the Gigvora/Sociopro platform. It provides notifications, bookmarks/saved items, calendar events, reminders, and quick tools that can be surfaced across Jobs, Freelance, Interactive, and core social modules. All routes and components reuse the host middleware, layouts, and authentication.

## Routing & Middleware Alignment
- **Web**: Prefixed by `/{config('pro_network_utilities_security_analytics.utilities.prefixes.web')}` (default `/utilities`) with middleware stack from `config('pro_network_utilities_security_analytics.utilities.middleware.web')` (`web`, `auth`, `verified`, `locale`).
- **API**: Prefixed by `/{config('pro_network_utilities_security_analytics.utilities.prefixes.api')}` (default `/api/utilities`) with middleware from `config('pro_network_utilities_security_analytics.utilities.middleware.api')` (`api`, `auth:sanctum`, `locale`).
- **Guards & limits**: Feature toggles live in `config/pro_network_utilities_security_analytics.php` under `features.utilities_*`; rate/quantity limits live under `utilities.limits` and retention under `utilities.retention`.

## Laravel Surface
- **Controllers**
  - `NotificationCenterController@index|paginate|markRead|markAllRead|clear` – user-scoped notifications.
  - `BookmarkController@toggle|list|destroy` – saved/bookmarked resources (jobs, posts, events, companies, freelancers, content IDs via morph map).
  - `CalendarController@index|store|update|destroy` – personal calendar entries and synced interview reminders.
  - `ReminderController@index|store|update|destroy|snooze` – lightweight reminders tied to user or resource type.
  - `UtilityToolsController@index|downloadCsv|exportToken|metrics` – quick tools, CSV exports, API tokens (admin-only when `can:manageUtilities`).
- **Routes**
  - Web: `/utilities/notifications`, `/utilities/notifications/read-all`, `/utilities/saved`, `/utilities/calendar`, `/utilities/reminders`, `/utilities/tools`.
  - API: `/api/utilities/notifications`, `/api/utilities/notifications/read`, `/api/utilities/bookmarks`, `/api/utilities/calendar`, `/api/utilities/reminders`, `/api/utilities/tools/*`.
- **Policies & permissions**
  - Users can only view/update their own notifications, bookmarks, reminders, calendar entries, and tokens.
  - Admin-only quick tools and platform metrics via `can:manageUtilities` or `role:platform_admin`.
- **Events & jobs**
  - Emit `utilities.notification.created`, `utilities.notification.read`, `utilities.bookmark.toggled`, `utilities.reminder.created`, `utilities.reminder.due`.
  - Queue workers send digest emails/in-app notifications using host channels; cleanup jobs trim old notifications/activity per retention config.

## Flutter Addon Surface
- **Models**: `NotificationItem`, `Bookmark`, `Reminder`, `CalendarEvent`, `QuickTool` under `flutter_addon/lib/models`.
- **Services**: `NotificationApi`, `BookmarkApi`, `ReminderApi`, `CalendarApi`, `UtilitiesToolsApi` under `flutter_addon/lib/services` using shared HTTP client/auth.
- **State**: ChangeNotifiers in `flutter_addon/lib/state/utilities_state.dart` expose loading/error/data for notifications, bookmarks, reminders, and calendar.
- **UI**: Widgets in `flutter_addon/lib/ui/utilities` – notification list + bell dropdown, bookmark toggle button, reminder cards, mini-calendar, quick actions panel. Navigation hooks live in `flutter_addon/lib/menu.dart` and `menu/routes.dart` to surface Notifications page, Saved items, and My Schedule.

## API Endpoints (JSON)
- `GET /api/utilities/notifications` – list notifications (filters: `unread`, `type`, `page`).
- `POST /api/utilities/notifications/read` – mark one or many as read; `POST /api/utilities/notifications/read-all` marks all.
- `DELETE /api/utilities/notifications` – clear user notifications (retention-friendly).
- `GET /api/utilities/bookmarks` – list saved items (filters: `type`, `resource_id`).
- `POST /api/utilities/bookmarks` – body `{type, resource_id}` to save/unsave (idempotent toggle) respecting `utilities.limits.saved_items`.
- `GET /api/utilities/calendar` – list calendar events (supports `range[start,end]`).
- `POST /api/utilities/calendar` – create event `{title, starts_at, ends_at, location?, notes?}`; `PUT /api/utilities/calendar/{event}` updates; `DELETE` removes.
- `GET /api/utilities/reminders` – list reminders; supports `status` filter (`pending`, `snoozed`, `sent`).
- `POST /api/utilities/reminders` – create reminder `{message, due_at, resource_type?, resource_id?}`; `PUT /api/utilities/reminders/{reminder}` updates; `DELETE` removes; `POST /api/utilities/reminders/{reminder}/snooze` defers.
- `GET /api/utilities/tools` – list quick tools available to user; `GET /api/utilities/tools/metrics` (admin), `POST /api/utilities/tools/export` (CSV export), `POST /api/utilities/tools/token` (generate API token – admin only).

## Cross-Module Hooks
- **Jobs**: job cards can call bookmark toggle endpoints; interview schedules can push `CalendarController` entries and reminders.
- **Freelance/Interactive**: saved gigs/posts reuse bookmarks; live/meeting reminders reuse reminders endpoints.
- **Notifications**: host modules push notifications via `NotificationCenterController` or events; mobile/web bell dropdown consumes the same API.

## Operational Checklist
- Configure feature flags in `config/pro_network_utilities_security_analytics.php` (`utilities_*`).
- Ensure middleware stacks reference host guards and locale; apply rate limits using `utilities.limits`.
- Wire policies so users can only see their own utilities data; enforce admin guard on quick tools/metrics.
- Schedule cleanup job to prune notifications/activity per `utilities.retention`.
- In Flutter, set base URL/auth token to match host and register utilities routes in app navigation.
