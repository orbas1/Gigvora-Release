# Agent Instructions – Freelance Package  
_UI, Views & Screens Specification (Laravel + Flutter)_

## Overall Goal

Build a complete **Freelance Marketplace** experience for:

1. **Laravel web app** (Blade views + JS), and  
2. **Flutter mobile addon** (`freelance_flutter_addon`),

covering:

- Freelancers & Clients (user upgrade)
- Gigs (fixed-price offers)
- Projects (bidding-style jobs)
- Bids & proposals
- Contracts & milestones
- Escrow & payouts
- Disputes & resolution
- Reviews & ratings
- Commission & fees visibility

This will sit **inside a social/LinkedIn-style platform**, sharing users but adding full freelance workflows.

> ⚠️ Do **not** add or touch binary files (images, fonts, compiled JS/CSS bundles, `.exe`, `.dll`, `.so`, `.apk`, `.ipa`, etc.). Only templates, Dart/JS/TS, CSS/SCSS and configuration.

---

## 1. Laravel Web – Blade Views, JS & Admin

Use these namespaces:

- User-facing: `resources/views/vendor/freelance/`
- Admin-facing: `resources/views/vendor/freelance/admin/`
- Shared components: `resources/views/vendor/freelance/components/`

### 1.1 Global – Role Upgrade / Freelance Onboarding

**1.1.1 Freelance Onboarding / Role Upgrade Page**

- **File**: `freelance/onboarding.blade.php`
- **Purpose**: Let normal users switch on **Freelancer** and/or **Client** capabilities.
- **Content**:
  - Hero section:
    - Title: “Start Freelancing” / “Hire Talent”.
    - Short explanation.
  - Two cards:
    - “Become a Freelancer” (headline, bullet benefits).
    - “Become a Client” (headline, bullet benefits).
  - Toggles/checks:
    - `I want to work as a freelancer`
    - `I want to hire freelancers`
  - Profile completeness bar (skills, hourly rate, timezone, bio for freelancers).
  - CTA button: “Save & Continue”.

- **JS** (`freelanceOnboarding.js`):
  - Toggle states persisted via AJAX.
  - Profile completeness calculation.
  - Validation before proceed (e.g. must fill basic profile fields).

---

### 1.2 Freelancer – User-Facing Blade Views

#### 1.2.1 Freelancer Dashboard

- **File**: `freelance/freelancer/dashboard.blade.php`
- **Purpose**: Overview of freelance activity.
- **Content**:
  - KPI cards:
    - Active gigs.
    - Active contracts.
    - Open proposals.
    - Earnings this month.
  - Charts (if available):
    - Earnings over last 6 months.
  - Lists:
    - “Open Contracts” (top 5).
    - “Latest Messages” (link to messaging system).
    - “Recommended Projects”.

- **JS** (`freelancerDashboard.js`):
  - Load charts using existing chart library.
  - Lazy load recommended projects.

---

#### 1.2.2 My Gigs List (Freelancer)

- **File**: `freelance/freelancer/gigs/index.blade.php`
- **Content**:
  - Header: “My Gigs” with “Create New Gig” button.
  - Filters:
    - Status: Draft / Active / Paused / Denied.
  - List/table:
    - Gig title.
    - Price (or price range).
    - Orders in queue.
    - Reviews (rating).
    - Status badge.
    - Actions: View, Edit, Pause/Activate, Duplicate.

- **JS**:
  - Status filters via AJAX.
  - Inline toggle Active/Pause.

---

#### 1.2.3 Gig Create/Edit Wizard

- **File**: `freelance/freelancer/gigs/wizard.blade.php`
- **Steps**:
  1. **Overview**:
     - Title, category, subcategory, tags.
  2. **Pricing**:
     - Fixed price / packages (Basic, Standard, Premium).
     - Delivery time.
     - Revisions.
  3. **Description & FAQ**:
     - Rich-text description.
     - Frequently asked questions (repeatable blocks).
  4. **Requirements**:
     - Client requirements/questions.
  5. **Preview & Publish**:
     - Summary card.
     - Fee breakdown (commission shown).
     - Publish or Save as Draft.

