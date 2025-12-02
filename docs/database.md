# Database & Install SQL

- `public/assets/install.sql` is regenerated from the live Laravel migrations and seeders using SQLite to ensure schema and base data parity across core and addons. Run `APP_ENV=local DB_CONNECTION=sqlite DB_DATABASE=database/database.sqlite GIGVORA_ADMIN_PASSWORD=<strong-password> php artisan migrate:fresh --seed` followed by `sqlite3 database/database.sqlite .dump > public/assets/install.sql` to refresh.
- All addon tables (Jobs, Freelance, Interactive, Ads, AI, Utilities, Talent & AI) are included via migrations; the SQL dump mirrors their foreign keys and indexes.
- Seed data is idempotent and expects `GIGVORA_ADMIN_PASSWORD` plus any addon integration keys set in `.env` before seeding.
- The users table now includes extended profile and moderation fields (`nickname`, `about`, `save_post`, `status`, `profile_status`, `moderation_strikes`, `shadow_banned_until`, `banned_reason`) to keep GDPR export/erasure and moderation flows consistent with the application code.

## Validation Steps
- After updating migrations, re-run `php artisan migrate:fresh --seed` with the SQLite database to confirm schema health.
- Importing `install.sql` into a blank SQLite instance must succeed without errors and match the migrated schema.
- Use `php artisan config:cache` after setting environment variables to ensure cached config does not miss required keys.
