# Agent Instructions – Webinar, Networking, Interview & Podcast Package
_UI, Views & Screens Specification (Laravel + Flutter)_

## Overall Goal

Build a complete **Live & Events** experience for:

1. **Laravel web app** (Blade views + JS), and  
2. **Flutter mobile addon** (`webinar_networking_interview_podcast_flutter_addon`),

covering:

- Webinars (live & recorded)
- Networking sessions (including speed networking)
- Podcasts (live & on-demand)
- Interviews (scheduling, scoring, multi-person)

This must work for:

- **Hosts** (create/manage events, go live, moderate)
- **Attendees** (discover, register, join, replay)
- **Admins** (moderate and configure)

> ⚠️ Do **not** add or touch any binary files (images, fonts, compiled bundles, `.exe`, `.dll`, `.so`, `.apk`, `.ipa`, etc.). Only templates, Dart/JS/TS, and CSS/SCSS.

---

## 1. Laravel Web – Blade Views, JS & Admin

All module views should live under:

- User-facing: `resources/views/vendor/live/`  
- Admin-facing: `resources/views/vendor/live/admin/`  
- Shared components: `resources/views/vendor/live/components/`

### 1.1 Webinars – User-Facing Blade Views

**1.1.1 Webinars Landing / Catalogue**

- **File**: `live/webinars/index.blade.php`
- **Purpose**: Discover upcoming & past webinars.
- **Content**:
  - Hero section:
    - Title: “Webinars”
    - Filters: Upcoming / Past / My Webinars.
    - CTA button: “Host a Webinar”.
  - Filters:
    - Search by title/host.
    - Category dropdown.
    - Date range filter.
    - Price: Free / Paid.
  - List/grid of webinar cards:
    - Thumbnail (from non-binary assets or placeholder).
    - Title, host name, date/time.
    - Tag: Free / Paid.
    - Status: Scheduled / Live / Finished.
    - “View Details” button.

- **JS**:
  - AJAX filtering & pagination.
  - “Live now” badges updated periodically via small polling or websocket hook.

---

**1.1.2 Webinar Detail & Registration Page**

- **File**: `live/webinars/show.blade.php`
- **Purpose**: Show full details and allow registration.
- **Content**:
  - Header:
    - Title, host name, profile snippet.
    - Date & time (auto converted to user’s timezone).
    - Duration (if specified).
  - Info sections:
    - Description (HTML-safe).
    - Agenda / bullet points.
    - Speakers list (names, titles).
  - Registration panel (right side or top):
    - If not registered:
      - Button: “Register” (primary).
      - Price display (Free or £X).
    - If registered:
      - Status: “You’re registered”.
      - “Join Waiting Room” button (when time is near).
  - Social sharing links (optional).
  - Past recordings list (if series).

- **JS**:
  - “Register” button triggers AJAX call; updates UI to “Registered”.
  - If event is about to start, show countdown timer (`webinarCountdown.js`).

---

**1.1.3 Webinar Waiting Room**

- **File**: `live/webinars/waiting_room.blade.php`
- **Purpose**: Lobby before host starts the webinar.
- **Content**:
  - Header: Webinar title, host, start time.
  - Countdown timer.
  - Message area: host announcements.
  - Connectivity checks:
    - Simple status like “You’re good to go” (no real device access here; this is UX only).
  - Button: “Open Live Session” (becomes active when host starts).

- **JS**:
  - Countdown timer.
  - Polling / websocket hook to check when session goes live, then enable “Enter Webinar” button.
  - Optional toast message when live.

---

**1.1.4 Webinar Live Session Page (Front-end Shell)**

> Note: This page is a **host UI shell** where actual video/RTC widget is embedded.

- **File**: `live/webinars/live.blade.php`
- **Purpose**: In-session view for webinar host/attendees.
- **Content**:
  - Layout: two columns (or full-width with side panel).
  - Main area:
    - Video/stream container `<div id="webinar-video-container"></div>` for integration.
    - Optional whiteboard/drawing canvas `<div id="webinar-whiteboard"></div>`.
  - Side panel:
    - Live chat feed.
    - Q&A list (questions submitted by attendees).
    - Attendees count.
  - Bottom toolbar:
    - For host: Start/End session, Toggle recording, Mute all (if supported), Share screen (hook button only).
    - For attendee: Leave, Mute/unmute (controlled via client integration).

