# Talent & AI Backend Functions & Endpoints

## Web (`web` + `auth` middleware, gated by `gigvora_talent_ai.enabled` and module toggles)
UI assets are delivered via Vite from `resources/js/addons/talent_ai/*` and `resources/css/addons/talent_ai/talent_ai.css` to keep Gigvora branding consistent across dashboards, pipelines, and AI workspace tiles.

### Primary screens
- `GET /addons/talent-ai/headhunters/dashboard` → `view('talent_ai::headhunters.dashboard')` (name: `addons.talent_ai.headhunters.dashboard`)
  - Presents mandate and candidate summaries with pipeline drag-and-drop hooks (`pipeline_board.js`).
- `GET /addons/talent-ai/launchpad/programmes` → `view('talent_ai::launchpad.programmes.index')` (name: `addons.talent_ai.launchpad.programmes.index`)
  - Lists Experience Launchpad programmes with pagination and progress context (`launchpad_progress.js`).
- `GET /addons/talent-ai/ai-workspace` → `view('talent_ai::ai_workspace.index')` (name: `addons.talent_ai.ai_workspace.index`)
  - Surfaces AI tool tiles wired to API endpoints (`ai_workspace.js`).
- `GET /addons/talent-ai/volunteering/opportunities` → `view('talent_ai::volunteering.opportunities.index')` (name: `addons.talent_ai.volunteering.opportunities.index`)
  - Provides volunteering discovery with client-side filters (`volunteering_filters.js`).

### Headhunters (`modules.headhunters.enabled`)
- `POST /addons/talent-ai/headhunter/profile` → `HeadhunterProfileController@store` (`addons.talent_ai.headhunter.profile.store`)
  - Creates a headhunter profile for the authenticated user.
- `PUT /addons/talent-ai/headhunter/profile/{profile}` → `HeadhunterProfileController@update` (`addons.talent_ai.headhunter.profile.update`)
  - Updates profile metadata.
- `POST /addons/talent-ai/headhunter/{profile}/mandates` → `MandateController@store` (`addons.talent_ai.headhunter.mandate.store`)
  - Opens a new mandate tied to the profile.
- `PUT /addons/talent-ai/headhunter/mandates/{mandate}` → `MandateController@update` (`addons.talent_ai.headhunter.mandate.update`)
  - Edits mandate settings.
- `POST /addons/talent-ai/headhunter/{profile}/candidates` → `CandidateController@store` (`addons.talent_ai.headhunter.candidate.store`)
  - Adds a candidate to a mandate.
- `PUT /addons/talent-ai/headhunter/candidates/{candidate}` → `CandidateController@update` (`addons.talent_ai.headhunter.candidate.update`)
  - Updates candidate details.
- `POST /addons/talent-ai/headhunter/mandates/{mandate}/pipeline` → `PipelineController@store` (`addons.talent_ai.headhunter.pipeline.store`)
  - Creates a pipeline item for a mandate.
- `POST /addons/talent-ai/headhunter/pipeline/{pipelineItem}/move` → `PipelineController@move` (`addons.talent_ai.headhunter.pipeline.move`)
  - Moves a pipeline item across stages.
- `POST /addons/talent-ai/headhunter/pipeline/{pipelineItem}/interviews` → `HeadhunterInterviewController@store` (`addons.talent_ai.headhunter.interview.store`)
  - Adds an interview record.
- `PUT /addons/talent-ai/headhunter/interviews/{interview}` → `HeadhunterInterviewController@update` (`addons.talent_ai.headhunter.interview.update`)
  - Updates an interview entry.

### Launchpad (`modules.launchpad.enabled`)
- `POST /addons/talent-ai/launchpad/programmes` → `ProgrammeController@store` (`addons.talent_ai.launchpad.programme.store`)
  - Creates a launchpad programme.
