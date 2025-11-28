# Agent Instructions – AI, Headhunter, Experience Launchpad & Volunteering Package (Laravel + Flutter)

## Overall Goal

Your goal is to create **one coherent addon** composed of:

1. A **Laravel package** (backend + web UI)
2. A **Flutter mobile addon package** (client UI + logic)

that together provide aligned functionality on both:

* The **Laravel backend / web app**, and
* The **Flutter mobile app**.

This addon plugs into an existing **Sociopro-based social media platform**, evolving it towards a **LinkedIn-style professional & talent network**. It must behave as an **addon wrapper** that **extends** Sociopro without overriding, breaking, or tightly coupling to core behaviour.

The addon brings together four major pillars under a single, consistent domain model:

1. **Headhunters** – tools for retained search, candidate pipelines and mandates.
2. **Experience Launchpad** – structured experience-building opportunities (including internships, projects and career-change tracks).
3. **AI Workspace** – curated AI utilities (cloned and adapted from Magic AI’s codebase) focused on careers, branding, search and outreach.
4. **Volunteering** – non‑paid volunteering opportunities and charity placements.

> ⚠️ Important: **Do not copy any binary files** (e.g. images, fonts, compiled assets, `.exe`, `.dll`, `.so`, APKs, etc.). Only copy **source code, configuration, Blade templates, Dart code**, and other text assets.
>
> ⚠️ Important: Assume Sociopro already provides core social features (users, posts, comments, stories, chat, marketplace, notifications, etc.). You must build **on top of** these, using **new namespaced code, config flags and adapters**, not by renaming or deleting existing core logic.
>
> ⚠️ Important: For AI functionality, you **may copy and adapt relevant source files from the existing Magic AI codebase** into this addon, but there must be **no runtime dependency** on the separate Magic AI application. The goal is that **Magic AI can be removed** later with no breakage. All AI logic must live under this addon’s own namespaces, config, routes and UI.

---

## Integration Principles

1. **Non-invasive extension**

   * Never modify Sociopro core files directly (no edits in `vendor/`, no hard patches to core controllers or models).
   * All new behaviour should be implemented via:

     * New **namespaced models, controllers and services**.
     * **Service providers**, **events/listeners**, **observers**, **policies**, and **view composers**.
     * **Config flags** and **feature toggles**.

2. **Clear boundaries & domains**

   * Keep the four pillars (Headhunters, Experience Launchpad, AI Workspace, Volunteering) **logically separated** but **interoperable**.
   * Identify and extract any truly shared concepts (e.g. "opportunity", "application", "candidate profile enrichment") into shared modules to avoid duplication.

3. **Single source of truth for users & identities**

   * All user accounts, authentication and core profile data come from Sociopro.
   * The addon may extend user profiles with additional tables/columns (e.g. `headhunter_profiles`, `career_profiles`, `ai_workspace_settings`), but **must not** create a second user table.

4. **Symmetry between web & mobile**

   * Any critical workflow (e.g. applying for an Experience Launchpad opportunity, booking an interview, running AI CV optimisation) must be supported on **both** Laravel (web) and Flutter (mobile), possibly with different UI but the **same underlying APIs and permissions**.

5. **Configurable & optional**

   * Assume this addon may be **enabled or disabled per deployment** or even per tenant.
   * All routes, menu entries and API endpoints should honour an `enabled` flag in config.
   * The AI components should be configurable for **Bring Your Own Key (BYOK)** and/or **platform‑provided keys** with cost/usage limits.

6. **Security first**

   * Enforce authentication and authorisation on all sensitive endpoints (headhunter pipelines, AI usage logs, candidate data, volunteering contact details, etc.).
   * Avoid exposing raw model IDs where not needed; use policies and scopes.
   * When copying Magic AI code, **remove any debug shortcuts**, test keys, or insecure defaults.

7. **Analytics & observability**

   * Capture high‑level analytics events (e.g. "AI_CV_GENERATED", "HEADHUNTER_STAGE_MOVED", "LAUNCHPAD_APPLICATION_SUBMITTED", "VOLUNTEER_MATCHED") via the existing analytics hooks used by other addon packages.
   * Ensure analytics integration is **optional** and can be disabled or routed to a null adapter.

---

## Part 1 – Laravel Package

### 1. Config

Create a configuration file, for example: `config/gigvora_talent_ai.php` (or under `config/addons/` depending on repo convention) defining:

