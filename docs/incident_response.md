# Incident Response Runbooks

## Data Breach or Account Compromise
- Trigger: unusual admin login, leaked tokens, or audit log anomalies.
- Immediate steps:
  - Lock impacted accounts via admin controls and force password reset + 2FA.
  - Rotate API keys and invalidate active Sanctum tokens.
  - Export audit evidence from `/api/admin/audit-logs` for the affected window.
  - Notify stakeholders and begin disclosure following legal requirements.
- Recovery:
  - Verify GDPR export/delete requests are processed after containment.
  - Capture a post-mortem entry in `AuditLog` with action `incident.report`.

## Fraud/Abuse, Escrow, or Dispute Escalation
- Trigger: abnormal payment activity, escrow override requests, or dispute flags.
- Steps:
  - Review recent `AuditLog` entries for `payment.error` or dispute escalations.
  - Pause payout/escrow release in the payment provider dashboard.
  - Capture evidence (contracts, chat threads, invoices) and store under `incident.report`.
  - Apply role or access restrictions where necessary and notify parties of status.

## Streaming Misuse or Illegal Content
- Trigger: reports of illegal or abusive streaming content.
- Steps:
  - Terminate the stream via live controls and record an `incident.report` entry.
  - Preserve evidence (engagement metrics, uploader profile, chat logs).
  - File a takedown audit entry and coordinate with trust & safety for escalation.

## Payment Gateway or Integration Outage
- Trigger: elevated `integration.error` or `payment.error` counts in admin metrics.
- Steps:
  - Confirm provider status and switch to backup gateways if configured.
  - Communicate downtime to users via notifications/utility banners.
  - Retry failed jobs after the provider is restored and verify queue backlog recovery.

## Communication and Handoff
- Use the admin dashboards for real-time KPIs and queue states when coordinating incidents.
- Log every manual override (bans, unlocks, payouts) to `AuditLog` for traceability.
- After resolution, update `docs/progress.md` with a concise summary and remediation steps.
