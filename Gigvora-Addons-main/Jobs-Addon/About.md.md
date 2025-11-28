# Agent Instructions – Jobs Package
_UI, Views & Screens Specification (Laravel + Flutter)_

## Overall Goal

Build a complete **Jobs & ATS** experience for:

1. **Laravel web app** (Blade views + JS), and  
2. **Flutter mobile addon** (`jobs_flutter_addon`),

covering:

- Job discovery & search
- Job posting & company profiles
- Applications management (job seeker & employer)
- ATS pipelines (stages, notes, tags)
- CV builder & cover letters
- Screening questions
- Interview scheduling & calendar
- Job posting plans / subscriptions

This plugs into an existing **social media / LinkedIn-style platform** and must feel fully integrated.

> ⚠️ Do **not** add or touch any binary files (images, fonts, compiled bundles, `.exe`, `.dll`, `.so`, `.apk`, `.ipa`, etc.). Only templates, Dart/JS/TS, CSS/SCSS.

---

## 1. Laravel Web – Blade Views, JS & Admin

Use this namespace for views:

- Job seeker + employer: `resources/views/vendor/jobs/`
- Admin: `resources/views/vendor/jobs/admin/`
- Shared components: `resources/views/vendor/jobs/components/`

### 1.1 Job Seeker – User-Facing Blade Views

**1.1.1 Jobs Landing & Search Page**

- **File**: `jobs/index.blade.php`
- **Purpose**: Main entry to browse and search jobs.
- **Content**:
  - Hero/search section:
    - “Find your next role” headline.
    - Search fields:
      - Job title/keywords (text input).
      - Location (text or select).
    - “Search” (primary button).
  - Filters sidebar (or top filter bar):
    - Job type (full-time, part-time, contract, remote).
    - Salary range slider.
    - Experience level.
    - Industry / category.
    - Posted date (last 24h, week, month).
  - Job results list:
    - Card or table view.
    - Each card:
      - Job title.
      - Company name & logo placeholder.
      - Location.
      - Salary snippet (if provided).
      - Tags: Remote, Hybrid, etc.
      - “View details” button.
      - Bookmark/save icon.
  - Pagination.

- **JS** (`jobsSearch.js`):
  - Debounced keyword search.
  - AJAX filtering and pagination.
  - Save/unsave job via AJAX toggle.

---

**1.1.2 Job Detail Page**

- **File**: `jobs/show.blade.php`
- **Purpose**: Display full job details and apply entry.
- **Content**:
  - Header:
    - Job title.
    - Company name & link to company profile.
    - Location, salary, job type.
    - Save job icon.
  - Main sections:
    - Job description (HTML-safe).
    - Responsibilities list.
    - Requirements list.
    - Benefits list.
  - Sidebar/panel:
    - Company snapshot (logo placeholder, industry, size, location).
    - “Apply Now” button (primary).
    - Apply with CV / Linked profile snippet.
  - Additional:
    - Screening questions preview (if present).
    - “Similar jobs” list.

- **JS** (`jobDetail.js`):
  - Handle “Save job” toggle.
  - Scroll to “Apply” section when clicking CTA.
  - Optional: open application as modal.

---

**1.1.3 Job Apply Page / Modal**

- **File**: `jobs/apply.blade.php` (full page)  
  or partial for modal: `jobs/components/apply_modal.blade.php`
- **Purpose**: Multi-step application form.
- **Sections**:
  1. **Profile & Contact**:
     - Name, email, phone (pre-filled if logged in).
  2. **CV Selection**:
     - Use existing CV.
     - Upload new CV (UI only; actual upload handled backend).
  3. **Cover Letter**:
     - Textarea (rich text if possible).
     - “Generate from template” button (optional).
  4. **Screening Questions**:
     - Render job-specific questions (multiple-choice, yes/no, free text).
  5. **Review & Submit**:
     - Show summary.
     - Checkbox for data/privacy consent.
     - “Submit Application” button.

- **JS** (`jobApplyWizard.js`):
  - Multi-step navigation (Next/Back).
  - Client-side validation for required fields.
  - Progress indicator (steps).
  - AJAX submit (where appropriate) + success/failure messages.

---