* **Global enable flags**:

  * `enabled` – master flag for the addon.
  * `modules.headhunters.enabled`
  * `modules.launchpad.enabled`
  * `modules.ai_workspace.enabled`
  * `modules.volunteering.enabled`

* **AI engine configuration**:

  * `ai.provider` – which LLM provider/stack to use (e.g. `openai`, `anthropic`, `local`, `stub`).
  * `ai.byok.enabled` – whether users can connect their own keys.
  * `ai.platform_keys.enabled` – whether the platform exposes shared keys.
  * `ai.pricing_tiers` – definition of subscription tiers (e.g. `basic`, `pro`, `enterprise`) mapping to token/credit limits.
  * `ai.cost_model` – optional per‑token/per‑minute pricing reference used by the cost predictor.

* **AI usage limits & safety**:

  * Max daily/weekly/monthly usage by tier.
  * Guardrail settings (max prompt length, max output length, blocked task types where relevant).

* **Headhunter settings**:

  * Default pipeline stages (e.g. `sourced`, `screened`, `shortlisted`, `interviewed`, `offer`, `placed`).
  * Permission roles (e.g. which roles can create mandates, view candidates, add notes).
  * Retainer & fee models configuration (for later extensibility).

* **Experience Launchpad settings**:

  * Default categories (e.g. `Graduate`, `Career-changer`, `School-leaver`, `Returner`).
  * Rules for pay reductions, experience credit accrual, reference requirements.
  * Flags to require manual approval by organisation/mentor.

* **Volunteering settings**:

  * Default volunteering categories and sectors.
  * Whether posts must be verified as genuine non‑paid opportunities.
  * Optional links to external charity vetting logic.

* **Analytics & logging**:

  * Toggles for emitting structured analytics events.
  * Log channels for AI errors, cost anomalies, or abuse.

Ensure the config file includes sensible defaults and is **safe to publish** (no secrets).

---

### 2. Database

Design migrations to support the four pillars. Where possible, use meaningful table names and foreign keys back to Sociopro’s user and organisation tables.

> Do not drop or rename any Sociopro core tables. Only add new tables or safe nullable extension columns.

Recommended tables (non‑exhaustive):

#### 2.1 Headhunters

* `headhunter_profiles`

  * `id`
  * `user_id` (FK to users table)
  * `headline`, `bio`, `specialisms` (JSON or text)
  * `fee_model` (enum – retained, contingency, hybrid)
  * `metadata` (JSON)

* `headhunter_mandates`

  * `id`
  * `created_by_headhunter_id` (FK to headhunter_profiles)
  * `client_organisation_id` (FK to organisations/businesses if exists; otherwise generic `client_name` fields)
  * `title`, `role_level`, `salary_range_min`, `salary_range_max`
  * `location`, `work_type` (remote/hybrid/onsite)
  * `status` (open, on‑hold, closed, filled)
  * `brief` (text)
  * `created_at`, `updated_at`

* `headhunter_candidates`

  * `id`
  * `user_id` (nullable – candidate may or may not be an existing platform user)
  * `external_identifier` (for off‑platform candidates)
  * `name`, `email`, `phone`, `current_title`, `current_company`
  * `cv_path` or `cv_metadata` (for reference; actual CV storage handled via existing storage system)
  * `notes` (text)

* `headhunter_pipeline_items`

  * `id`
  * `mandate_id` (FK to `headhunter_mandates`)
  * `candidate_id` (FK to `headhunter_candidates`)
  * `stage` (enum matching pipeline stages)
  * `score` (numeric or JSON with multiple dimensions)
  * `source` (manual, AI_recommendation, referral, etc.)
  * `is_favourite` (bool)
  * `metadata` (JSON – interview history, salary expectations, etc.)
  * `created_at`, `updated_at`

* `headhunter_interviews`

  * `id`
  * `pipeline_item_id`
  * `scheduled_at`, `duration_minutes`
  * `location_type` (online, phone, onsite)
  * `location_details`
  * `outcome` (enum) + `notes`

#### 2.2 Experience Launchpad

* `launchpad_programmes`

  * `id`
  * `created_by_user_id` (organisation owner / mentor)
  * `title`, `description`
  * `category` (Graduate, Career-changer, etc.)
  * `industry`
  * `base_role_title`
  * `expected_hours_total`
  * `expected_weeks_total`
  * `reference_offered` (bool)
  * `qualification_awarded` (nullable string)
  * `pay_base` (decimal, nullable if unpaid)
  * `pay_reduction_percent` (decimal, nullable)
  * `application_deadline` (nullable)
  * `status` (draft, published, closed)

