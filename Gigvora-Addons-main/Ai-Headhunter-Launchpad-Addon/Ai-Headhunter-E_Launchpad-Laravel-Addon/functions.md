# Talent & AI Backend Functions & Endpoints

## Web (`web` + `auth` middleware, gated by `gigvora_talent_ai.enabled` and module toggles)
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

## API (`api` + `auth:sanctum`, `gigvora_talent_ai.enabled`, `modules.ai_workspace.enabled`)
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

## Permissions & Policies
- Policies mapped for headhunter, launchpad, and volunteering domain models ensure per-resource authorization.
- `manage_talent_ai` gate restricts admin routes to Sociopro admins (`user_role === 'admin'`).
- All routes enforce authentication through Laravel `auth` or `auth:sanctum` middleware.