**1.1.4 Saved Jobs Page**

- **File**: `jobs/saved.blade.php`
- **Purpose**: Let job seekers manage saved/bookmarked jobs.
- **Content**:
  - List of saved jobs (same card style as search results).
  - Quick actions:
    - “Apply Now”.
    - “Remove from saved”.

- **JS**:
  - Remove-from-saved via AJAX.
  - Lazy loading/pagination.

---

**1.1.5 My Applications Dashboard (Job Seeker)**

- **File**: `jobs/applications/index.blade.php`
- **Purpose**: View all applications and statuses.
- **Content**:
  - Tabs: All / In Progress / Interview / Rejected / Hired.
  - Table/list:
    - Job title.
    - Company.
    - Applied date.
    - Current status (Applied, Screening, Interview, Offer, Rejected).
    - Next step (if available).
    - “View Details” button.

- **JS**:
  - Tab switching + filtering.
  - Status badges.

---

**1.1.6 Application Detail Page (Job Seeker)**

- **File**: `jobs/applications/show.blade.php`
- **Content**:
  - Job summary (title, company).
  - Application timeline:
    - Submitted → Screening → Interview(s) → Offer/Reject.
  - View CV/cover letter (rendered or link).
  - Interview invitations:
    - Date/time.
    - “Add to calendar” link.
  - Messages/notes from employer (if integrated).

---

**1.1.7 CV Builder Pages**

- **Files**:
  - `jobs/cv/index.blade.php` – CV list.
  - `jobs/cv/edit.blade.php` – CV editor.
- **Content**:
  - CV list:
    - Existing CVs with names.
    - Actions: Edit, Download (if allowed), Delete.
    - Button: “Create New CV”.
  - CV edit:
    - Sections:
      - Personal info.
      - Summary.
      - Experience (repeatable blocks).
      - Education (repeatable blocks).
      - Skills (tags/chips).
    - Template selector (if multiple templates).

- **JS** (`cvBuilder.js`):
  - Add/remove experience/education sections dynamically.
  - Drag-and-drop reordering of sections.
  - Real-time character counts for summary.

---

**1.1.8 Cover Letter Builder**

- **File**: `jobs/cover_letters/edit.blade.php`
- **Content**:
  - Title of cover letter/template.
  - Rich-text editor area.
  - Variables/tags (e.g. [Company Name], [Job Title]).
  - Save as template / Use in application.

- **JS**:
  - Rich text editor integration.
  - Insert placeholder tags.

---

### 1.2 Employer – User-Facing Blade Views

**1.2.1 Employer Jobs Dashboard**

- **File**: `jobs/employer/dashboard.blade.php`
- **Purpose**: Overview of employer job performance.
- **Content**:
  - KPI cards:
    - Active jobs.
    - New applications (last 7 days).
    - Interviews scheduled.
    - Offers made.
  - Chart: Applications over time.
  - Job table:
    - Job title, status, views, applications, actions.

- **JS** (`employerDashboard.js`):
  - Chart rendering via existing chart lib.
  - AJAX filters (by job, timeframe).

---

**1.2.2 My Jobs List**

- **File**: `jobs/employer/jobs/index.blade.php`
- **Content**:
  - Filter by status: Draft / Open / Paused / Closed.
  - Table:
    - Title.
    - Job ref ID.
    - Created date.
    - Expiry date.
    - Status.
    - Applications count.
    - Actions: View, Edit, Close, Duplicate, Promote (if any).
  - Button: “Post a Job”.

---

**1.2.3 Job Create/Edit Wizard (Employer)**

- **File**: `jobs/employer/jobs/wizard.blade.php`
- **Steps**:
  1. **Basics**:
     - Job title, location, employment type, remote/on-site.
  2. **Details**:
     - Description (rich text), responsibilities, requirements, benefits.
  3. **Compensation & Policies**:
     - Salary range and currency.
     - Pay frequency (optional).
  4. **Screening**:
     - Attach existing screening template or create questions now.
  5. **Publishing**:
     - Job visibility: public / internal.
     - Start date, expiry date.
     - Choose posting plan (credit or subscription).
     - Review summary.