* `launchpad_tasks`

  * `id`
  * `programme_id`
  * `title`, `description`
  * `order`
  * `estimated_hours`
  * `skills_covered` (JSON)

* `launchpad_applications`

  * `id`
  * `programme_id`
  * `applicant_user_id`
  * `status` (applied, shortlisted, interview, offered, rejected, completed)
  * `total_hours_completed`
  * `experience_summary` (text)
  * `reference_issued` (bool)
  * `qualification_issued` (bool)
  * `mentor_notes` (text)
  * `created_at`, `updated_at`

* `launchpad_interviews`

  * Similar structure to `headhunter_interviews`, but scoped to programmes/applications.

#### 2.3 AI Workspace

* `ai_sessions`

  * `id`
  * `user_id`
  * `type` (cv_writer, outreach, calendar, interview_prep, image_edit, video, etc.)
  * `input_metadata` (JSON – high‑level properties, not raw PII; avoid storing full prompts unless required and permitted)
  * `output_metadata` (JSON – length, quality score, etc.)
  * `tokens_used` / `credits_used`
  * `cost_estimated`
  * `status` (success, error)
  * `created_at`

* `ai_byok_credentials`

  * `id`
  * `user_id`
  * `provider`
  * `api_key_encrypted`
  * `settings` (JSON – models, rate limits)
  * Timestamps

* `ai_subscription_plans`

  * `id`, `name`, `slug`
  * `monthly_price`
  * `yearly_price`
  * `credit_allowance`
  * `features` (JSON)

* `ai_user_subscriptions`

  * `id`
  * `user_id`
  * `plan_id`
  * `status` (active, past_due, cancelled)
  * `renews_at`
  * `cancelled_at`

* `ai_usage_aggregates`

  * Aggregated statistics per user/plan/period to drive cost prediction and upgrade suggestions.

#### 2.4 Volunteering

* `volunteering_opportunities`

  * `id`
  * `created_by_user_id`
  * `organisation_name` (or FK if organisations exist)
  * `title`, `description`
  * `location_type` (remote, onsite, hybrid)
  * `location`
  * `sector` (charity, NGO, community, etc.)
  * `time_commitment_hours_per_week`
  * `duration_weeks` (nullable)
  * `is_expenses_only` (bool)
  * `verified` (bool)
  * `status` (draft, published, closed)

* `volunteering_applications`

  * `id`
  * `opportunity_id`
  * `applicant_user_id`
  * `status` (applied, in_review, accepted, rejected, completed)
  * `notes`
  * `created_at`, `updated_at`

Add any required pivot tables for tags, skills, industries as needed.

---

### 3. Domains

Structure the Laravel code into clear domains under an addon namespace, e.g. `Gigvora\TalentAi` or similar:

* `Domain/Headhunters`

  * Models: `HeadhunterProfile`, `HeadhunterMandate`, `HeadhunterCandidate`, `HeadhunterPipelineItem`, `HeadhunterInterview`.
  * Services: pipeline management, candidate matching, mandate lifecycle.
  * Policies: controlling who can view/edit what.

* `Domain/Launchpad`

  * Models: `LaunchpadProgramme`, `LaunchpadTask`, `LaunchpadApplication`, `LaunchpadInterview`.
  * Services: experience calculation, hours aggregation, reference/qualification issuance.

* `Domain/AiWorkspace`

  * Models: `AiSession`, `AiByokCredential`, `AiSubscriptionPlan`, `AiUserSubscription`, `AiUsageAggregate`.
  * Services (adapted from Magic AI):

    * CV/profile writer & fixer.
    * Outreach composer.
    * Social media calendar/post generator.
    * Career coach persona / headhunter brain.
    * Content repurposer (long video → clips, posts).
    * Interview prep Q&A.
    * Image generation & editing (canvas, nano‑editor, ad creatives).
    * Video generation (e.g. via VEO‑style text‑to‑video, using appropriate API wrappers).
    * Marketing bot utilities (campaign ideas, copy, hooks).
  * Cost management & subscription helper services.

* `Domain/Volunteering`

  * Models: `VolunteeringOpportunity`, `VolunteeringApplication`.
  * Services: matching, verification, application workflows.

* `Domain/Shared`

  * Value objects, enums, events and shared utilities.
  * Analytics event emitters.

