# Agent Instructions – Advertisement Package (Laravel + Flutter)
_UI, Views & Screens Specification_

## Overall Goal

Build a complete **Ads Manager** experience for:

1. **Laravel web app** (Blade views + JS), and  
2. **Flutter mobile addon** (`advertisement_flutter_addon`),

that lets advertisers:

- Create and manage ad campaigns and creatives.
- Configure targeting, budget, and schedule.
- View performance metrics and forecasts.
- See their ads served across placements (feed, profile, search, jobs, gigs, podcasts, webinars, networking, etc.)

And lets admins:

- Review and moderate ads.
- Manage global ad settings, limits, and keyword pricing.

> ⚠️ Do **not** add or touch any binary files (images, fonts, compiled bundles, `.exe`, `.dll`, `.so`, `.apk`, `.ipa`, etc.). Only templates, Dart/JS/TS, CSS/SCSS, and config.

---

## 1. Laravel Web – Blade Views & JS

### 1.1 Advertiser-Facing Blade Views

All advertiser views should live under:
`resources/views/vendor/advertisement/advertiser/`

**1.1.1 Ads Main Dashboard**

- **File**: `advertiser/dashboard.blade.php`
- **Purpose**:
  - Overview of advertiser performance and quick access to key actions.
- **Content**:
  - Top KPI cards:
    - Total spend (selected period)
    - Impressions
    - Clicks
    - Conversions
    - Average CPC, CPA, CPM
  - Line chart: spend vs impressions vs clicks over time.
  - Table: top 5 campaigns (name, status, impressions, clicks, CTR, spend).
  - CTAs:
    - “Create Campaign” button (primary).
    - “View All Campaigns” (secondary).

- **JS behaviour**:
  - Date range picker to reload KPIs and chart.
  - Async loading of chart data via AJAX.
  - On campaign row click → navigate to campaign detail page.

---

**1.1.2 Campaign List Page**

- **File**: `advertiser/campaigns/index.blade.php`
- **Purpose**:
  - List all campaigns with filtering and bulk actions.
- **Content**:
  - Filters:
    - Search by campaign name.
    - Status dropdown: Active / Paused / Completed / Draft.
    - Objective (optional).
    - Date range.
  - Campaign table:
    - Columns: Name, Status, Objective, Start Date, End Date, Impressions, Clicks, CTR, Spend, Actions.
  - Actions:
    - Row actions: View, Edit, Pause/Resume, Duplicate, Archive.
  - Button: “Create Campaign”.

- **JS behaviour**:
  - Filter form triggers AJAX reload of table.
  - Row actions handled via AJAX where possible (pause/resume, archive).
  - Pagination without full page reload (AJAX or Livewire/inertia-style pattern).

---

**1.1.3 Campaign Detail Page**

- **File**: `advertiser/campaigns/show.blade.php`
- **Purpose**:
  - In-depth view of a single campaign, its ads and performance.
- **Content**:
  - Header:
    - Campaign name, status badge, objective, date range.
    - Buttons: Edit, Pause/Resume, Duplicate.
  - Tabs:
    - Overview
    - Ads (Creatives)
    - Targeting
    - Budget & Schedule
    - Performance
  - **Overview tab**:
    - KPI cards (for this campaign only).
    - Chart: impressions/clicks/conversions over time.
  - **Ads tab**:
    - List of creatives with thumbnail / type / status.
    - “Create New Ad” button.
  - **Targeting tab**:
    - Read-only view of configured targeting (gender, locations, interests, keywords, etc.).
  - **Budget & Schedule tab**:
    - Read-only budget & schedule (daily/lifetime, start/end date).
  - **Performance tab**:
    - Table of metrics by placement or by ad.

- **JS behaviour**:
  - Tab navigation with URL hash state (`#overview`, `#ads`, etc.).
  - Chart data loaded via AJAX according to selected date range.
  - Pause/Resume actions confirmed via modal, executed via AJAX.

---

**1.1.4 Campaign Create/Edit Wizard**

- **File**: `advertiser/campaigns/wizard.blade.php`
- **Purpose**:
  - Multi-step form for creating/editing campaigns.
