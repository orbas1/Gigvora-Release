# Functions & Integration Guide – Jobs / ATS Package

## Overview
This package delivers a full-featured Jobs & ATS module for a LinkedIn-style platform, providing job search, employer posting, ATS pipeline management, interview scheduling, CV/cover-letter handling, and subscription tracking. The Laravel package exposes REST + Blade experiences, and the Flutter addon surfaces mobile-ready screens backed by the same APIs.

## Architecture & Modules
- **Laravel**
  - Controllers in `src/Http/Controllers` cover jobs, applications, ATS, screening, interviews, subscriptions, CVs, and cover letters.
  - Models in `src/Models` encapsulate jobs, companies, candidates, bookmarks, ATS stages, and interview schedules with relationships and computed fields (e.g., `Job::$salary_label`, `Job::$tag_list`).
  - Support utilities: analytics dispatcher (`src/Support/Analytics/JobsAnalytics.php`), feed transformer (`src/Support/Feed/JobFeedTransformer.php`), and search helper (`src/Support/Search/JobSearchService.php`).
  - Views under `resources/views/vendor/jobs` implement search, detail, apply, saved, and ATS UI components.
- **Flutter**
  - Models under `lib/src/models` mirror Laravel payloads (jobs, companies, applications, CVs, cover letters, screening, ATS, subscriptions, interviews).
  - Services/repositories in `lib/src/services` and `lib/src/data` call Laravel APIs with consistent parsing and error propagation.
  - State notifiers in `lib/src/state` manage loading/error state and refresh flows.
  - Widgets in `lib/src/widgets` deliver list/detail/apply forms, ATS boards, interview scheduling, subscriptions, CV builder, and cover letter editor screens.

### Core Flows
- **Job seeker search & apply**: `JobController@index` → Blade search (`jobs/index.blade.php`) or Flutter `JobListScreen` → `JobDetailScreen` → apply via web wizard or Flutter `ApplicationFormPage` using `/api/applications`.
- **Employer job posting**: `JobController@store/update/destroy` handles CRUD with analytics; views in `vendor/jobs/employer` consume the same endpoints.
- **ATS management**: `AtsController@pipeline/stages/move` + Flutter ATS widgets keep candidates moving across stages with analytics hooks.
- **Interviews**: `InterviewController@index/store` lists and schedules interviews; Flutter `InterviewSchedulePage` consumes `/api/applications/{id}/interviews`.

## Functions & Features (Laravel)
- **Jobs**
  - `GET /jobs` → `JobController@index` (HTML or JSON with filters: `search`, `location`, `employment_type`, `workplace_type`, `featured`, `posted`, `per_page`).
  - `GET /jobs/{job}` → `JobController@show` (HTML detail or JSON with company, applications, screening questions).
  - `GET /jobs/{job}/apply` → `JobController@apply` renders multi-step apply wizard.
  - `POST /jobs/{job}/apply` → `ApplicationController@storeForJob` (web form submission).
  - `POST/DELETE /jobs/{job}/save` + `GET /jobs/saved` → toggle and list bookmarks.
  - `GET /jobs/{job}/similar` → similar roles (company/location based).
  - `POST /api/jobs` `PUT /api/jobs/{job}` `DELETE /api/jobs/{job}` → create/update/close jobs (analytics: `job_posted`, `job_updated`, `job_closed`).
- **Applications & ATS**
  - `GET /api/applications` (filters: `company_id`, `candidate_id`, `status`, `per_page`).
  - `POST /api/applications` and `POST /api/jobs/{job}/applications` create applications (auto `applied_at`, default status, optional screening answers) firing `job_applied` and `screening_questions_answered`.
  - `GET /api/applications/{jobApplication}` returns application detail with job, candidate, answers, interviews.
  - `PUT /api/applications/{jobApplication}` updates status/notes (fires `application_status_changed`).
  - `POST /api/applications/{jobApplication}/withdraw` marks withdrawn.
  - `POST /api/applications/{jobApplication}/ats/move` moves stage with audit + analytics.
- **Screening**
  - `GET /api/jobs/{job}/screening` list questions; `POST /api/jobs/{job}/screening` create questions.
  - `POST /api/applications/{jobApplication}/screening-answers` stores answers, updates screening score, fires analytics.
- **Interviews**
  - `GET /api/applications/{jobApplication}/interviews` list schedules.
  - `POST /api/applications/{jobApplication}/interviews` accepts `scheduled_at|start`, `location`, `instructions`, `meeting_link`, `status` and emits `interview_scheduled`.
- **CVs & Cover Letters**
  - CVs: `GET /api/cvs`, `POST /api/cvs`, `GET/PUT/DELETE /api/cvs/{cv}` (events: `cv_created`, `cv_updated`, `job_seeker_profile_completed`).
  - Cover letters: `GET /api/cover-letters`, `POST /api/cover-letters`, `GET/PUT/DELETE /api/cover-letters/{coverLetter}` (event: `cover_letter_created`).
- **Companies**
  - `POST /companies` creates company profiles (analytics: `employer_profile_completed`).
- **Subscriptions**
  - `GET /api/subscriptions` (filter by `company_id`) and `POST /api/subscriptions` for plan tracking.