- `PUT /addons/talent-ai/launchpad/programmes/{programme}` → `ProgrammeController@update` (`addons.talent_ai.launchpad.programme.update`)
  - Updates a programme.
- `POST /addons/talent-ai/launchpad/programmes/{programme}/publish` → `ProgrammeController@publish` (`addons.talent_ai.launchpad.programme.publish`)
  - Publishes a programme.
- `POST /addons/talent-ai/launchpad/programmes/{programme}/close` → `ProgrammeController@close` (`addons.talent_ai.launchpad.programme.close`)
  - Closes a programme.
- `POST /addons/talent-ai/launchpad/programmes/{programme}/applications` → `LaunchpadApplicationController@store` (`addons.talent_ai.launchpad.application.store`)
  - Submits an application to a programme.
- `POST /addons/talent-ai/launchpad/applications/{application}/status` → `LaunchpadApplicationController@updateStatus` (`addons.talent_ai.launchpad.application.status`)
  - Updates application status.
- `POST /addons/talent-ai/launchpad/applications/{application}/interviews` → `LaunchpadInterviewController@store` (`addons.talent_ai.launchpad.interview.store`)
  - Schedules an interview for an application.
- `PUT /addons/talent-ai/launchpad/interviews/{interview}` → `LaunchpadInterviewController@update` (`addons.talent_ai.launchpad.interview.update`)
  - Updates interview details.

### Volunteering (`modules.volunteering.enabled`)
- `POST /addons/talent-ai/volunteering/opportunities` → `OpportunityController@store` (`addons.talent_ai.volunteering.opportunity.store`)
  - Creates a volunteering opportunity.
- `PUT /addons/talent-ai/volunteering/opportunities/{opportunity}` → `OpportunityController@update` (`addons.talent_ai.volunteering.opportunity.update`)
  - Updates opportunity details.
- `POST /addons/talent-ai/volunteering/opportunities/{opportunity}/publish` → `OpportunityController@publish` (`addons.talent_ai.volunteering.opportunity.publish`)
  - Publishes an opportunity.
- `POST /addons/talent-ai/volunteering/opportunities/{opportunity}/close` → `OpportunityController@close` (`addons.talent_ai.volunteering.opportunity.close`)
  - Closes an opportunity.
- `POST /addons/talent-ai/volunteering/opportunities/{opportunity}/applications` → `VolunteeringApplicationController@store` (`addons.talent_ai.volunteering.application.store`)
  - Submits an application.
- `POST /addons/talent-ai/volunteering/applications/{application}/status` → `VolunteeringApplicationController@updateStatus` (`addons.talent_ai.volunteering.application.status`)
  - Updates application status.

### Admin (`can:manage_talent_ai`)
- `GET /addons/talent-ai/admin/config` → `AdminController@index` (`addons.talent_ai.admin.config`)
  - Admin settings dashboard for Talent & AI.
- `POST /addons/talent-ai/admin/plans` → `AdminController@storePlan` (`addons.talent_ai.admin.plans.store`)
  - Creates or updates AI workspace plans.

## API (`api` + `auth:sanctum`, `gigvora_talent_ai.enabled`)
### Headhunters (`modules.headhunters.enabled`)
- `GET /api/addons/talent-ai/headhunters/profile` → `HeadhunterProfileController@show` (`api.addons.talent_ai.headhunters.profile.show`)
  - Returns the authenticated headhunter profile with mandate and candidate counts.
- `GET /api/addons/talent-ai/headhunters/mandates` → `MandateController@index` (`api.addons.talent_ai.headhunters.mandates.index`)
  - Paginates mandates owned by the authenticated headhunter, with pipeline counts.
- `GET /api/addons/talent-ai/headhunters/mandates/{mandate}` → `MandateController@show` (`api.addons.talent_ai.headhunters.mandates.show`)
  - Shows mandate detail and pipeline items.
- `GET /api/addons/talent-ai/headhunters/mandates/{mandate}/pipeline` → `PipelineController@index` (`api.addons.talent_ai.headhunters.pipeline.index`)
  - Lists pipeline items for a mandate.