Each domain should be **self‑contained**, exposing a clear API (services, facades, controllers) and minimising cross‑domain coupling.

---

### 6. Resources (Blade Views)

Implement Blade views for the web interface:

* Headhunter dashboards:

  * Overview of mandates and pipelines.
  * Kanban‑style pipeline board per mandate.
  * Candidate detail pages with notes and AI suggestions (e.g. AI‑suggested questions or outreach messages).

* Experience Launchpad:

  * Programme creation/editing forms.
  * Public listing pages and filters.
  * Application and progress tracking views.

* AI Workspace:

  * Unified AI workspace page with tabs/cards for each AI tool category (Profile & CV, Outreach, Calendar, Interview Prep, Social Media, Images, Video, Writer, etc.).
  * Simple, safe prompt UIs reusing UI/UX patterns from Magic AI but **namespaced and styled** to match Gigvora.

* Volunteering:

  * Volunteer opportunity creation forms.
  * Discovery/browse pages.
  * Application flow and status tracking.

Use **layouts** and UI components consistent with other Gigvora addons. Separate reusable partials (forms, tables, modals) under a namespaced folder such as `resources/views/addons/talent_ai/...`.

---

### 7. Admin Panel Entries

Integrate with the existing admin panel / settings area by adding:

* A main **"Talent & AI"** section containing:

  * Module toggles (enable/disable Headhunters, Launchpad, AI Workspace, Volunteering).
  * AI provider and key management UI (for platform keys only – BYOK is user‑side).
  * Subscription plan management (create/edit `AiSubscriptionPlan`).

* Management screens:

  * Headhunter overview: list, verify, or flag headhunter profiles.
  * Launchpad programmes moderation.
  * Volunteering opportunities moderation and verification.

Ensure all admin routes are protected by appropriate middleware/permissions (e.g. `can:manage_addons` or a dedicated `manage_talent_ai` ability).

---

### 8. Assets (CSS/JS)

* Place all addon‑specific CSS and JS under a dedicated path (e.g. `resources/js/addons/talent_ai`, `resources/css/addons/talent_ai`).
* Use the existing build pipeline (Vite/Mix) to compile and version assets.
* Avoid global CSS overrides; scope styles via BEM patterns or component‑local classes.

---

### 9. Language Translations

* Provide translation files under `resources/lang/{locale}/addons_talent_ai.php` (or similar naming convention).
* Include keys for:

  * All menu labels and headings.
  * Status enums (stages, application states, etc.).
  * AI toolnames and tool descriptions.
  * Validation and error messages.

---

### 10. Routes

Define routes in a dedicated routes file, e.g. `routes/addons_talent_ai.php`, then register them via the service provider.

* Use **route groups** with prefixes and name prefixes:

  * Prefix: `/addons/talent-ai/...`
  * Name: `addons.talent_ai.*`

* Separate route groups:

  * **Web (Blade)** – `web` + `auth` middleware.
  * **API (JSON)** – `api` + auth token middleware (Sanctum or existing API guard).

Ensure there are no collisions with core or other addon routes.

---

### 11. Services & Support

Implement service classes for:

* **HeadhunterService** – manage mandates, candidate linking, stage transitions, and event emission.
* **LaunchpadService** – manage programmes, tasks, applications, progress tracking.
* **AiWorkspaceService** – orchestrate calls to underlying AI tools (copied from Magic AI), enforce limits, record `AiSession` records, and handle BYOK vs platform keys.
* **VolunteeringService** – create and moderate opportunities, manage applications.

Where functionality is copied from Magic AI, refactor it to:

* Use this addon’s models and config.
* Remove any direct references to the old app’s namespaces, facades or specific database tables.

---

### 12. Service Provider

Create a dedicated service provider, e.g. `Gigvora\TalentAi\Providers\TalentAiServiceProvider` that:

* Merges the addon config into the application.
* Registers translations, views, and routes.
* Binds interfaces to implementations (services, repositories).
* Registers policies for the new models.
* Optionally registers console commands for:

  * Seeding default pipeline stages.
  * Seeding default AI subscription plans.

Ensure the service provider is **auto‑discoverable** or explicitly registered by the main app.

---

### 13. Documentation

Include a Markdown documentation file in the addon root (e.g. `docs/README_TALENT_AI.md`) that explains:

* Features and capabilities of the addon.
* Installation and configuration steps.
* How to enable/disable each module.
* How to configure BYOK and platform AI keys.
* Example usage flows (headhunter pipeline, launchpad application, AI CV writing, volunteering application).