- **JS** (`jobPostWizard.js`):
  - Step navigation & validation.
  - Autosave as draft.
  - Rich-text editor for description.
  - Attach screening question sets.

---

**1.2.4 Company Profile Management**

- **File**: `jobs/employer/company/edit.blade.php`
- **Content**:
  - Company name, logo placeholder (no binary upload logic here).
  - Website, location, industry, size.
  - About/description.
  - Culture & benefits sections.

- **JS**:
  - Inline validations.
  - Save + success notification.

---

**1.2.5 ATS Pipeline View (Per Job)**

- **File**: `jobs/employer/ats/board.blade.php`
- **Purpose**: Kanban-style board of candidates per stage.
- **Content**:
  - Columns (stages):
    - Applied, Screening, Shortlisted, Interview, Offer, Hired, Rejected.
  - Each card:
    - Candidate name.
    - Key info (years experience, current role).
    - Tags (priority, referred).
    - Quick actions: View profile, add note, move to next stage.

- **JS** (`atsBoard.js`):
  - Drag-and-drop between stages (HTML5 or library-based).
  - When drop occurs:
    - Update stage via AJAX.
    - Show toast on success/error.
  - Column filtering (search by candidate name or tag).

---

**1.2.6 Candidate Detail & Notes (Employer)**

- **File**: `jobs/employer/candidates/show.blade.php`
- **Content**:
  - Candidate profile:
    - Name, headline, location.
    - CV preview/download link.
    - Social/profile link to main platform profile.
  - Application details:
    - Applied date.
    - Stage.
    - Answers to screening questions.
  - Notes & tags:
    - Add note (textarea).
    - Add/remove tags.
  - Actions:
    - Move to stage (dropdown).
    - Invite to interview.
    - Reject (with reason).

- **JS**:
  - Save note via AJAX.
  - Add/remove tags with chips UI.
  - Stage change AJAX.

---

**1.2.7 Screening Questions Configuration**

- **File**: `jobs/employer/screening/index.blade.php`
- **Content**:
  - List of screening templates.
  - Template edit form:
    - Name.
    - Questions list (repeatable blocks).
    - Question type: Yes/No, Multiple Choice, Short Answer.
    - Mark as “knockout” (auto-reject if answered incorrectly).

- **JS** (`screeningBuilder.js`):
  - Add/remove/reorder questions.
  - Toggle knockout flags.

---

**1.2.8 Interview Schedule & Calendar (Employer)**

- **File**:
  - `jobs/employer/interviews/index.blade.php`
  - `jobs/employer/interviews/calendar.blade.php`
- **Content**:
  - List view:
    - Interviews with candidate, role, date/time, status.
  - Calendar view:
    - Monthly/weekly view with interview slots.
  - Actions:
    - Schedule new interview (selected candidate & role).
    - Reschedule.
    - Cancel.

- **JS** (`interviewCalendar.js`):
  - Calendar using existing JS calendar library.
  - Click date to create new slot (modal).
  - Drag to move slot (reschedule via AJAX).

---

**1.2.9 Employer Subscription & Credits Page**

- **File**: `jobs/employer/billing/index.blade.php`
- **Content**:
  - Current plan info.
  - Remaining job credits.
  - Past invoices table.
  - Upgrade/downgrade buttons.

- **JS**:
  - Load plan options via AJAX (if necessary).
  - Plan selection & confirmation UI.

---

### 1.3 Admin Views (Jobs)

**1.3.1 Admin Jobs Overview**

- **File**: `jobs/admin/dashboard.blade.php`
- **Content**:
  - High-level metrics:
    - Total jobs, active jobs.
    - Total applications.
    - Number of employers & job seekers.
  - Charts:
    - Jobs posted over time.
    - Applications over time.

---

**1.3.2 Admin Job Moderation**

- **File**: `jobs/admin/jobs/index.blade.php`
- **Content**:
  - Table:
    - Job title, employer, posted date, status, flags (if any).
    - Actions: Approve, Reject, Suspend.
  - Filters:
    - Status, employer, date.

---

**1.3.3 Admin Categories & Locations**

- **Files**:
  - `jobs/admin/categories/index.blade.php`
  - `jobs/admin/locations/index.blade.php`
- **Content**:
  - CRUD forms for categories & locations.
  - List with edit/delete.