- `POST /api/addons/talent-ai/headhunters/mandates/{mandate}/pipeline/{pipelineItem}` → `PipelineController@move` (`api.addons.talent_ai.headhunters.pipeline.move`)
  - Moves a pipeline item between stages.
- `GET /api/addons/talent-ai/headhunters/candidates/{candidate}` → `CandidateController@show` (`api.addons.talent_ai.headhunters.candidates.show`)
  - Returns candidate detail for the headhunter.
- `POST /api/addons/talent-ai/headhunters/candidates/{candidate}/notes` → `CandidateController@notes` (`api.addons.talent_ai.headhunters.candidates.notes`)
  - Updates pipeline notes for the candidate across associated mandates.
- `GET /api/addons/talent-ai/headhunters/candidates/{candidate}/interviews` → `HeadhunterInterviewController@index` (`api.addons.talent_ai.headhunters.candidates.interviews.index`)
  - Lists interviews scheduled for the candidate’s pipeline items.

### Launchpad (`modules.launchpad.enabled`)
- `GET /api/addons/talent-ai/launchpad/programmes` → `ProgrammeController@index` (`api.addons.talent_ai.launchpad.programmes.index`)
  - Paginates published programmes (or all for managers/creators) with tasks.
- `GET /api/addons/talent-ai/launchpad/programmes/{programme}` → `ProgrammeController@show` (`api.addons.talent_ai.launchpad.programmes.show`)
  - Shows a programme and its tasks.
- `GET /api/addons/talent-ai/launchpad/programmes/{programme}/tasks` → `ProgrammeController@tasks` (`api.addons.talent_ai.launchpad.programmes.tasks`)
  - Lists programme tasks in order.
- `POST /api/addons/talent-ai/launchpad/programmes/{programme}/applications` → `LaunchpadApplicationController@store` (`api.addons.talent_ai.launchpad.applications.store`)
  - Submits an application to a programme.
- `GET /api/addons/talent-ai/launchpad/applications/{application}` → `LaunchpadApplicationController@show` (`api.addons.talent_ai.launchpad.applications.show`)
  - Returns an application with programme tasks, interviews, and task progress.
- `POST /api/addons/talent-ai/launchpad/applications/{application}/tasks/{task}` → `LaunchpadApplicationController@updateTask` (`api.addons.talent_ai.launchpad.applications.tasks.update`)
  - Marks or clears task completion for an application.

### Volunteering (`modules.volunteering.enabled`)
- `GET /api/addons/talent-ai/volunteering/opportunities` → `OpportunityController@index` (`api.addons.talent_ai.volunteering.opportunities.index`)
  - Paginates published volunteering opportunities (or all for managers/creators).
- `GET /api/addons/talent-ai/volunteering/opportunities/{opportunity}` → `OpportunityController@show` (`api.addons.talent_ai.volunteering.opportunities.show`)
  - Shows opportunity details.
- `POST /api/addons/talent-ai/volunteering/opportunities/{opportunity}/applications` → `VolunteeringApplicationController@store` (`api.addons.talent_ai.volunteering.applications.store`)
  - Submits an application to an opportunity.
- `GET /api/addons/talent-ai/volunteering/applications` → `VolunteeringApplicationController@index` (`api.addons.talent_ai.volunteering.applications.index`)
  - Lists volunteering applications for the authenticated user.
- `GET /api/addons/talent-ai/volunteering/applications/{application}` → `VolunteeringApplicationController@show` (`api.addons.talent_ai.volunteering.applications.show`)
  - Shows a single volunteering application with opportunity context.

### AI Workspace (`modules.ai_workspace.enabled`)
- `POST /api/addons/talent-ai/ai/cv-writer` → `ToolController@cvWriter` (`api.addons.talent_ai.ai.cv_writer`)
  - Generates CV content.