- **JS**:
  - `webinarLive.js`:
    - Connect to streaming/RTC integration (via host app’s JS hooks).
    - Live chat (send/receive messages).
    - Q&A submission.
    - Event handlers for host actions (start/stop, toggle record).
  - Update attendees count in real time.

---

**1.1.5 Webinar Recordings Catalogue & Player**

- **File**: `live/webinars/recordings.blade.php`
- **Purpose**: Browse and watch recorded webinars.
- **Content**:
  - Filters: search, category, host, date range.
  - List of recordings:
    - Title, date, duration, tags.
    - “Watch Replay” button.
  - Player page:
    - (Nested) `live/webinars/recording_show.blade.php`
    - Video player container.
    - Chapters (timestamps).
    - Resource links (slides, notes).

- **JS**:
  - Video playback controls (scrub, speed).
  - Chapters navigation.

---

### 1.2 Networking – User-Facing Blade Views

**1.2.1 Networking Landing Page**

- **File**: `live/networking/index.blade.php`
- **Purpose**: Discover and join networking sessions.
- **Content**:
  - Hero: “Networking Sessions”.
  - Filters: Speed Networking / Group Networking, Free / Paid.
  - List of sessions:
    - Title, host, date/time, type (2 min or 5 min rotations).
    - Spots available.
    - “View Details” button.
    - “Join Now” for live sessions.

- **JS**:
  - AJAX filter.
  - Live sessions pinned to top via periodic refresh.

---

**1.2.2 Networking Session Detail & Registration**

- **File**: `live/networking/show.blade.php`
- **Content**:
  - Title, host, type (speed, duration).
  - Description & goal (e.g. “Fintech founders networking”).
  - Schedule info.
  - Registration panel:
    - “Register / Join waitlist” depending on capacity.
    - Business card preview (name, headline, company).
  - Section for “what to expect”.

- **JS**:
  - Register button via AJAX.
  - If at capacity, show “Join waitlist”.

---

**1.2.3 Networking Waiting Room**

- **File**: `live/networking/waiting_room.blade.php`
- **Content**:
  - Session title, countdown timer.
  - Participant list (just names + city/role).
  - Option to edit business card (headline, bio, links).
  - “Join Session” button (activates when live).

- **JS**:
  - Countdown timer.
  - Polling for session start.
  - Save business card via AJAX.

---

**1.2.4 Networking Live Session (Speed Networking UI Shell)**

- **File**: `live/networking/live.blade.php`
- **Purpose**: Speed networking room with timed rotations.
- **Content**:
  - Main area:
    - Placeholder container(s) for video/chat tiles (`#networking-video-container`).
  - Top bar:
    - Current round: “Round 2 of 6”.
    - Timer countdown (2 or 5 minutes).
  - Side panel:
    - “Your partner’s card”: name, role, links, notes field.
    - “Next up” hint (optional).
  - Bottom bar:
    - Buttons: “Skip Round” (if allowed), “Report”, “Leave Session”.

- **JS**:
  - `networkingLive.js`:
    - Timer countdown per round.
    - Receive pairing info (who you’re currently matched with).
    - On round end, show “Rotating…” screen while new pairing loads.
    - Persist per-partner notes (client-side and/or via API).
  - Websocket or polling to receive pairings and round status.

---

### 1.3 Podcasts – User-Facing Blade Views

**1.3.1 Podcast Catalogue**

- **File**: `live/podcasts/index.blade.php`
- **Content**:
  - Search, category filter, hosts filter.
  - Grid/list of podcast series:
    - Cover (non-binary placeholder), title, tagline, host.
  - “Start Listening” button → series detail.

---

**1.3.2 Podcast Series Detail**

- **File**: `live/podcasts/series_show.blade.php`
- **Content**:
  - Header: series cover, title, description, host info.
  - “Follow” button.
  - Episodes list:
    - Title, duration, publish date, tags.
    - “Play” button (inline player or route to episode page).

- **JS**:
  - Play inline audio without leaving page if desired.
  - Follow/unfollow via AJAX.

---

**1.3.3 Podcast Episode Player**

- **File**: `live/podcasts/episode_show.blade.php`
- **Content**:
  - Player:
    - Play/pause, scrub, playback speed, elapsed time.
  - Episode info:
    - Title, description, guests, show notes.
  - Related episodes or “More from this series”.

