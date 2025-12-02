# Gigvora Roles & Permissions

This matrix is the canonical reference for permissions enforced across Gigvora web, APIs, and Flutter. All gates are defined in `config/permission_matrix.php` and consumed by the shared `PermissionMatrix` helper and `permission` middleware.

## Roles

| Role | Purpose |
| --- | --- |
| member | Default authenticated user for feed, utilities, and basic discovery. |
| freelancer | Independent talent building gigs, proposals, and contracts. |
| recruiter | Hiring manager creating jobs, reviewing candidates, and sponsoring campaigns. |
| company_admin | Owns company-wide admin for jobs, ads, billing, and role assignment. |
| creator | Hosts interactive sessions and produces content. |
| moderator | Handles abuse, disputes, and safety escalations. |
| platform_admin | Full administrator with override privileges and system settings. |

## Jobs Addon

| Capability | member | freelancer | recruiter | company_admin | creator | moderator | platform_admin |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Apply to jobs | ✅ | ✅ | 🔒 | 🔒 | 🔒 | 🔒 | ✅ |
| Create postings | 🔒 | 🔒 | ✅ | ✅ | 🔒 | 🔒 | ✅ |
| Manage applicants | 🔒 | 🔒 | ✅ | ✅ | 🔒 | 🔒 | ✅ |
| View reporting | 🔒 | 🔒 | ✅ | ✅ | 🔒 | 🔒 | ✅ |

## Freelance Addon

| Capability | member | freelancer | recruiter | company_admin | creator | moderator | platform_admin |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Access workspace | ✅ | ✅ | 🔒 | ✅ | 🔒 | 🔒 | ✅ |
| Manage gigs & proposals | 🔒 | ✅ | 🔒 | 🔒 | 🔒 | 🔒 | ✅ |
| Client projects & escrow | 🔒 | 🔒 | ✅ | ✅ | 🔒 | 🔒 | ✅ |
| Toggle favourites | ✅ | ✅ | 🔒 | ✅ | 🔒 | 🔒 | ✅ |
| Moderate disputes | 🔒 | 🔒 | 🔒 | 🔒 | 🔒 | ✅ | ✅ |

## Ads Addon

| Capability | member | freelancer | recruiter | company_admin | creator | moderator | platform_admin |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Manage campaigns | 🔒 | 🔒 | ✅ | ✅ | 🔒 | 🔒 | ✅ |
| View reports | 🔒 | 🔒 | ✅ | ✅ | 🔒 | 🔒 | ✅ |

## Talent & AI Addon

| Capability | member | freelancer | recruiter | company_admin | creator | moderator | platform_admin |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Run AI search | 🔒 | 🔒 | ✅ | ✅ | 🔒 | 🔒 | ✅ |
| Configure scoring | 🔒 | 🔒 | 🔒 | ✅ | 🔒 | 🔒 | ✅ |
| Use generators | 🔒 | ✅ | ✅ | ✅ | ✅ | 🔒 | ✅ |

## Interactive Addon

| Capability | member | freelancer | recruiter | company_admin | creator | moderator | platform_admin |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Host sessions | 🔒 | 🔒 | 🔒 | ✅ | ✅ | 🔒 | ✅ |
| Join sessions | ✅ | ✅ | ✅ | ✅ | ✅ | 🔒 | ✅ |
| Moderate live chat | 🔒 | 🔒 | 🔒 | 🔒 | 🔒 | ✅ | ✅ |

## Utilities Addon

| Capability | member | freelancer | recruiter | company_admin | creator | moderator | platform_admin |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Manage reminders/calendar | ✅ | ✅ | ✅ | ✅ | ✅ | 🔒 | ✅ |
| Notifications/bookmarks | ✅ | ✅ | ✅ | ✅ | ✅ | 🔒 | ✅ |

## AI Addon

| Capability | member | freelancer | recruiter | company_admin | creator | moderator | platform_admin |
| --- | --- | --- | --- | --- | --- | --- | --- |
| Use AI generators | 🔒 | ✅ | ✅ | ✅ | ✅ | 🔒 | ✅ |

## Enforcement Notes

- Gate checks and middleware use the keys above (e.g., `permission:freelance.workspace.access`).
- Admin-only actions still respect least privilege: `platform_admin` can override, but `moderator` is limited to safety scopes.
- Audit logging is required for role/permission changes and is handled via `AuditLogger` writing to the `audit_logs` table.

## References

- Navigation visibility aligns with these permissions via `App\Support\Navigation\NavigationBuilder`.
- Analytics taxonomy is detailed in `docs/analytics_events.md` and mirrors permissioned flows.