- **Steps (screens inside same Blade)**:
  1. **Objective & Naming**
     - Fields: Campaign name (input), Objective (select).
  2. **Audience / Targeting**
     - Gender (multi-select).
     - Locations (country + optional region/city).
     - Interests/tags.
     - Keywords (+ match type if applicable).
  3. **Placements**
     - Checkboxes for:
       - Feed/timeline
       - Profile
       - Search
       - Jobs
       - Gigs/Projects
       - Podcasts
       - Webinars
       - Networking
     - Placement-specific notes (e.g. recommended aspect ratio).
  4. **Budget & Schedule**
     - Budget: Daily / Lifetime.
     - Amount fields.
     - Start date/time, End date/time.
     - Bidding model: CPC / CPA / CPM.
  5. **Review & Confirm**
     - Summary of all previous steps.
     - “Create Campaign” / “Save Changes” button.

- **JS behaviour**:
  - Client-side validation on each step.
  - Stepper navigation (Next/Back) using JS; store form state in hidden fields.
  - Live budget & reach estimation preview panel (if forecast API is available).
  - Disable final submit until all required fields are valid.

---

**1.1.5 Ad Creative List Page**

- **File**: `advertiser/creatives/index.blade.php`
- **Purpose**:
  - List all creatives for a given campaign (or advertiser).
- **Content**:
  - Filters: campaign, type (video/banner/text/search), status.
  - Cards or table of creatives:
    - Thumbnail/preview.
    - Name.
    - Type.
    - Status.
    - Key metrics (impressions, clicks, CTR).
    - Actions: Edit, Duplicate, Pause, Archive.

- **JS behaviour**:
  - Quick preview modal for ad.
  - AJAX actions for Pause/Archive.

---

**1.1.6 Ad Creative Create/Edit Form**

- **File**: `advertiser/creatives/edit.blade.php`
- **Purpose**:
  - Create or edit single ad creative.
- **Content**:
  - Selector for creative type: Text / Banner / Video / Search / Recommendation.
  - For text/search ad:
    - Headline, description, final URL.
  - For banner/video:
    - Upload or select asset (use existing asset management UI).
    - Headline, description, call-to-action button text.
    - Destination URL.
  - Ad preview panel (live updating).
  - Attach to campaign/placement.
  - Save as Draft / Activate.

- **JS behaviour**:
  - Live preview update on form input.
  - Client-side dimension/length checks (e.g. title length, description length).
  - Optional dropzone-like upload for media (not actually uploading binaries in this spec, just UI-level).

---

**1.1.7 Keyword Planner Page**

- **File**: `advertiser/keyword_planner/index.blade.php`
- **Purpose**:
  - Discover keywords and see estimated CPC/CPA/CPM and volume.
- **Content**:
  - Search bar: enter keyword(s) or URL.
  - Filters: location, language (optional).
  - Results table:
    - Keyword.
    - Suggested bid (CPC).
    - Estimated conversions/CPA.
    - Impressions volume.
    - Add to campaign list (checkbox or “Add” button).
  - “Export to Campaign” button to push selected keywords to an existing campaign.

- **JS behaviour**:
  - AJAX search for keyword ideas.
  - Debounced input.
  - Bulk select/unselect.

---

**1.1.8 Forecast / Simulation Page**

- **File**: `advertiser/forecast/index.blade.php`
- **Purpose**:
  - Show predicted reach, clicks, conversions and cost for chosen inputs.
- **Content**:
  - Form inputs:
    - Daily budget slider.
    - Campaign duration.
    - Targeting summary (or attach to existing campaign).
  - Forecast results:
    - Cards: expected impressions, clicks, conversions.
    - Slider/graph showing cost vs results.
  - Button: “Apply these settings to Campaign”.

- **JS behaviour**:
  - Real-time update of forecast as user adjusts sliders.
  - AJAX request to forecast endpoint when inputs change.
  - Graph rendering (using existing chart library).

---

**1.1.9 Advertiser Billing & Settings Page**

- **File**: `advertiser/settings/billing.blade.php`
- **Purpose**:
  - Manage advertiser billing profile and see invoices.
- **Content**:
  - Billing info form: company name, address, VAT (if used).
  - Table of past invoices/charges.
  - Current spend limit and usage.

- **JS behaviour**:
  - Inline save with optimistic UI (or full form submit).
  - Date-range filter for invoices.

---

### 1.2 Admin-Facing Blade Views

All admin views should live under:
`resources/views/vendor/advertisement/admin/`

**1.2.1 Admin Ads Dashboard**

- **File**: `admin/dashboard.blade.php`
- **Content**:
  - Global KPIs:
    - Total platform ad revenue.
    - Total impressions, clicks, conversions.
  - Moderation queue count (pending ads).
  - Top advertisers.
  - Chart: revenue over time.

---