- **JS**:
  - Audio playback logic.
  - Remember last position (optional) with local storage.

---

**1.3.4 Live Podcast Recording UI (Host Shell)**

- **File**: `live/podcasts/live.blade.php`
- **Purpose**: Shell for host + guests to record a live podcast.
- **Content**:
  - Audio/RTC container.
  - Host tools:
    - Start/stop recording.
    - Mute guests.
    - Show notes editor.
  - Timing indicator.

- **JS**:
  - `podcastLive.js` for session control and integration with RTC/recording.

---

### 1.4 Interviews – User-Facing Blade Views

**1.4.1 Interview Dashboard (Candidate View)**

- **File**: `live/interviews/candidate_dashboard.blade.php`
- **Content**:
  - Upcoming interviews list:
    - Date/time, company, role, status (Confirmed/Pending).
    - “View Details” button.
  - Past interviews list (optional).
  - Calendar view (month/week) with interview slots.

- **JS**:
  - Calendar rendering using existing calendar JS.
  - Click on slot → open detail modal/page.

---

**1.4.2 Interview Detail (Candidate)**

- **File**: `live/interviews/candidate_show.blade.php`
- **Content**:
  - Role/title, company, interviewers.
  - Date/time, duration, interview type (video/in-person).
  - Joining link/button: “Join Interview”.
  - Special instructions.
  - Attachments: job description, documents.

- **JS**:
  - If within allowable window, enable “Join Waiting Room”.

---

**1.4.3 Interview Waiting Room**

- **File**: `live/interviews/waiting_room.blade.php`
- **Content**:
  - Countdown timer.
  - Basic instructions and etiquette tips.
  - “Enter Interview” button.

- **JS**:
  - Countdown.
  - Check when host opens room.

---

**1.4.4 Interview Live Session (Candidate Shell)**

- **File**: `live/interviews/live_candidate.blade.php`
- **Content**:
  - Video container.
  - Minimal controls: mute, camera on/off, leave.
  - No scoring or criteria visible.

- **JS**:
  - Integration with interview RTC provider.

---

**1.4.5 Interviewer Panel – Interview Detail & Scoring**

- **File**: `live/interviews/interviewer_show.blade.php`
- **Content**:
  - Interview summary: candidate, role, time.
  - Tabs:
    - “Overview”
    - “Criteria & Scoring”
    - “Notes”
  - **Criteria & Scoring**:
    - Matrix table:
      - Rows: criteria (Communication, Problem Solving, etc.).
      - Columns: Score (e.g. 1–5), Comments.
    - Overall recommendation (hire/hold/reject).
  - **Notes**:
    - Free-form notes area.

- **JS**:
  - Inline scoring form with autosave.
  - Ability to lock scores after interview.

---

### 1.5 Admin Views (Webinars, Networking, Podcasts, Interviews)

All admin views under `live/admin/`.

**1.5.1 Admin Live & Events Dashboard**

- **File**: `live/admin/dashboard.blade.php`
- **Content**:
  - Global stats:
    - Number of upcoming webinars/networking events/interviews.
    - Total hours of recorded content.
    - Attendance stats.
  - Recent events requiring review.
  - Error/issue logs (e.g. failed recordings).

---

**1.5.2 Admin Webinars Management**

- **List**:
  - `live/admin/webinars/index.blade.php`
  - Columns: title, host, date/time, status, registrations, actions.
- **Detail**:
  - `live/admin/webinars/show.blade.php`
  - See all registrations, attendance logs, recording status.
- **Actions**:
  - Force close webinar.
  - Approve/Reject recordings for catalogue.

---

**1.5.3 Admin Networking Management**

- **List**:
  - `live/admin/networking/index.blade.php`
- **Detail**:
  - `live/admin/networking/show.blade.php`
- **Data**:
  - Participants list, pairing logs, complaints/reports.

---

**1.5.4 Admin Podcasts Management**

- **Series list**: `live/admin/podcasts/series_index.blade.php`
- **Series detail**: `live/admin/podcasts/series_show.blade.php`
- **Episodes**:
  - Approve/reject episodes.
  - Mark explicit, feature, etc.

---

**1.5.5 Admin Interviews Management**

- **List**:
  - `live/admin/interviews/index.blade.php`
- **Detail**:
  - `live/admin/interviews/show.blade.php`
