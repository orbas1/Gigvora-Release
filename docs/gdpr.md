# GDPR & DSAR

- Use the admin GDPR endpoints (`/api/admin/gdpr/users/{id}`) to export or erase user data. Exports include profiles, posts, media, stories, live sessions, utilities records, notifications, and audit logs tied to the actor/target.
- Erasure anonymizes user profiles, clears moderation strikes/bans, removes media/posts/stories/live streams, and wipes utilities + notifications while preserving audit log trails of the operation.
- All GDPR operations are logged in `audit_logs` with `gdpr.export` or `gdpr.erase`, including actor and timestamps. Always perform over HTTPS and store exports securely.
- Data retention: moderation bans and audit logs are retained; personal data in posts/comments is removed or anonymized based on cascade deletes in the service layer.

## DSAR Checklist
1. Confirm requester identity via admin tooling.
2. Trigger export and verify payload integrity.
3. Initiate erasure if requested and permitted; confirm cascades completed.
4. Record completion in `docs/progress.md` with the request identifier.