## Functions & Features (Flutter)
- **Screens**
  - `lib/src/widgets/job_list_screen.dart`: Lists jobs with search; depends on `JobsRepository` + `JobsNotifier`.
  - `lib/src/widgets/job_detail_screen.dart`: Shows job detail/tags/questions and CTA to apply.
  - `lib/src/widgets/application_form_page.dart`: Collects applicant info, cover letter, screening answers and posts to `/api/applications` via `JobsRepository.apply`.
  - `lib/src/widgets/ats_pipeline_page.dart`: Visualizes ATS stages using `jobs_repository.fetchPipeline` and `moveToStage`.
  - `lib/src/widgets/interview_schedule_page.dart`: Schedules interviews through `/api/applications/{id}/interviews`.
  - `lib/src/widgets/subscription_screen.dart`: Displays subscription data using `SubscriptionRepository`.
  - `lib/src/widgets/cv_builder_page.dart`: Captures CV metadata and saves through `ProfileRepository.saveCv` → `/api/cvs`.
  - `lib/src/widgets/cover_letter_page.dart`: Creates a cover letter via `ProfileRepository.saveCoverLetter` → `/api/cover-letters`.
- **Services/Repositories**
  - `JobsService` / `JobsRepository`: search/list/detail, saved toggles, similar jobs, apply, pipeline, interviews.
  - `ApplicationsService`: list/detail/withdraw/move ATS stage, interview retrieval.
  - `CvService`, `CoverLetterService`, `ProfileRepository`: CRUD for CVs and cover letters using updated endpoints.
  - `BillingService`, `SubscriptionRepository`: subscription retrieval/updates.
- **State**
  - ChangeNotifiers (`jobs_notifier.dart`, `application_notifier.dart`, `cv_state.dart`, `cover_letter_state.dart`, etc.) expose loading/error flags and trigger refresh after create/update/delete.

## Integration Guide – Feed & Search
- **Feed**
  - Use `Jobs\Support\Feed\JobFeedTransformer::toFeedItem($job)` to serialize a `Job` into a generic feed payload (`type`, `title`, `summary`, `url`, `owner_id`, `published_at`, `tags`, `location`).
  - Example: `FeedItem::fromArray(JobFeedTransformer::toFeedItem($job))` inside your feed aggregation service.
- **Search**
  - Backend helper: `Jobs\Support\Search\JobSearchService::search(['search' => 'designer', 'location' => 'Remote'])` returns a paginator of published jobs with companies.
  - Flutter: call `JobsService.fetchJobs(keyword: 'designer', location: 'Remote')` or `JobsRepository.fetchJobs(query: ...)` to render results; filters map directly to Laravel query params.
  - To plug into Scout/Meilisearch, index fields `title`, `description`, `location`, `employment_type`, `workplace_type`, and `company.name`; reuse `JobSearchService` filter logic when building search queries.

## Integration Guide – Analytics
- Backend dispatches `Jobs\Events\AnalyticsEvent` via `Jobs\Support\Analytics\JobsAnalytics::dispatch` for:
  - `job_seeker_profile_completed`, `employer_profile_completed`, `job_posted`, `job_updated`, `job_closed`,
  - `job_applied`, `application_status_changed`,
  - `cv_created`, `cv_updated`, `cover_letter_created`,
  - `screening_questions_answered`, `interview_scheduled`.
- Hook your analytics listener into `AnalyticsEvent` to forward to Segment/GA/datadog; log channel tags `[jobs]` already added.
- Flutter: inject your analytics provider into screens/notifiers to mirror key steps (list view loaded, detail opened, application submitted, interview scheduled).

## Security, Reliability & Performance Notes
- Validation via FormRequests/inline validation on all endpoints; candidate/company IDs validated and sanitized.
- Auth-required actions (saving jobs, apply posting, ATS moves) enforce authentication guards inside controllers; unauthorized requests 401.
- Pagination applied on all listings (`jobs`, `applications`, `saved`, `subscriptions`, `interviews`).
- Jobs queries eager-load companies/app counts to reduce N+1; similar jobs limited to 10.
- Bookmark/apply routes use CSRF (web) and accept bearer headers for API calls.
- File-like payloads (CV content) stored as JSON; extend with storage disks when uploading actual files.
- For performance, add DB indexes on `jobs.company_id`, `jobs.status`, `jobs.published_at`, `job_bookmarks.user_id`, `job_applications.job_id` when migrating in host app.

## Configuration & Environment
- Publish config: `php artisan vendor:publish --tag=jobs-config` to adjust roles/features/posting limits.
- Views/lang: `php artisan vendor:publish --tag=jobs-views` / `jobs-lang` if overriding UI text.
- Ensure API + web middleware stacks include `auth` + `web` with CSRF for POST job/apply/save routes.
- Set `JobsAddonConfig.baseUrl` and optional `authToken` (Bearer) in Flutter host app; headers auto-attached.

## Quick Start – Integration Steps
1. **Laravel Package**
   - Install dependencies and run migrations.
   - Register `Jobs\JobsServiceProvider` (auto-discovery) and publish config/views as needed.
   - Add web routes namespace `/jobs` to navigation; ensure auth middleware wraps apply/save/post routes.
   - Wire feed/search integrations using `JobFeedTransformer` and `JobSearchService`.
   - Attach analytics listener to `Jobs\Events\AnalyticsEvent` to forward events.
2. **Flutter Addon**
   - Add dependency and set `JobsAddonConfig.baseUrl` (+ `authToken` if protected).
   - Register menu entries from `lib/src/menu.dart` to expose list/detail/apply, ATS, CV, cover-letter, subscription screens.
   - Provide candidate/company IDs from host app when launching CV/CoverLetter/Apply flows.
   - Optionally inject analytics callbacks around screen navigation and submission actions.

## Final QA Checklist (Jobs/ATS)
- Verify job search filters, saved toggles, and pagination on `/jobs`.
- Confirm job detail shows salary, employment/workplace tags, similar jobs, and opens apply wizard.
- Submit application via web and Flutter, ensuring screening answers persist and analytics fire.
- Move application across ATS stages and check status events.
- Schedule interviews and review timeline updates.
- Create/update CVs and cover letters through API and Flutter editors.
- Validate feed/search integrations by consuming transformers/services in host app.