- **Functions**:
  - Override scheduling.
  - Ensure data/minimum criteria are present.
  - Export scoring results.

---

### 1.6 Shared Components (Blade Partials)

Under `live/components/`:

- `waiting_room_header.blade.php`
- `event_card.blade.php`
- `host_tools_toolbar.blade.php`
- `live_chat_panel.blade.php`
- `notes_sidebar.blade.php`
- `calendar_widget.blade.php`

---

### 1.7 JavaScript Modules (Laravel Side)

Under `resources/js/live/`:

- `webinarCatalogue.js`
  - Filters & pagination for webinars.
- `webinarDetail.js`
  - Registration, countdowns.
- `webinarLive.js`
  - Live chat, Q&A, integration hooks, live UI state.
- `networkingCatalogue.js`
  - Session filters & status.
- `networkingLive.js`
  - Timers, pairing updates, note-taking client side.
- `podcastPlayer.js`
  - Audio control, position saving.
- `interviewDashboard.js`
  - Candidate calendar & list UI.
- `interviewerScoring.js`
  - Criteria matrix autosave, lock state.
- `adminLiveDashboard.js`
  - Metrics & charts for admin.

---

## 2. Flutter Mobile – Screens, Widgets, State & Menu

All mobile code lives in `webinar_networking_interview_podcast_flutter_addon`.

### 2.1 Structure

- `lib/webinar_networking_interview_podcast_flutter_addon.dart`
  - Exports routes and root widgets.
- `lib/src/pages/`
- `lib/src/models/`
- `lib/src/services/`
- `lib/src/state/`
- `lib/src/widgets/`
- `lib/src/menu.dart`

---

### 2.2 Webinars – Mobile Screens

**2.2.1 WebinarsHomeScreen**

- **File**: `lib/src/pages/webinars/webinars_home_screen.dart`
- **Purpose**: Landing with tabs for Upcoming, My Webinars, Recordings.
- **Functions**:
  - Fetch lists from API.
  - Tap on item → detail.
  - CTA: “Host Webinar”.

- **UI**:
  - `AppBar(title: Text('Webinars'))`
  - `TabBar`: Upcoming, My, Recordings.
  - `ListView` of cards (title, host, date, status pill).

---

**2.2.2 WebinarDetailScreen**

- **File**: `webinar_detail_screen.dart`
- **Functions**:
  - Fetch webinar details, register/unregister, open waiting room.
- **UI**:
  - Scroll view: title, host, date/time, description, speakers.
  - Bottom fixed button:
    - “Register” / “Registered” / “Join Waiting Room”.

---

**2.2.3 WebinarWaitingRoomScreen**

- **File**: `webinar_waiting_room_screen.dart`
- **Functions**:
  - Show countdown timer and join button.
  - Listen for live-start event (polling/websocket).
- **UI**:
  - Big countdown text.
  - Host message card.
  - “Enter Webinar” button.

---

**2.2.4 WebinarLiveScreen**

- **File**: `webinar_live_screen.dart`
- **Functions**:
  - Display embedded video widget (from host app).
  - Show chat panel.
  - Show Q&A section (tabs).
  - Host-only tools: start/stop, record toggle (if host).
- **UI**:
  - Portrait:
    - Top: video player (flex 2).
    - Bottom: tabbed “Chat / Q&A”.
  - Landscape:
    - Left: video player.
    - Right: chat/Q&A.

---

**2.2.5 WebinarRecordingPlayerScreen**

- **File**: `webinar_recording_player_screen.dart`
- **Functions**:
  - Play recorded webinar.
  - Seek, adjust playback speed.
- **UI**:
  - Video player widget.
  - Episode info section.
  - List of related recordings.

---

### 2.3 Networking – Mobile Screens

**2.3.1 NetworkingHomeScreen**

- **File**: `networking_home_screen.dart`
- **Functions**:
  - List upcoming and live sessions.
  - Filter by type/duration.
- **UI**:
  - TabBar: Upcoming, My Sessions, Past.
  - Cards: title, host, date/time, type.

---

**2.3.2 NetworkingSessionDetailScreen**

- **File**: `networking_session_detail_screen.dart`
- **Functions**:
  - Show details; register; edit business card.
- **UI**:
  - Scrollable info view.
  - Business card preview block.
  - “Register / Join Waiting Room” button.