- **JS** (`gigWizard.js`):
  - Step navigation + validation.
  - Dynamic package support.
  - Add/remove FAQ and requirement items.
  - Calculate net earnings (price minus commission) and update in UI.

---

#### 1.2.4 Orders from Gigs (Freelancer Side)

- **File**: `freelance/freelancer/orders/index.blade.php`
- **Content**:
  - List of orders on gigs:
    - Client name.
    - Gig title.
    - Order amount.
    - Status (In Progress, Delivered, Completed, Cancelled, In Dispute).
    - Due date.
    - Actions: View, Deliver, Open Dispute.
- **Order Detail**: `freelance/freelancer/orders/show.blade.php`
  - Thread style:
    - Requirements.
    - Messages/attachments.
    - Milestones (if used).
    - Delivery submissions.
    - Escrow info panel.

- **JS**:
  - Live message updates via polling/websocket hook.
  - Timer to show days/hours left.

---

#### 1.2.5 Projects (Freelancer View – Bidding)

- **Project Browse Page**
  - **File**: `freelance/freelancer/projects/index.blade.php`
  - **Content**:
    - Filters: Category, Budget, Fixed/Hourly, Client rating.
    - List of available projects (title, short description, budget, proposals count, posted time).
    - “View project” button.

- **Project Detail (Freelancer)**
  - **File**: `freelance/freelancer/projects/show.blade.php`
  - **Content**:
    - Project description.
    - Budget & payment type (fixed/hourly).
    - Milestone hints.
    - Client info snippet.
    - Proposals count.
    - CTA: “Submit Proposal” or “Edit Proposal” (if already applied).
  - **JS**:
    - Expand/collapse long descriptions.
    - Show your proposal summary if existing.

---

#### 1.2.6 Submit Proposal / Bid

- **File**: `freelance/freelancer/projects/proposal.blade.php`
- **Content**:
  - Bid amount (fixed or hourly rate).
  - Estimated delivery time.
  - Cover letter / pitch (rich text).
  - Attachments UI (frontend only).
  - Optional: Milestone breakdown (planned phases).
  - Fee breakdown:
    - Shows platform commission and net earning.

- **JS** (`proposalForm.js`):
  - Auto-calc net earnings from bid minus commission.
  - Client-side validations.
  - Save draft proposal (optional).

---

#### 1.2.7 My Proposals List

- **File**: `freelance/freelancer/proposals/index.blade.php`
- **Content**:
  - Tabs: All / Pending / Accepted / Rejected.
  - Each row:
    - Project title.
    - Client.
    - Your bid.
    - Status.
    - Date submitted.
  - Actions: View Proposal, Withdraw (if allowed).

---

#### 1.2.8 Contracts & Milestones (Freelancer)

- **Contracts List**
  - **File**: `freelance/freelancer/contracts/index.blade.php`
  - Columns: Client, Project/Gig, Amount, Status, Next milestone due.
- **Contract Detail**
  - **File**: `freelance/freelancer/contracts/show.blade.php`
  - Sections:
    - Overview (terms, total amount).
    - Milestones timeline:
      - Title, amount, due date, status (Funded, In Progress, Submitted, Approved, Released).
    - Escrow panel (funding & release state).
    - Messages thread.
    - Buttons:
      - “Mark Milestone as Complete”.
      - “Request Release”.
      - “Open Dispute”.

- **JS** (`contractDetail.js`):
  - Timeline animations/indicators.
  - Milestone actions via AJAX.
  - Confirmation dialogs for critical actions.

---

### 1.3 Client – User-Facing Blade Views

#### 1.3.1 Client Dashboard

- **File**: `freelance/client/dashboard.blade.php`
- **Content**:
  - KPIs:
    - Open projects.
    - Active contracts.
    - Amount in escrow.
    - Total spent.
  - Lists:
    - Active contracts with status.
    - Open disputes (if any).
    - Recommended freelancers.

---

#### 1.3.2 Post Project Wizard (Client)