**1.2.2 Ads Moderation Queue**

- **File**: `admin/moderation/index.blade.php`
- **Content**:
  - Table of creatives awaiting review:
    - Advertiser, campaign, ad name, type, created at, flagged reason (if any).
  - Actions:
    - Approve, Reject (with reason), View preview.
  - Filter: status (pending/approved/rejected), ad type.

- **JS behaviour**:
  - Approve/Reject via AJAX, with confirmation modals.
  - Preview opens in modal.

---

**1.2.3 Global Settings Page**

- **File**: `admin/settings/index.blade.php`
- **Content**:
  - Sections:
    - Default budgets & limits.
    - Default keyword pricing rules.
    - Enable/disable ad types and placements.
    - Compliance checks toggles (e.g. require manual approval).
  - Save buttons per section.

- **JS behaviour**:
  - Tabbed layout for sections.
  - Success toast after saves.

---

**1.2.4 Keyword Pricing Management**

- **File**: `admin/keyword_pricing/index.blade.php`
- **Content**:
  - Table: keyword / base CPC / CPA / CPM / last updated.
  - Filter by keyword.
  - Action: Edit row (inline or separate form).

- **JS behaviour**:
  - Inline editing of pricing with save on blur or click.
  - Pagination.

---

**1.2.5 Advertiser Management**

- **File**: `admin/advertisers/index.blade.php`
- **Content**:
  - Table: advertiser name, status, total spend, active campaigns, actions.
  - View detail page for an advertiser (campaigns, billing, flags).

---

### 1.3 Shared Ad Placements (Blade Partials)

These are Blade partials used by other modules (feed, profile, jobs, gigs, etc.).

- **Feed Ad Card Component**
  - `components/ad_feed_card.blade.php`
- **Sidebar / Banner Ad Component**
  - `components/ad_banner.blade.php`
- **Search Results Ad Component**
  - `components/ad_search_result.blade.php`

Each partial receives an `$ad` object (or DTO) and renders according to type and placement style.

---

### 1.4 JavaScript Requirements (Laravel Side)

- Use the host app’s build pipeline (e.g. Vite/Mix).
- Place scripts under: `resources/js/advertisement/`.

**Core JS modules:**

- `advertisement/apiClient.js`
  - Generic wrapper for ads AJAX calls.

- `advertisement/dashboard.js`
  - Fetch KPI and chart data.
  - Bind date pickers and filter changes.

- `advertisement/campaigns.js`
  - Handle filter forms and AJAX table reload.
  - Pause/Resume/Duplicate campaign actions.

- `advertisement/wizard.js`
  - Multi-step wizard logic.
  - Client-side validation.
  - Live preview for budget/reach block (if forecast is available).

- `advertisement/creatives.js`
  - Ad preview logic.
  - Dynamic form sections when selecting ad type.

- `advertisement/keyword_planner.js`
  - Debounced search.
  - Results fetch and table update.
  - Bulk selection & export.

- `advertisement/forecast.js`
  - Slider inputs.
  - Throttled calls to forecast endpoint.
  - Chart updates.

- `advertisement/admin.js`
  - Moderation actions (approve/reject).
  - Settings tabs + save.

---

## 2. Flutter Mobile – Screens, Widgets, State & Styling

All mobile ads UI lives inside the `advertisement_flutter_addon` package.

### 2.1 Core Structure

- `lib/advertisement_flutter_addon.dart`
  - Exports:
    - Routes map.
    - Menu items.
    - Root widgets/screens.

- `lib/src/pages/` – Screens.
- `lib/src/models/` – DTOs for campaigns, creatives, metrics, etc.
- `lib/src/services/` – API clients.
- `lib/src/state/` – BLoCs/Cubits/Providers.
- `lib/src/widgets/` – reusable widgets (cards, charts, input controls).
- `lib/src/menu.dart` – menu configuration.

---

### 2.2 Advertiser Mobile Screens

**2.2.1 AdsHomeScreen**

- **File**: `lib/src/pages/ads_home_screen.dart`
- **Purpose**:
  - Overview dashboard for mobile advertisers (similar to web dashboard).
- **Functions**:
  - Fetch KPIs and recent campaigns via API.
  - Show summary cards and quick actions:
    - “Create Campaign”.
    - “View Campaigns”.
- **UI**:
  - AppBar: “Ads Manager”.
  - Body:
    - Scrollable.
    - Cards for KPIs.
    - Small chart widget for trends.
    - List of top 3 campaigns.

---