---

**2.3.3 NetworkingWaitingRoomScreen**

- **File**: `networking_waiting_room_screen.dart`
- **Functions**:
  - Countdown.
  - Edit card (name, role, headline).
- **UI**:
  - Countdown.
  - Editable fields in `Card`.
  - “Save Card” + “Join Session” button.

---

**2.3.4 NetworkingLiveScreen**

- **File**: `networking_live_screen.dart`
- **Functions**:
  - Display partner info.
  - Timer per round (2 or 5 minutes).
  - Rotate partner at round end.
  - Note-taking per partner.
- **UI**:
  - Top: round indicator + timer.
  - Middle: partner card (name, role, links).
  - Bottom:
    - Notes `TextField`.
    - Buttons: “Next”, “Report”, “Leave”.

---

### 2.4 Podcasts – Mobile Screens

**2.4.1 PodcastCatalogueScreen**

- **File**: `podcast_catalogue_screen.dart`
- **Functions**:
  - List podcast series with filters.
- **UI**:
  - Search bar.
  - `ListView` of series cards.

---

**2.4.2 PodcastSeriesDetailScreen**

- **File**: `podcast_series_detail_screen.dart`
- **Functions**:
  - Show series info and episodes.
  - Follow/unfollow series.
- **UI**:
  - Series header.
  - Follow button.
  - `ListView` of episodes.

---

**2.4.3 PodcastEpisodePlayerScreen**

- **File**: `podcast_episode_player_screen.dart`
- **Functions**:
  - Play audio, pause, resume, seek.
  - Remember last position.
- **UI**:
  - Audio player widget (play/pause button, slider, time).
  - Episode details and show notes.

---

**2.4.4 PodcastLiveRecordingScreen (Host)**

- **File**: `podcast_live_recording_screen.dart`
- **Functions**:
  - Start/stop live recording.
  - Manage guests (mute).
- **UI**:
  - Large “Record / Stop” button.
  - Guest list with toggles.

---

### 2.5 Interviews – Mobile Screens

**2.5.1 InterviewScheduleScreen (Candidate)**

- **File**: `interview_schedule_screen.dart`
- **Functions**:
  - List upcoming and past interviews.
- **UI**:
  - `TabBar`: Upcoming / Past.
  - `ListView` of cards: role, company, date/time, status.

---

**2.5.2 InterviewDetailScreen (Candidate)**

- **File**: `interview_detail_screen.dart`
- **Functions**:
  - Show interview details.
  - Offer “Join Waiting Room” / “Join Interview”.
- **UI**:
  - Info list (role, interviewer(s), date/time).
  - Instructions.
  - Join button at bottom.

---

**2.5.3 InterviewWaitingRoomScreen**

- **File**: `interview_waiting_room_screen.dart`
- **Functions**:
  - Countdown.
  - Show instructions.
- **UI**:
  - Centered countdown.
  - Text instructions.

---

**2.5.4 InterviewLiveScreen (Candidate)**

- **File**: `interview_live_screen.dart`
- **Functions**:
  - Show video call UI container from host app.
- **UI**:
  - Video layout + minimal controls (mute/camera/leave).

---

**2.5.5 InterviewerPanelScreen**

- **File**: `interviewer_panel_screen.dart`
- **Functions**:
  - Show criteria matrix.
  - Allow scoring and notes.
- **UI**:
  - List of criteria with sliders or dropdown scores.
  - Comment fields.
  - Save/Lock button.

---

### 2.6 Flutter Menu & Navigation

**File**: `lib/src/menu.dart`

Expose items like:

- `MenuItem('Webinars', route: '/live/webinars', icon: Icons.videocam_outlined)`
- `MenuItem('Networking', route: '/live/networking', icon: Icons.people_outline)`
- `MenuItem('Podcasts', route: '/live/podcasts', icon: Icons.podcasts)`
- `MenuItem('Interviews', route: '/live/interviews', icon: Icons.event_note_outlined)`

Also export a `Map<String, WidgetBuilder>`:

- `/live/webinars`
- `/live/webinars/:id`
- `/live/networking`
- `/live/networking/:id`
- `/live/podcasts`
- `/live/podcasts/series/:id`
- `/live/podcasts/episode/:id`
- `/live/interviews`
- `/live/interviews/:id`
- And relevant live/waiting room routes.