---

**1.3.4 Admin Plans & Job Posting Fees**

- **File**: `jobs/admin/plans/index.blade.php`
- **Content**:
  - List job plans (Free, Starter, Pro, etc.).
  - Edit form:
    - Name, price, number of jobs, duration.
  - Toggle active/inactive.

---

### 1.4 Shared Components (Blade Partials)

Under `jobs/components/`:

- `job_card.blade.php`
- `filter_bar.blade.php`
- `pagination.blade.php`
- `candidate_card.blade.php`
- `ats_stage_column.blade.php`
- `calendar_widget.blade.php`

---

### 1.5 JavaScript Modules (Laravel Side)

Under `resources/js/jobs/`:

- `jobsSearch.js` – search & filter.
- `jobDetail.js` – save job, apply button interactions.
- `jobApplyWizard.js` – multi-step application.
- `cvBuilder.js` – dynamic CV sections.
- `coverLetterEditor.js` – rich text & tags.
- `employerDashboard.js` – charts & stats.
- `atsBoard.js` – drag-and-drop pipeline.
- `screeningBuilder.js` – build screening questionnaires.
- `interviewCalendar.js` – manage interview slots.
- `adminJobsDashboard.js` – admin charts & filters.

---

## 2. Flutter Mobile – Screens, Widgets, State & Menu

All mobile jobs UI lives in `jobs_flutter_addon`.

### 2.1 Structure

- `lib/jobs_flutter_addon.dart`
  - Exports:
    - Routes map
    - Menu items
- `lib/src/pages/`
- `lib/src/models/`
- `lib/src/services/`
- `lib/src/state/`
- `lib/src/widgets/`
- `lib/src/menu.dart`

---

### 2.2 Job Seeker – Mobile Screens

**2.2.1 JobsHomeScreen**

- **File**: `lib/src/pages/seeker/jobs_home_screen.dart`
- **Purpose**: Entry screen with recommended and browsing.
- **Functions**:
  - Fetch recommended jobs + recent searches.
  - Navigate to search & filters.

- **UI**:
  - `AppBar(title: Text('Jobs'))`
  - Sections:
    - “Recommended for you” (horizontal list of job cards).
    - “Recently viewed” (list).
    - Button: “Browse all jobs”.

---

**2.2.2 JobSearchScreen**

- **File**: `job_search_screen.dart`
- **Functions**:
  - Keyword + location search.
  - Filter panel.
  - Infinite scroll results.

- **UI**:
  - Top search bar (title + location).
  - Filter icon (opens modal bottom sheet).
  - `ListView` for job cards.
  - Pull-to-refresh.

---

**2.2.3 JobDetailScreen**

- **File**: `job_detail_screen.dart`
- **Functions**:
  - Fetch job details.
  - Save/unsave job.
  - Start application flow.

- **UI**:
  - Scrollable page:
    - Job header.
    - Description, requirements, benefits.
  - Persistent bottom bar:
    - “Save” icon.
    - “Apply” primary button.

---

**2.2.4 JobApplyScreen**

- **File**: `job_apply_screen.dart`
- **Functions**:
  - Multi-step application (similar to web).
  - Validate and submit to API.

- **UI**:
  - `Step`-like layout (or custom with index).
  - Each step as separate widget.
  - Back/Next buttons at bottom.
  - Progress indicator.

---

**2.2.5 SavedJobsScreen**

- **File**: `saved_jobs_screen.dart`
- **Functions**:
  - Fetch saved jobs.
  - Unsave or start applying.

- **UI**:
  - Simple `ListView` + trailing actions.

---

**2.2.6 MyApplicationsScreen**

- **File**: `my_applications_screen.dart`
- **Functions**:
  - Fetch user’s applications.
  - Group by status.

- **UI**:
  - `TabBar`: All / In progress / Interview / Rejected / Hired.
  - `ListView` with small timeline indicators.

---

**2.2.7 ApplicationDetailScreen**

- **File**: `application_detail_screen.dart`
- **Functions**:
  - Fetch application details from API.
  - Show timeline, interview invites, status.

- **UI**:
  - Timeline widget.
  - Info sections.

---