- **File**: `freelance/client/projects/wizard.blade.php`
- **Steps**:
  1. **Overview**:
     - Title, category, tags.
  2. **Scope**:
     - Describe work, deliverables, skills required.
  3. **Budget**:
     - Fixed price or hourly.
     - Budget range / max hourly rate.
  4. **Screening**:
     - Attach question set or custom questions.
  5. **Review & Publish**:
     - Summary.
     - Commission estimation (show fees).
     - Publish or Save Draft.

- **JS** (`projectWizard.js`):
  - Step validation.
  - Character counters.
  - Show service fee total (approx) based on budget.

---

#### 1.3.3 My Projects (Client View)

- **File**: `freelance/client/projects/index.blade.php`
- **Content**:
  - Filters: status (Draft / Open / In Progress / Completed / Closed).
  - List:
    - Title, proposals count, budget, status.
    - Actions: View, Edit, Close, Reopen.

---

#### 1.3.4 Project Detail – Proposals Management (Client)

- **File**: `freelance/client/projects/show.blade.php`
- **Content**:
  - Project info at top.
  - Tabs:
    1. **Proposals**:
       - Cards with freelancer name, rating, bid amount, delivery time, short pitch.
       - Buttons: View Proposal, Shortlist, Reject, Hire.
    2. **Overview**:
       - Project description & attachments.
    3. **Activity**:
       - Timeline of events (project posted, proposals, contract creation).

- **JS** (`projectProposals.js`):
  - Filter proposals by bid amount, rating, time.
  - Quick shortlist/reject via AJAX.
  - Confirm Hire → create Contract and redirect.

---

#### 1.3.5 Client Contracts & Escrow

- **Contracts List**
  - **File**: `freelance/client/contracts/index.blade.php`
- **Contract Detail**
  - **File**: `freelance/client/contracts/show.blade.php`
  - Content:
    - Same as freelancer but from client perspective.
    - Escrow panel:
      - Balance funded; actions:
        - “Fund Milestone”, “Release Payment”, “Request Refund”.
    - Dispute status (if any).
  - **JS**:
    - Payment action triggers (frontend only).

---

### 1.4 Escrow, Disputes, Reviews – Shared Views

#### 1.4.1 Escrow Overview Page

- **File**: `freelance/escrow/index.blade.php`
- **Content**:
  - Sections:
    - “Awaiting Funding”.
    - “Active Escrows”.
    - “Completed”.
  - Each item:
    - Contract/project, counterpart, amount, status.

---

#### 1.4.2 Dispute Centre

- **List Page**
  - **File**: `freelance/disputes/index.blade.php`
  - Tabs: Open / Resolved.
  - Each item: related contract, counterpart, open date, status.
- **Detail Page**
  - **File**: `freelance/disputes/show.blade.php`
  - Content:
    - Dispute summary.
    - Reason, side’s statements.
    - Evidence/attachments list (links).
    - Thread of dispute messages.
    - Resolution outcome (when closed).

- **JS** (`disputeCentre.js`):
  - Submit statement via AJAX.
  - Status badges update.

---

#### 1.4.3 Reviews & Ratings

- **Review Request/Modal**
  - **File**: `freelance/reviews/modal.blade.php`
- **Content**:
  - Star rating (1–5).
  - Short headline.
  - Comment textarea.
- **JS**:
  - Star rating widget.
  - Char counters.

---

### 1.5 Admin Views (Freelance)

Under `freelance/admin/`.

**1.5.1 Admin Freelance Dashboard**

- **File**: `freelance/admin/dashboard.blade.php`
- **Content**:
  - Metrics:
    - Total freelancers.
    - Total clients using freelance.
    - Active gigs/projects.
    - Total escrow volume.
    - Disputes open.
  - Charts:
    - Volume by month.
    - Disputes trend.

---

**1.5.2 Admin Gigs Management**

- **File**: `freelance/admin/gigs/index.blade.php`
- **Content**:
  - Table:
    - Gig title, freelancer, status, orders count, rating.
    - Actions: Approve, Disable, Feature.
  - Filters by status, category.

---

**1.5.3 Admin Projects Management**

- **File**: `freelance/admin/projects/index.blade.php`
- **Content**:
  - Table:
    - Title, client, status, proposals count, budget.
    - Actions: Close, Flag.

