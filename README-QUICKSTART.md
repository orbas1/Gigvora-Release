# Gigvora Quickstart (Unified Schema)

## Web (Laravel)
- Requirements: PHP 8.2+, Composer, Node.js + npm, MySQL/MariaDB, Redis/queue backend, FFmpeg.
- Setup:
  1. Clone repo and enter: `git clone https://github.com/orbas1/Gigvora-Release.git && cd Gigvora-Release`
  2. Copy env: `cp .env.example .env` then fill DB/cache/mail/storage/queue keys.
  3. Database options:
    - **Option A (SQL import)**: `mysql -u <user> -p <database> < database/install_master.sql` (rebuilt via `database/install/rebuild_install.sh` which now stitches together modular parts: `000_preamble.sql`, `001_core.sql`, `200_addons_marketplace.sql`, `210_jobs_addon.sql`, `900_modern_extensions.sql`, and `999_postamble.sql`, mirroring to `public/assets/install.sql`).
     - **Option B (Laravel migrations)**: `composer install` then `php artisan key:generate` and `php artisan migrate --seed`.
  4. Assets: `npm install` then `npm run build` (or `npm run dev` locally).
  5. Storage: `php artisan storage:link`.
  6. Run: `php artisan serve` and log in with the seeded admin (set `GIGVORA_ADMIN_PASSWORD` before seeding).
- Notes: configure media storage, AI/ads/payment API keys, and streaming credentials in `.env` before going live.

## Mobile (Flutter)
- Requirements: Flutter SDK (matching repo version), Android Studio/Xcode, emulator or device.
- Setup:
  1. `cd "Gigvora Flutter Mobile App"/App`
  2. Point API base URL in `lib/config/api_config.dart` (or addon configs) to your Laravel host, e.g. `https://local.gigvora.test/api`.
  3. Fetch deps: `flutter pub get`.
  4. Run: `flutter run` (or build with `flutter build apk` / `flutter build ios`).
- Notes: global theming uses `GigvoraThemeData`; configure push notifications (e.g., Firebase) and any build-time env JSON before release builds.