**2.2.8 CvListScreen & CvEditScreen**

- **Files**:
  - `cv_list_screen.dart`
  - `cv_edit_screen.dart`
- **Functions**:
  - List CVs, create, edit.
  - Add/remove experience, education.

- **UI**:
  - CV list: simple cards.
  - Edit:
    - Scroll view with form sections.
    - Add section buttons.
    - Floating “Save” button.

---

**2.2.9 CoverLetterEditScreen**

- **File**: `cover_letter_edit_screen.dart`
- **Functions**:
  - Create/edit cover letters.
  - Insert smart tags for company/job.

- **UI**:
  - Text field for title.
  - Multiline text area with actions.

---

### 2.3 Employer – Mobile Screens

**2.3.1 EmployerDashboardScreen**

- **File**: `employer_dashboard_screen.dart`
- **Functions**:
  - Fetch employer KPIs and recent jobs.

- **UI**:
  - Cards for metrics.
  - Recent jobs list with quick stats.

---

**2.3.2 EmployerJobsListScreen**

- **File**: `employer_jobs_list_screen.dart`
- **Functions**:
  - List employer jobs.
  - Filter by status.
  - Navigate to create/edit/ATS board.

- **UI**:
  - Filter chips for status.
  - Job cards with small badges.

---

**2.3.3 JobCreateEditScreen (Wizard)**

- **File**: `job_create_edit_screen.dart`
- **Functions**:
  - Multi-step job posting form.
  - Save draft & publish.

- **UI**:
  - Stepper at top.
  - Forms separated into steps (as per web wizard).
  - Save & Next/Back buttons.

---

**2.3.4 EmployerCompanyProfileScreen**

- **File**: `employer_company_profile_screen.dart`
- **Functions**:
  - Edit company details.

- **UI**:
  - Form fields with validations, Save button.

---

**2.3.5 AtsBoardScreen (Per Job)**

- **File**: `ats_board_screen.dart`
- **Functions**:
  - Fetch pipeline stages and candidates.
  - Move candidates between stages (tap + menu, or long-press & drag).

- **UI**:
  - Horizontal scroll of columns.
  - Each column is `ListView` of candidate cards.
  - Candidate card tap → CandidateDetailScreen.

---

**2.3.6 CandidateDetailScreen**

- **File**: `candidate_detail_screen.dart`
- **Functions**:
  - View applicant info, notes, screening answers.
  - Move stage, add notes, tags.
  - Invite to interview.

- **UI**:
  - Top: candidate avatar/name/headline.
  - Sections: Profile, Application, Notes, Tags.
  - Actions row at bottom.

---

**2.3.7 ScreeningConfigScreen**

- **File**: `screening_config_screen.dart`
- **Functions**:
  - Build/edit screening templates.

- **UI**:
  - List of questions.
  - Add question button.
  - Question type selector.
  - Knockout toggle.

---

**2.3.8 EmployerInterviewScheduleScreen**

- **File**: `employer_interview_schedule_screen.dart`
- **Functions**:
  - Show upcoming interviews.
  - Create/reschedule.

- **UI**:
  - Calendar-like view (agenda list per day).
  - Card for each interview.

---

**2.3.9 EmployerBillingScreen**

- **File**: `employer_billing_screen.dart`
- **Functions**:
  - Show plan & credits.
  - List payment history (summary).

---

### 2.4 Flutter Menu & Navigation

**File**: `lib/src/menu.dart`

Expose entries like:

For job seekers:

- `MenuItem('Jobs', route: '/jobs/home', icon: Icons.work_outline)`
- `MenuItem('Saved Jobs', route: '/jobs/saved', icon: Icons.bookmark_border)`
- `MenuItem('My Applications', route: '/jobs/applications', icon: Icons.assignment_outlined)`
- `MenuItem('My CVs', route: '/jobs/cv', icon: Icons.description_outlined)`

For employers (conditionally shown if role is employer):

- `MenuItem('My Jobs', route: '/employer/jobs', icon: Icons.business_center_outlined)`
- `MenuItem('ATS', route: '/employer/ats', icon: Icons.dashboard_customize_outlined)`
- `MenuItem('Interviews', route: '/employer/interviews', icon: Icons.event_note_outlined)`
- `MenuItem('Billing', route: '/employer/billing', icon: Icons.credit_card_outlined)`