---

**1.5.4 Admin Disputes Management**

- **File**: `freelance/admin/disputes/index.blade.php`
- **Detail**: `freelance/admin/disputes/show.blade.php`
- **Content**:
  - View both parties’ statements, evidence.
  - Admin actions:
    - Decide outcome (refund/partial/split).
    - Add internal notes.

---

**1.5.5 Admin Fees & Commission Config**

- **File**: `freelance/admin/fees/index.blade.php`
- **Content**:
  - Commission settings (percentage tiers, min/max fee).
  - Saving and previewing fee calculation examples.

---

### 1.6 Shared Blade Components

Under `freelance/components/`:

- `gig_card.blade.php`
- `project_card.blade.php`
- `user_badge.blade.php` (freelancer/client info)
- `contract_milestones_timeline.blade.php`
- `pagination.blade.php`
- `filter_bar.blade.php`
- `dashboard_kpi_cards.blade.php`

---

### 1.7 JavaScript Modules (Laravel Side)

Under `resources/js/freelance/`:

- `freelanceOnboarding.js`
- `freelancerDashboard.js`
- `gigWizard.js`
- `projectWizard.js`
- `proposalForm.js`
- `contractDetail.js`
- `projectProposals.js`
- `disputeCentre.js`
- `feesPreview.js` (for showing commission breakdown)
- `adminFreelanceDashboard.js`

---

## 2. Flutter Mobile – Screens, Widgets, State & Menu

All mobile UI lives in `freelance_flutter_addon`.

### 2.1 Structure

- `lib/freelance_flutter_addon.dart`
  - Exposes routes & root widgets.
- `lib/src/pages/`
- `lib/src/models/`
- `lib/src/services/`
- `lib/src/state/`
- `lib/src/widgets/`
- `lib/src/menu.dart`

---

### 2.2 Global – Role Upgrade / Onboarding Screens

**2.2.1 FreelanceOnboardingScreen**

- **File**: `lib/src/pages/global/freelance_onboarding_screen.dart`
- **Functions**:
  - Toggle freelancer/client roles.
  - Make initial setup of skills, hourly rate, headline.
- **UI**:
  - Scrolling page with:
    - Big title.
    - Two toggles (Freelancer, Client).
    - Basic profile form.
    - Primary button “Continue”.
- **State**:
  - Local form state.
  - On submit → call API to upgrade roles.

---

### 2.3 Freelancer – Mobile Screens

**2.3.1 FreelancerDashboardScreen**

- **File**: `freelancer_dashboard_screen.dart`
- **Functions**:
  - Load KPI metrics and lists.
- **UI**:
  - `AppBar(title: Text('Freelancer'))`
  - Cards for metrics in a grid.
  - Lists “Active Contracts”, “Recent Proposals”.

---

**2.3.2 MyGigsScreen**

- **File**: `my_gigs_screen.dart`
- **Functions**:
  - Fetch user’s gigs.
  - Filter by status.
- **UI**:
  - Filter chips.
  - `ListView` of gig cards.
  - FAB: “New Gig”.

---

**2.3.3 GigEditScreen (Wizard)**

- **File**: `gig_edit_screen.dart`
- **Functions**:
  - Create/edit gig across steps.
- **UI**:
  - Stepper or top progress indicator.
  - Form pages: Overview, Pricing, Description/FAQ, Requirements, Preview.
  - Bottom fixed bar: Back / Next / Save & Exit.

---

**2.3.4 GigOrdersScreen & GigOrderDetailScreen**

- **Files**:
  - `gig_orders_screen.dart`
  - `gig_order_detail_screen.dart`
- **Functions**:
  - List and detail orders (as freelancer).
- **UI**:
  - Orders list with status chips.
  - Detail:
    - Requirements, messages thread, milestones, deliver button.

---

**2.3.5 ProjectsBrowseScreen**

- **File**: `projects_browse_screen.dart`
- **Functions**:
  - Browse open projects.
- **UI**:
  - Search/filter bar.
  - List of project cards.

---

**2.3.6 ProjectDetailScreen (Freelancer)**