---

## Part 2 – Flutter Addon Package

The Flutter addon must provide client‑side support for all four pillars using the same backend API.

### 1. `pubspec.yaml`

* Declare a **separate Dart/Flutter package** for the addon (or a module within the existing app) with:

  * Dependencies on the app’s existing networking, state‑management, and design system packages.
  * Any additional packages required for:

    * Video previews.
    * Rich text editing for AI outputs (if needed).

* Expose a clean API to the host app:

  * Routes/nav builders.
  * Widgets (e.g. `TalentAiHomeScreen`, `HeadhunterDashboard`, `LaunchpadProgrammeListScreen`, `AiWorkspaceScreen`, `VolunteeringListScreen`).

---

### 2. Models

* Mirror the Laravel API response shapes with Dart models:

  * `HeadhunterProfileModel`, `HeadhunterMandateModel`, `HeadhunterCandidateModel`, `HeadhunterPipelineItemModel`, `HeadhunterInterviewModel`.
  * `LaunchpadProgrammeModel`, `LaunchpadTaskModel`, `LaunchpadApplicationModel`, `LaunchpadInterviewModel`.
  * `AiSessionModel`, `AiSubscriptionPlanModel`, `AiUserSubscriptionModel`.
  * `VolunteeringOpportunityModel`, `VolunteeringApplicationModel`.

* Use existing JSON serialisation patterns (e.g. `json_serializable`) in line with other addons.

---

### 3. Screens / Pages

Implement key screens for each pillar:

* **Headhunters**

  * Dashboard for mandates.
  * Pipeline board per mandate.
  * Candidate details and notes.

* **Experience Launchpad**

  * Programme browsing and filters (by category, industry, commitment).
  * Programme detail, tasks overview, and expected experience metrics (hours/weeks/years, reference, qualification).
  * Application flow and progress tracker.

* **AI Workspace**

  * Single entry **AI Workspace Screen** split into cards/tabs:

    * Profile & CV Writer.
    * Outreach Composer (InMails, emails, DMs).
    * Social Calendar & Post Generator.
    * Interview Prep.
    * Social Media Image & Canvas tools (basic UI with offloaded generation to backend).
    * Writer & Chat.
    * Video tools (where supported by backend).
    * Marketing Bot.

* **Volunteering**

  * Volunteering opportunities list with search and filters.
  * Opportunity details and application flow.

Follow the existing design system for typography, colours and layout. The goal is that this addon feels like part of the same app.

---

### 5. State Management

* Use the **same state management approach** as the core app (e.g. Provider/Bloc/Riverpod).
* Define stores/cubits/notifiers for:

  * Headhunter state (mandates, pipelines).
  * Launchpad state (programmes, applications, progress).
  * AI workspace state (active tool, ongoing session, usage limits).
  * Volunteering state (opportunities, applications).

Ensure all API call errors are handled gracefully with user‑friendly messages.

---

### 6. `menu.dart`

* Extend or expose functions for adding menu entries, such as:

  * `Talent & AI` section leading to `TalentAiHomeScreen`.
  * Sub‑entries for `Headhunters`, `Launchpad`, `AI Workspace`, and `Volunteering`.

Respect any feature flags returned from the backend (e.g. hide AI if disabled, hide headhunters if not available for the user’s role).

---

### 7. Analytics & Security Hooks (Client-Side)

* When a user triggers key actions (e.g. submitting a Launchpad application, running an AI CV optimisation), emit appropriate analytics events using the app’s standard analytics interface.
* Ensure that **no secrets (API keys)** are ever stored or used on the client; all AI calls should go through the backend.

---

## Required Functional Areas (Both Laravel & Flutter)

Below are the explicit functional requirements that must be implemented consistently across Laravel (backend/web) and Flutter (mobile) layers.

### 1. Headhunters

* Full headhunter lifecycle:

  * Users can **apply to become headhunters** or be flagged as such by admins.
  * Headhunters can create/manage **mandates** for roles.
  * CRUD for headhunter profiles, mandates and candidates.
  * Manage **pipelines** with clearly defined stages (sourced → placed, etc.).
  * Schedule and log **interviews**, notes and outcomes.
  * Tools to manage **mandates, outreach, interviews, retainers**, and client relationships.
  * Matching views between headhunters and high‑value candidates (using search + optional AI suggestions).
  * Secure permission model so only authorised headhunters and admins can view/edit pipeline data.

