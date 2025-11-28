# Webinar, Networking, Interview & Podcast Laravel Package

This package bundles webinar hosting, speed networking, podcast publishing, and interview coordination into a reusable Laravel module. It is designed for LinkedIn-style professional networks that need event, media, and interview capabilities available to both web and mobile clients.

## Installation

1. Add the package via Composer (using a path or VCS repository):

```bash
composer require jobi/webinar-networking-interview-podcast
```

2. Publish assets and configuration:

```bash
php artisan vendor:publish --tag=wnip-config
php artisan vendor:publish --tag=wnip-views
php artisan vendor:publish --tag=wnip-lang
php artisan vendor:publish --tag=wnip-database
```

3. Run database migrations and optional seeders:

```bash
php artisan migrate
php artisan db:seed --class="\\Jobi\\WebinarNetworkingInterviewPodcast\\Database\\Seeders\\WebinarNetworkingInterviewPodcastSeeder"
```

4. Protect the API routes with `auth:sanctum` or your preferred guard. The package registers routes under the `/wnip` prefix.

## Features

- **Webinars:** scheduling, paywall-ready registrations, live toggling, and recording references.
- **Networking:** speed networking with rotation tracking and attendee registration.
- **Podcasts:** series and episodes with scheduling and publish flows.
- **Interviews:** multi-slot interview scheduling, scoring, and secure access rules.
- **Policies:** sensible defaults for hosts, admins, attendees, interviewers, and interviewees.
- **Views & Translations:** starter blade templates and language lines for quick UI integration.

## Configuration

All runtime flags live in `config/webinar_networking_interview_podcast.php`. Key toggles include feature enablement, pricing defaults, streaming provider settings, and interview scoring defaults.

## Extending

- Override blades via the `wnip` view namespace.
- Extend policies by binding your own classes in a service provider before route registration.
- Swap streaming providers by updating the config and injecting your own controllers or event listeners.