Host app will integrate these into its main navigation (tabs/drawer).

---

### 2.7 Styling & UX (Web + Mobile)

**General**:

- Reuse platform’s **design system**:
  - Same fonts, colours, spacing.
- **Typography**:
  - Headline for page titles.
  - Medium for section headings.
  - Regular for content.
- **Colours**:
  - Brand primary for CTAs and active states.
  - Neutral backgrounds (#F7F7F7 / #FFFFFF).
- **Spacing**:
  - Use consistent spacing scale: 8/12/16/24.
- **Buttons**:
  - Primary: solid, full-width on mobile where important.
  - Secondary: outlined/link style.

**Web Layout**:

- For dashboards: two-column layout on desktop; stack on mobile.
- For live sessions: responsive (video area prioritised).

**Mobile Layout**:

- Use `Scaffold` with `AppBar` and optional `BottomNavigationBar`.
- Avoid more than 2 nested scrollables.
- Use `SafeArea` for notch devices.

---

## 3. Interactivity, CRUD & Logic Flows

### 3.1 CRUD Behaviour

**Webinars**

- Create/Edit:
  - Host creation forms (web/mobile).
- Read:
  - Listings, detail, recordings.
- Update:
  - Edit details, schedule, host.
- Delete/Archive:
  - Archive webinars when finished (keep recordings).

**Networking Sessions**

- Create/Edit:
  - Host defines type (speed), rotation duration, capacity.
- Read:
  - List and detail views.
- Update:
  - Adjust time, capacity if not started.
- Delete/Archive:
  - Allow cancel (with notifications).

**Podcasts**

- Create:
  - Create series, then episodes.
- Read:
  - Catalogue, series, episodes.
- Update:
  - Edit titles, descriptions, show notes.
- Delete/Archive:
  - Archive episodes/series.

**Interviews**

- Create:
  - HR/host schedules interviews with participants.
- Read:
  - Candidate sees schedule; interviewer sees scoring panels.
- Update:
  - Reschedule, update participants.
- Delete/Archive:
  - Archive completed interviews with scores attached.

---

### 3.2 Core Logic Flows

**Flow A – Join a Webinar**

1. User browses Webinars List → selects event.
2. On detail page, clicks “Register”.
3. Closer to start time, “Join Waiting Room” appears.
4. Waiting room shows countdown; when live, “Enter Webinar” activates.
5. User joins `WebinarLive` UI and interacts via chat/Q&A.
6. After event, recording appears in recordings catalogue.

**Flow B – Speed Networking Session**

1. User registers for a networking session.
2. At start time, they enter waiting room, finalise business card.
3. Session begins → user enters live screen.
4. Each round:
   - Timer starts (2 or 5 mins).
   - Partner info displayed.
   - User can take notes.
5. At timer end, system rotates pairings automatically.
6. At end of session, user can export/see list of partners encountered.

**Flow C – Listen to a Podcast**

1. User visits Podcasts catalogue → chooses series.
2. On series page, user follows the show.
3. User selects an episode → opens Episode Player.
4. Plays episode; app remembers position.
5. User can resume later from last position.

**Flow D – Candidate Interview Journey**

1. Candidate sees upcoming interview in Interview Schedule.
2. Opens detail page → reviews info and instructions.
3. At time, enters waiting room.
4. From waiting room, joins live interview UI.
5. After interview, candidate can optionally see status updates in schedule.

**Flow E – Interviewer Scoring**

1. Interviewer opens Interviewer Panel for specific candidate.
2. During or immediately after interview, fills in scoring matrix and notes.
3. Scores autosave; interviewer can lock final decision.
4. Admin/HR can view aggregated scores later.

**Flow F – Admin Monitoring**

1. Admin opens Live & Events dashboard.
2. Can check upcoming webinars/networking sessions.
3. Opens specific event management pages to view participants, logs.
4. Uses lists to resolve issues (e.g. failed recordings, flagged behaviour).

---

By following this specification, the agent must:

- Implement **all listed Blade views**, partials, and JS behaviour for the Laravel package.
- Implement **all listed Flutter screens**, widgets, and state logic in the mobile addon.
- Ensure smooth **navigation, CRUD operations, and user flows** for webinars, networking, podcasts, and interviews.
- Respect existing **design system** while providing a polished, production-ready Live & Events experience across web and mobile.
