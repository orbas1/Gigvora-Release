# Jobs Laravel Package

This package packages the core jobs, applications, and applicant tracking features from the Jobi platform so they can be reused inside other Laravel applications.

## Installation

1. Add the package as a path repository in your root `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "jobs_package/Jobs_Laravel_package"
        }
    ]
}
```

2. Require the package:

```bash
composer require jobs/laravel-package:dev-main
```

3. Publish assets and run database migrations:

```bash
php artisan vendor:publish --provider="Jobs\\JobsServiceProvider"
php artisan migrate
```

4. (Optional) seed default ATS data:

```bash
php artisan db:seed --class="Jobs\\Database\\Seeders\\JobsDatabaseSeeder"
```

5. Wire into your social features:

- Subscribe to the `Jobs\\Events\\JobPublished` and `Jobs\\Events\\ApplicationSubmitted` events to push job and application
  activity into your existing timelines, search indexers, or notification pipelines.
- Add the published routes to your feed/search middleware so jobs can be discovered alongside posts.
- Grant permissions to your admin roles so they can moderate job listings and review application status updates.

## Features

- Job postings with publishing windows and featured listings
- Company and candidate profiles
- Job applications with CV and cover letter attachments
- Applicant Tracking System stages and movement history
- Screening questions and recorded answers
- Subscription records and job credit tracking
- Interview scheduling records
- API and web routes for job browsing and management
- Blade views, translations, and configuration publishing assets
- Event hooks for live feeds, notifications, and search indexing
- Endpoints tuned for mobile clients consuming the Flutter addon