### 2. Experience Launchpad

* Focused on individuals with **little or no experience** (graduates, 16+ school‑leavers, career changers, returners).
* Organisations/mentors can create **Launchpad programmes** that define:

  * Tasks and projects.
  * Estimated hours, weeks, and total experience.
  * Whether a **reference** will be offered.
  * Whether any **qualification/certificate** is provided.
  * Any **pay reduction percentage from base** (if paid) and clear disclosure of conditions.
* Candidates can:

  * Browse and apply for programmes.
  * Track their progress and see hours/weeks accumulated.
  * See outcomes (reference issued, qualification gained).
* The system should:

  * Compute total experience gained per programme and across programmes.
  * Optionally surface this experience into user profiles (e.g. as case studies/projects).
  * Integrate with AI features for CV/profile optimisation based on Launchpad experiences.

### 3. AI Layer (Curated AI Workspace)

All AI functionality is **self‑contained in this addon**, using copied and adapted logic from Magic AI. There must be **no runtime dependency** on an external Magic AI app.

All AI tools are accessed through a **single, unified AI interface** on web and mobile. The initial scope includes:

3a. **AI Profile/CV Writer & Fixer**

* Generate and refine CVs, profiles, and summaries using user data and Launchpad/Headhunter experiences.

3b. **AI Outreach Composer**

* Create tailored outreach messages (InMails, emails, DMs) for recruiters, headhunters, hiring managers and mentors.

3c. **Social Media Calendar & Post Generator**

* Suggest posting schedules and generate posts for building a professional brand (with particular focus on traders/creators but usable by all).

3d. **AI Career Coach / Persona ("Headhunter Brain")**

* Provide interactive guidance on career decisions, job search strategy, and profile positioning.

3e. **AI Content Repurposing**

* Take long‑form content (e.g. long videos or posts) and repurpose into clips, snippets, and multi‑platform content (LinkedIn, X, etc.).

3f. **AI Interview Prep / Q&A**

* Generate interview questions and suggested answers based on role, mandate or programme.

3g. **AI Social Media Image Editors & Canvas**

* Provide image generation and editing suitable for social posts and ads (e.g. Canva‑style but AI‑powered), including:

  * Image generation from prompts.
  * Nano‑editor for simple adjustments.
  * Templates for social posts, covers and ads.

3h. **AI Writer & AI Chat**

* General structured writer (articles, posts, summaries, reports) plus a flexible chat interface for career and content‑related tasks.

3i. **AI Video (e.g. VEO 3 and others)**

* Where supported by backend APIs, allow text/images → short video generations, particularly for ad creatives, intros, and explainer clips.

3j. **Marketing Bot**

* Suggest funnels, campaigns, hooks and ad copy tailored to profiles, channels and budgets.

Additional core AI platform functionality:

* **BYOK vs Platform Keys** – users can bring their own AI keys or use platform keys governed by subscription.
* **AI Web Search & Tools** – enable web‑augmented answers where configured.
* **Speech to Text & Voice** – speech transcription for notes, plus optional voice output if supported.
* **AI Code & Article Writer** – retain core Magic AI’s code/article generation where appropriate for user base (e.g. devs building portfolios).
* **AI Personas & Avatars** – define and reuse personas for different content/communication styles.
* **AI API Cost Management & Predictor** – estimate and track AI costs based on usage, helping admins design sustainable pricing.
* **AI Subscription Upgrade Pricing Manager** – helper logic for recommending plan upgrades based on usage patterns.

All of the above must respect:

* Usage limits by plan.
* Safety rules and guardrails.
* Logging for debugging and abuse detection.

### 4. Volunteering

* Provide a full workflow for **non‑paid volunteering opportunities**:

  * Charities, NGOs and community organisations can **post volunteering opportunities**.
  * Users can **browse, filter, and apply**.
  * There is **no salary**; optionally flag if reasonable expenses are covered.
* Requirements:

  * Clear labelling of roles as volunteering / unpaid.
  * Ability for admins/moderators to verify opportunities.
  * Basic stats (hours contributed, roles completed) which can optionally feed into Experience Launchpad and profile enhancement.

---

By following this AGENTS file, you will build a **single, cohesive addon** that layers **Headhunters**, **Experience Launchpad**, **AI Workspace**, and **Volunteering** on top of Sociopro, with all AI functionality fully internalised so that the separate Magic AI app can be retired without breaking Gigvora.