**2.2.2 CampaignListScreen**

- **File**: `campaign_list_screen.dart`
- **Functions**:
  - Fetch campaigns list with filters.
  - Pull-to-refresh.
  - Tap to open `CampaignDetailScreen`.
  - FAB: “+ Campaign”.

- **UI**:
  - Search bar at top.
  - Filter chips (status).
  - `ListView` of campaign cards.

---

**2.2.3 CampaignDetailScreen**

- **File**: `campaign_detail_screen.dart`
- **Functions**:
  - Fetch campaign detail by ID.
  - Display tabs:
    - Overview
    - Ads
    - Targeting
    - Budget & Schedule
    - Performance
  - Actions:
    - Pause/Resume.
    - Edit (navigate to wizard in edit mode).

- **UI**:
  - `TabBar` + `TabBarView`.
  - Each tab uses layout similar to web but adapted to mobile.

---

**2.2.4 CampaignWizardScreen**

- **File**: `campaign_wizard_screen.dart`
- **Purpose**:
  - Multi-step wizard to create/edit campaigns on mobile.
- **Steps**:
  1. Objective & name (TextFields, DropdownButton).
  2. Audience/Targeting (gender toggles, country/region selectors, tags/keywords input with chips).
  3. Placements (checkbox list tiles).
  4. Budget & Schedule (sliders and DatePickers).
  5. Review (read-only summary + Submit button).

- **Functions**:
  - Maintain wizard state in Cubit/provider.
  - Validate on each step.
  - Call API to create/update campaign.

- **UI**:
  - `Stepper`-like layout or custom step indicator at top.
  - Bottom buttons: Back / Next / Save.

---

**2.2.5 CreativeListScreen**

- **File**: `creative_list_screen.dart`
- **Functions**:
  - List creatives for selected campaign.
  - Filter by type/status.
  - Navigate to `CreativeEditScreen`.

- **UI**:
  - `ListView` of cards with preview icon, type badge, metrics.

---

**2.2.6 CreativeEditScreen**

- **File**: `creative_edit_screen.dart`
- **Functions**:
  - Create/edit ad creative.
  - Show relevant fields for each creative type.
  - Upload/select media (UI only; uses host app’s file picker integration).
  - Validate fields.
  - Call API to save.

- **UI**:
  - Drop-down for ad type.
  - Dynamic fields (if text, show headline/description; if banner/video, show media picker).
  - Preview at bottom.

---

**2.2.7 KeywordPlannerScreen**

- **File**: `keyword_planner_screen.dart`
- **Functions**:
  - Search keyword ideas.
  - Filter by location.
  - Add selected keywords to a campaign.

- **UI**:
  - TextField (search) with search icon.
  - Dropdown for location.
  - `ListView` of keyword rows with metrics and a checkbox.

---

**2.2.8 ForecastScreen**

- **File**: `forecast_screen.dart`
- **Functions**:
  - Adjust budget/duration.
  - Call forecast API.
  - Display predicted impressions, clicks, conversions, and cost.

- **UI**:
  - Sliders for budget and duration.
  - Cards for forecast results.
  - Graph widget (line or bar chart).

---

**2.2.9 AdsReportsScreen**

- **File**: `ads_reports_screen.dart`
- **Functions**:
  - Show charts and tables of campaign performance.
  - Allow switching between campaigns/periods.

- **UI**:
  - Date range picker.
  - Drop-down for campaign selection.
  - Chart + summary stats.

---

### 2.3 Mobile Admin Screens (Optional / If Needed in App)

If admins manage from mobile:

- `admin_ads_dashboard_screen.dart`
- `admin_moderation_queue_screen.dart`
- Each with list views and simple moderation actions.

---

### 2.4 Flutter Menu Integration

**File**: `lib/src/menu.dart`

Expose menu entries such as:

- `MenuItem('Ads Manager', route: '/ads/home', icon: Icons.campaign_outlined)`
- `MenuItem('Campaigns', route: '/ads/campaigns', icon: Icons.list_alt_outlined)`
- `MenuItem('Ads Reports', route: '/ads/reports', icon: Icons.analytics_outlined)`

This file must:

- Export a list of menu items for integration into the host app’s navigation (side drawer / bottom nav / profile menu).
- Export a `Map<String, WidgetBuilder>` of routes for:
  - `/ads/home`
  - `/ads/campaigns`
  - `/ads/campaigns/:id`
  - `/ads/campaigns/create`
  - `/ads/creatives`
  - `/ads/keyword-planner`
  - `/ads/forecast`
  - `/ads/reports`