- `POST /api/addons/talent-ai/ai/outreach` → `ToolController@outreach` (`api.addons.talent_ai.ai.outreach`)
  - Produces outreach messages.
- `POST /api/addons/talent-ai/ai/social-calendar` → `ToolController@socialCalendar` (`api.addons.talent_ai.ai.social_calendar`)
  - Builds social content schedules.
- `POST /api/addons/talent-ai/ai/coach` → `ToolController@coach` (`api.addons.talent_ai.ai.coach`)
  - Coaching prompts/responses.
- `POST /api/addons/talent-ai/ai/repurpose` → `ToolController@repurpose` (`api.addons.talent_ai.ai.repurpose`)
  - Repurposes content.
- `POST /api/addons/talent-ai/ai/interview-prep` → `ToolController@interviewPrep` (`api.addons.talent_ai.ai.interview_prep`)
  - Interview preparation assistant.
- `POST /api/addons/talent-ai/ai/image-canvas` → `ToolController@imageCanvas` (`api.addons.talent_ai.ai.image_canvas`)
  - Image generation canvas.
- `POST /api/addons/talent-ai/ai/writer` → `ToolController@writer` (`api.addons.talent_ai.ai.writer`)
  - Generic AI writer.
- `POST /api/addons/talent-ai/ai/marketing-bot` → `ToolController@marketingBot` (`api.addons.talent_ai.ai.marketing_bot`)
  - Marketing campaign copy assistant.
- `POST /api/addons/talent-ai/ai/byok` → `ByokController@store` (`api.addons.talent_ai.ai.byok.store`)
  - Stores BYOK credentials when enabled.
- `DELETE /api/addons/talent-ai/ai/byok/{credential}` → `ByokController@destroy` (`api.addons.talent_ai.ai.byok.destroy`)
  - Removes BYOK credential.
- `GET /api/addons/talent-ai/ai-workspace/sessions` → `StatusController@sessions` (`api.addons.talent_ai.ai.sessions`)
  - Returns recent AI sessions for the current user.
- `GET /api/addons/talent-ai/ai-workspace/usage` → `StatusController@usage` (`api.addons.talent_ai.ai.usage`)
  - Returns recent usage aggregates for metering/limits.
- `GET /api/addons/talent-ai/ai-workspace/plans` → `StatusController@plans` (`api.addons.talent_ai.ai.plans`)
  - Lists available AI subscription plans.
- `GET /api/addons/talent-ai/ai-workspace/subscription` → `StatusController@subscription` (`api.addons.talent_ai.ai.subscription`)
  - Returns the authenticated user’s active AI subscription (if any).

## Permissions & Policies
- Policies mapped for headhunter, launchpad, and volunteering domain models ensure per-resource authorization.
- `manage_talent_ai` gate restricts admin routes to Gigvora admins (`user_role === 'admin'`).
- All routes enforce authentication through Laravel `auth` or `auth:sanctum` middleware.
- Navigation visibility: the **Talent & AI** menu plus its sub-links (Headhunters, Experience Launchpad, AI Workspace, Volunteering) are shown only when `gigvora_talent_ai.enabled` and the relevant `modules.*.enabled` flags are true; admin entries remain hidden without the `manage_talent_ai` ability.

## UI Function Mapping (Web)
- Headhunter pipeline drag-and-drop and card hydration: `resources/js/addons/talent_ai/pipeline_board.js` (paired with Blade pipeline components extending `layouts.app`).
- Launchpad progress steppers and programme cards: `resources/js/addons/talent_ai/launchpad_progress.js`.
- AI workspace tool tiles and BYOK interactions: `resources/js/addons/talent_ai/ai_workspace.js`.
- Volunteering filters and card interactions: `resources/js/addons/talent_ai/volunteering_filters.js`.
- Admin settings, plan management, and guardrail toggles: `resources/js/addons/talent_ai/admin_settings.js`.
