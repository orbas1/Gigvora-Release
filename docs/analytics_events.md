# Analytics Event Taxonomy

Standard event names are defined centrally in `config/permission_matrix.php` under the `analytics.events` key and published via `App\Support\Analytics\AnalyticsEventPublisher`. Use the same names in Flutter analytics to keep parity.

## Jobs

| Event | When | Properties |
| --- | --- | --- |
| `analytics.jobs.job.posted` | Job created/published | `job_id`, `company_id`, `role` |
| `analytics.jobs.application.submitted` | Application submitted | `job_id`, `application_id`, `role` |
| `analytics.jobs.candidate.shortlisted` | Candidate moved to shortlist | `job_id`, `application_id`, `role` |
| `analytics.jobs.offer.sent` | Offer issued | `job_id`, `application_id`, `role` |

## Freelance

| Event | When | Properties |
| --- | --- | --- |
| `analytics.freelance.dashboard.view` | Workspace loaded | `profile_id`, `role` |
| `analytics.freelance.role.switched` | Buyer/seller role toggled | `profile_id`, `role` |
| `analytics.freelance.favourite.toggled` | Favourite added/removed | `resource_id`, `resource_type`, `action` |
| `analytics.freelance.proposal.submitted` | Proposal submitted | `project_id`, `proposal_id`, `role` |
| `analytics.freelance.contract.created` | Contract opened | `contract_id`, `project_id`, `role` |
| `analytics.freelance.escrow.released` | Escrow released | `contract_id`, `release_amount`, `role` |

## Ads

| Event | When | Properties |
| --- | --- | --- |
| `analytics.ads.dashboard.view` | Ads dashboard loaded | `company_id`, `role` |
| `analytics.ads.campaign.created` | Campaign created | `campaign_id`, `budget` |
| `analytics.ads.budget.updated` | Budget changed | `campaign_id`, `budget` |
| `analytics.ads.report.viewed` | Report accessed | `campaign_id`, `range` |

## Talent & AI

| Event | When | Properties |
| --- | --- | --- |
| `analytics.talent_ai.admin.opened` | Admin console opened | `role` |
| `analytics.talent_ai.search.run` | Search executed | `query`, `filters`, `role` |
| `analytics.talent_ai.candidate.saved` | Candidate saved | `candidate_id`, `pool_id`, `role` |

## Interactive

| Event | When | Properties |
| --- | --- | --- |
| `analytics.interactive.session.created` | Live/webinar/networking session created | `session_id`, `type`, `role` |
| `analytics.interactive.session.joined` | Participant joins session | `session_id`, `type`, `role` |
| `analytics.interactive.recording.viewed` | Recording viewed | `session_id`, `type`, `role` |

## Utilities

| Event | When | Properties |
| --- | --- | --- |
| `analytics.utilities.reminder.created` | Reminder created | `reminder_id`, `scope`, `role` |
| `analytics.utilities.calendar.synced` | Calendar sync completed | `provider`, `status`, `role` |
| `analytics.utilities.saved_item.added` | Bookmark added | `item_type`, `item_id`, `role` |

## AI

| Event | When | Properties |
| --- | --- | --- |
| `analytics.ai.generator.used` | Generator invoked | `generator`, `context`, `role` |
| `analytics.ai.prompt.submitted` | Prompt submitted | `generator`, `prompt_length`, `role` |

## Navigation & Admin

| Event | When | Properties |
| --- | --- | --- |
| `analytics.navigation.rendered` | Navigation payload rendered | `sections`, `route` |
| `analytics.admin.settings.viewed` | Settings accessed | `tab`, `role` |
| `analytics.admin.security.reviewed` | Security tools opened | `tab`, `role` |
| `analytics.admin.role.changed` | Role changed for a profile | `target_id`, `role` |
| `analytics.admin.permission.changed` | Permission toggled | `permission`, `granted`, `target_id` |

## Implementation Notes

- Always include the authenticated actor; the publisher will inject the actor id automatically via `PermissionMatrix::analyticsProperties`.
- Fire events once per successful transaction (e.g., after persistence) to avoid duplicate analytics.
- Mobile clients must mirror these names to keep dashboards consistent.