- **File**: `project_detail_screen.dart`
- **Functions**:
  - Show details and existing proposal.
  - Launch proposal screen.
- **UI**:
  - Scrollable info.
  - Bottom bar: Save / Submit Proposal / Edit Proposal.

---

**2.3.7 ProposalEditScreen**

- **File**: `proposal_edit_screen.dart`
- **Functions**:
  - Create or edit proposal for project.
- **UI**:
  - Fields for amount, time, cover letter.
  - Fee breakdown component.
  - Save/Submit buttons.

---

**2.3.8 MyProposalsScreen**

- **File**: `my_proposals_screen.dart`
- **Functions**:
  - List proposals grouped by status.

---

**2.3.9 FreelancerContractsScreen & ContractDetailScreen**

- **Files**:
  - `freelancer_contracts_screen.dart`
  - `contract_detail_screen.dart` (shared with client variant with role-specific UI)
- **Functions**:
  - Show active & past contracts.
  - Manage milestones (mark complete, request release).
- **UI**:
  - Contract detail:
    - Sections: Overview, Milestones, Messages, Escrow.

---

### 2.4 Client – Mobile Screens

**2.4.1 ClientDashboardScreen**

- **File**: `client_dashboard_screen.dart`
- **Functions**:
  - Show spend KPIs, open projects, contracts.

---

**2.4.2 ClientProjectsScreen**

- **File**: `client_projects_screen.dart`
- **Functions**:
  - List client projects by status.
- **UI**:
  - Filter chips.
  - Project cards with proposals count.

---

**2.4.3 ProjectCreateEditScreen**

- **File**: `project_create_edit_screen.dart`
- **Functions**:
  - Multi-step new project wizard.
- **UI**:
  - Steps: Basics, Scope, Budget, Screening, Review.
  - Stepper with “Save draft” and “Publish”.

---

**2.4.4 ClientProjectDetailScreen**

- **File**: `client_project_detail_screen.dart`
- **Functions**:
  - Show project.
  - Manage proposals and hire.
- **UI**:
  - Tabs:
    - Overview
    - Proposals
    - Activity
  - Proposal cards with action buttons.

---

**2.4.5 ClientContractsScreen & ContractDetailScreen**

- **Same as freelancer but with client actions:**
  - Fund milestone, Release funds, Request refund.

---

### 2.5 Escrow, Disputes, Reviews – Mobile Screens

**2.5.1 EscrowOverviewScreen**

- **File**: `escrow_overview_screen.dart`
- **Functions**:
  - List escrows by status for current user.
- **UI**:
  - Tabs: Awaiting Funding / Active / Completed.

---

**2.5.2 DisputesListScreen & DisputeDetailScreen**

- **Files**:
  - `disputes_list_screen.dart`
  - `dispute_detail_screen.dart`
- **Functions**:
  - List & view disputes.
  - Add messages/evidence (links or text).
- **UI**:
  - Chat-style thread for statements.
  - Status indicator.

---

**2.5.3 ReviewDialog / Screen**

- **File**: `review_screen.dart` or modal widget.
- **Functions**:
  - Rate counterpart and leave feedback at contract completion.

---

### 2.6 Flutter Menu & Navigation

**File**: `lib/src/menu.dart`

Expose **role-aware menu items**:

For freelancers:

- `MenuItem('Freelance Dashboard', route: '/freelance/freelancer/dashboard', icon: Icons.dashboard_outlined)`
- `MenuItem('My Gigs', route: '/freelance/freelancer/gigs', icon: Icons.storefront_outlined)`
- `MenuItem('Browse Projects', route: '/freelance/freelancer/projects', icon: Icons.search_outlined)`
- `MenuItem('My Proposals', route: '/freelance/freelancer/proposals', icon: Icons.assignment_outlined)`
- `MenuItem('Contracts', route: '/freelance/freelancer/contracts', icon: Icons.work_outline)`

For clients:

- `MenuItem('Client Dashboard', route: '/freelance/client/dashboard', icon: Icons.dashboard_customize_outlined)`
- `MenuItem('My Projects', route: '/freelance/client/projects', icon: Icons.assignment_outlined)`
- `MenuItem('Contracts', route: '/freelance/client/contracts', icon: Icons.work_history_outlined)`
- `MenuItem('Escrow', route: '/freelance/escrow', icon: Icons.account_balance_wallet_outlined)`