Also export a routes map:

- `/jobs/home`, `/jobs/search`, `/jobs/detail/:id`, `/jobs/apply/:id`, `/jobs/saved`, `/jobs/applications`, etc.
- `/employer/dashboard`, `/employer/jobs`, `/employer/jobs/create`, `/employer/ats/:jobId`, etc.

Host app wires these into its global navigation (tabs/drawer).

---

### 2.5 Styling & UX (Web + Mobile)

**General Principles:**

- Use existing **brand typography and colours**.
- Clean, professional, LinkedIn-style look.
- Minimal clutter, clear call-to-actions.

**Web:**

- **Layout**:
  - Jobs search: filters left (or top on mobile), results right.
  - Employer dashboards: metrics at top, tables below.
- **Typography**:
  - H1 for page titles; H2/H3 for section headings.
- **Spacing**:
  - Use consistent spacing (e.g. 8/16/24 px).
- **Buttons**:
  - Primary: for main actions (Apply, Post Job).
  - Secondary: for navigation/back.

**Mobile:**

- `Scaffold` + `AppBar`.
- Bottom fixed CTA bars where needed (Apply, Save).
- Single-column layout with scroll.
- Use `Card`, `ListTile`, `Chip`, `TabBar`, `BottomSheet` for modern UX.

---

## 3. Interactivity, CRUD & Logic Flows

### 3.1 CRUD Overview

**Jobs (Employer)**

- Create: via multi-step wizard.
- Read: employer job list & job detail.
- Update: edit job fields if not expired.
- Delete/Archive: close job (remove from active jobs).

**Applications**

- Job seeker:
  - Create: apply for job.
  - Read: list & detail of applications.
  - Update: withdraw application (if allowed).
- Employer:
  - Read: candidates per job, detail.
  - Update: move stage, add notes, schedule interviews.

**CVs & Cover Letters**

- Create multiple CVs & letters.
- Edit at any time.
- Delete/Archive.

**Screening Questions**

- Create templates.
- Edit/delete questions.
- Attach templates to job postings.

**Interviews**

- Create interview slots.
- Edit/reschedule.
- Cancel/archive.

---

### 3.2 Key Logic Flows

**Flow 1 – Job Seeker: Search & Apply**

1. User opens `JobsHomeScreen` / `jobs/index.blade.php`.
2. Enters keywords + location → hits Search.
3. Filters refine results.
4. Opens `JobDetail` view.
5. Clicks “Apply Now”:
   - Flows through application steps, attaches CV & cover letter, answers screening.
6. After submission, they see application in **My Applications** with status “Applied”.

---

**Flow 2 – Employer: Post a Job**

1. Employer opens **Employer Jobs Dashboard**.
2. Clicks “Post a Job”.
3. Completes wizard:
   - Basics → Details → Compensation → Screening → Publish.
4. On completion, job status becomes “Open”.
5. Job appears in listings and receives applications.

---

**Flow 3 – ATS Pipeline Management**

1. Employer opens ATS Board for a job.
2. Sees candidates in columns by stage.
3. Drags candidate from “Applied” to “Screening”.
4. System updates stage via API.
5. Candidate sees status update in **My Applications** (e.g. “In screening”).

---

**Flow 4 – Schedule Interview**

1. Employer opens Candidate Detail.
2. Clicks “Schedule Interview”.
3. Picks date/time & interviewers.
4. Interview invite created:
   - Candidate sees it in Applications/Interviews area.
   - Interview appears in Employer Interview Calendar.

---

**Flow 5 – Manage Plans & Credits**

1. Employer opens Billing page.
2. Sees current plan/credits.
3. Chooses new plan or buys credits.
4. After confirmation, UI updates credits & plan info.

---

By following this specification, the agent must:

- Implement **all listed Blade views and partials** with the described functions and JS.
- Implement **all listed Flutter screens**, widgets, state management, and API calls.
- Ensure smooth **navigation, CRUD, and flows** for both job seekers and employers.
- Match the host platform’s **design system** for a polished, production-ready Jobs & ATS experience on web and mobile.
