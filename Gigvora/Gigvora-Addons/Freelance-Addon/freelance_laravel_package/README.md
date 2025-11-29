# Taskup Freelance Laravel Package

This package extracts the gig, project, escrow, and dispute experience from the Taskup Laravel codebase into a publishable package. It ships migrations, Livewire components, Blade templates, API/Web routes, translations, and the supporting models, controllers, resources, middleware, and services required to run the freelance module.

The latest release also adds:

- Project management primitives (tasks, milestones, hourly time logging, submissions, invitations, freelancer matching, and client/freelancer reviews).
- Gig management extensions (timelines, FAQs, add-ons, packages, requirements, change requests, custom gigs, and gig reviews).
- Dispute lifecycle tracking (initial → mediation → partial/full refunds → arbitration → closed) with history of stage transitions.
- Escrow management utilities (partial releases, admin decisions, released totals) compatible with the mobile add-on.
- Profile enrichment (freelancer tags, gig tags, and skills) with admin endpoints to seed and curate the shared tag taxonomy.
- User profile depth: project portfolios with links/media, client-written profile reviews and aggregate ratings, and education/certification history with admin visibility.

## Installation

1. Add the package to `composer.json` as a path repository or publish it to your private registry:

   ```json
   "repositories": [
     { "type": "path", "url": "path/to/freelance_laravel_package", "options": { "symlink": false } }
   ]
   ```

   Then require it:

   ```bash
   composer require taskup/freelance-laravel-package:dev-main
   ```

2. Register the service provider (Laravel package auto-discovery will pick it up when installed via Composer):

   ```php
   Taskup\Freelance\FreelanceServiceProvider::class,
   ```

3. Publish the assets you need:

   ```bash
   php artisan vendor:publish --tag=freelance-config
   php artisan vendor:publish --tag=freelance-app
   php artisan vendor:publish --tag=freelance-migrations
   php artisan vendor:publish --tag=freelance-routes
   php artisan vendor:publish --tag=freelance-views
   php artisan vendor:publish --tag=freelance-lang
   ```

4. Run the migrations to create gig, project, escrow, and dispute tables:

   ```bash
   php artisan migrate
   ```

5. (Optional) Wire the freelance module into your **search index** and **activity feed**. The publishable config exposes
   `search` and `feed` blocks so you can toggle indexing, queue names, and recommendation weightings. Use the included
   events in the publishable `app/` folder to listen for gig/project lifecycle changes and push them into your search or live
   feed pipeline.

## Contents

- `publishable/app`: Models, controllers, middleware, services, requests, resources, and Livewire components copied from the Taskup freelance module.
- `publishable/database/migrations`: Gig, project, escrow, and dispute migrations.
- `publishable/resources`: Blade templates, Livewire views, and language lines for freelance flows.
- `publishable/routes`: Web, API, and admin routes exposing the freelance endpoints and pages.
- `publishable/config/freelance.php`: Feature toggles and commission defaults.
- `src/FreelanceServiceProvider.php`: Registers routes, migrations, translations, and publishable assets.

## Notes

- The publish step copies the Taskup `app` layer files directly into your host application so namespaces and imports continue to function.
- Ensure any payment gateways referenced by `VerfiyPaymentGateway` middleware are configured in the host environment.
- The package targets Laravel 10 and PHP 8.1+ (matching the upstream Taskup app).
- For production, enable queue workers for search/recommendation jobs, configure cache for `feed.recommendations`, and register
  broadcast channels your live feed uses. Defaults in `freelance.php` are conservative and can be tuned per environment.