Global:

- `MenuItem('Freelance Setup', route: '/freelance/onboarding', icon: Icons.settings_suggest_outlined)`

Export a `Map<String, WidgetBuilder>` routes map for all screens.

---

### 2.7 Styling & UX (Web + Mobile)

**Web**:

- Use host platform’s fonts & colour palette.
- **Jobs-like structure**:
  - Side filters, main content area.
  - Clear CTAs (e.g., “Post Project”, “Submit Proposal”).
- Add tooltips or small helper text where meaning isn’t obvious (e.g. commission).

**Mobile**:

- `Scaffold` with `AppBar`.
- Use `Card`, `ListTile`, `Chip`, `TabBar`, `BottomSheet` for filters.
- Fixed bottom action bars for critical actions:
  - Apply, Submit, Fund, Release.

Spacing, fonts, widgets must be consistent with platform style: 8/16/24 spacing, minimal colours, focus on readability.

---

## 3. Interactivity, CRUD & Logic Flows

### 3.1 CRUD Coverage

**Gigs**

- Create (wizard), edit, publish/unpublish, view, delete/archive.
- List per freelancer.

**Projects**

- Client: create, edit, publish, close, reopen.
- Freelancer: view, browse, search.

**Proposals**

- Freelancer: create, edit, withdraw.
- Client: view, shortlist, reject, accept/hire.

**Contracts & Milestones**

- Create from accepted proposal.
- Update milestone states (Funded → Submitted → Released).
- Close/complete.

**Escrow**

- Create on funding.
- Update on release/refund.
- List per user.

**Disputes**

- Open, add messages, resolve (admin).

**Reviews**

- Create at contract completion (both directions if required).

---

### 3.2 Key Logic Flows

**Flow A – New Freelancer Onboarding**

1. User opens Freelance Onboarding page/screen.
2. Selects role “Freelancer”.
3. Fills skills, hourly rate, summary.
4. Submits → API activates freelance profile.
5. Redirect to Freelancer Dashboard.

---

**Flow B – Client Posts a Project & Hires**

1. Client opens Post Project wizard.
2. Completes steps and publishes.
3. Freelancers browse and send proposals.
4. Client reviews proposals on Project Detail page.
5. Client selects a proposal → clicks “Hire”:
   - Contract is created.
   - Initial milestone is set + escrow requested.
6. Client funds milestone via escrow (front-end triggers).
7. Contract status becomes “In Progress”.

---

**Flow C – Freelancer Bids on Project**

1. Freelancer browses projects.
2. Opens Project Detail.
3. Clicks “Submit Proposal”.
4. Fills amount, time, cover letter; sees net earnings (after commission).
5. Submits proposal.
6. Proposal appears in My Proposals and on client’s side.

---

**Flow D – Milestone Delivery & Payment**

1. Freelancer completes work for a milestone.
2. On Contract Detail, marks milestone as “Submitted”.
3. Client reviews and accepts (or requests changes).
4. On acceptance, client clicks “Release Funds”.
5. Escrow releases funds; freelancer balance increases.
6. Milestone status updated to “Released”.

---

**Flow E – Dispute Handling**

1. Client or freelancer opens Contract Detail.
2. Clicks “Open Dispute”, selects reason & writes description.
3. Dispute created and shown in Dispute Centre.
4. Admin reviews and resolves via admin views.
5. Resolution outcome reflected in contract & escrow.

---

**Flow F – Review**

1. Upon contract completion:
   - User gets prompt to leave review.
2. Opens Review modal/screen.
3. Selects rating + comment.
4. Submits; review appears on counterpart’s profile.

---

By following this specification, the agent must:

- Implement **all Blade views & partials** plus the described JS for the Laravel package.
- Implement **all Flutter screens & routes**, with state management and API calls matching the backend.
- Ensure **end-to-end freelance flows** (from onboarding to project completion and review) work smoothly on both web and mobile, and feel native to the host social/LinkedIn-style platform.