---

### 2.5 Styling & UX (Web + Mobile)

**General principles:**

- Match the host platform’s existing design system.
- **Typography**:
  - Reuse global font settings.
  - Hierarchy: large bold titles, medium section headers, regular body text.
- **Colours**:
  - Primary brand colour for call-to-action buttons.
  - Neutral background cards (`#ffffff`), soft borders/shadows.
  - Status badges (green = Active, grey = Paused, red = Rejected).
- **Spacing**:
  - Use consistent spacing scale (e.g. 8/16/24 px).
  - On web: comfortable gaps between cards/tables.
  - On mobile: vertical spacing to avoid clutter.

**Buttons & Widgets:**

- Primary button: filled style (for main action, e.g. “Create Campaign”).
- Secondary: outlined or text button (e.g. “Cancel”, “Back”).
- Use icon buttons for small actions (edit, more options) with tooltips (web) or long-press hints (mobile).

**Layout:**

- Web:
  - Use responsive grid, collapsible side navigation.
  - Keep dashboards scannable: KPIs at top, charts mid, tables bottom.
- Mobile:
  - Use `Scaffold` + `AppBar` + `FloatingActionButton` where appropriate.
  - Use tabs and bottom sheets for options rather than overloaded screens.

---

## 3. Interactivity, CRUD & Logic Flows

### 3.1 CRUD Behaviour (Web + Mobile)

For **Campaigns**:

- Create:
  - Via wizard (web + mobile).
- Read:
  - Listing (with filters + sorting).
  - Detail view (overview, ads, targeting, budget/schedule, performance).
- Update:
  - Edit via wizard (all fields) or partial updates (status, name).
- Delete/Archive:
  - Archive campaigns instead of hard delete; hide from active listings.

For **Creatives**:

- Create & Attach to campaign.
- Read listing & preview.
- Update (text/media/URL).
- Pause/Resume/Archive.

For **Keyword Planner Entries**:

- Read from API.
- Add selected keywords into campaign-level targeting/UI.

For **Forecasts**:

- Read-only results from API based on user inputs.

---

### 3.2 Key Logic Flows

**Flow 1 – Create a New Campaign**

1. Advertiser opens Ads Dashboard → taps “Create Campaign”.
2. Wizard Step 1: sets name + objective → Next.
3. Step 2: selects audience (gender, location, interests, keywords).
4. Step 3: chooses placements.
5. Step 4: sets budget, schedule, bidding model.
6. Step 5: reviews summary → submits.
7. System creates campaign → redirects to Campaign Detail page.
8. User then creates creatives for the campaign.

**Flow 2 – Create Ad Creative**

1. From Campaign Detail (Ads tab), click “Create New Ad”.
2. Select ad type (text/banner/video/search).
3. Fill in required fields (titles, descriptions, URLs, media selector).
4. Preview updates live.
5. Save ad as Active or Draft.
6. Ad begins serving once approved by admin (if moderation enabled).

**Flow 3 – Monitor Performance**

1. Advertiser opens Campaign Detail → Performance tab.
2. Adjusts date range → chart + stats reload.
3. Uses Ads Reports screen for deeper multi-campaign comparison.

**Flow 4 – Use Keyword Planner**

1. Advertiser opens Keyword Planner.
2. Enters seed keyword(s) and optional location.
3. Results (keywords with estimated CPC/volume) display.
4. Selects relevant keywords → “Add to Campaign”.
5. Targeting tab for that campaign is updated with those keywords.

**Flow 5 – Forecast / Simulation**

1. Advertiser opens Forecast screen.
2. Selects campaign or defines target profile (audience).
3. Adjusts budget/duration sliders.
4. Forecast API responds with updated reach/clicks/conversion estimates.
5. Advertiser optionally saves these settings into a campaign.

**Flow 6 – Admin Moderation**

1. Admin opens Ads Moderation Queue.
2. Clicks an ad → preview modal.
3. Approves or Rejects (with reason).
4. Status reflected in advertiser’s UI and serving eligibility.

---

By following this document, the agent must:

- Implement **all listed Blade views** and their JS behaviours in the Laravel package.
- Implement **all listed Flutter screens** with state management and API calls in the mobile addon.
- Ensure full **CRUD, interactivity, and UX flows** for campaigns, creatives, metrics, keyword planner, and forecasts.
- Wire up **menus and navigation** so that Ads features are discoverable and consistent across web and mobile.
