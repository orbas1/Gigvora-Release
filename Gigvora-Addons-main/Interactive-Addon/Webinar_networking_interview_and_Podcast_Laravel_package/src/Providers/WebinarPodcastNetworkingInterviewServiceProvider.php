<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class WebinarPodcastNetworkingInterviewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/webinar_networking_interview_podcast.php', 'webinar_networking_interview_podcast');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'wnip');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views/vendor/live', 'wnip');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        $this->publishes([
            __DIR__ . '/../../config/webinar_networking_interview_podcast.php' => config_path('webinar_networking_interview_podcast.php'),
        ], 'wnip-config');

        $this->publishes([
            __DIR__ . '/../../resources/views/vendor/live' => resource_path('views/vendor/wnip'),
        ], 'wnip-views');

        $this->publishes([
            __DIR__ . '/../../resources/lang' => resource_path('lang/vendor/wnip'),
        ], 'wnip-lang');

        $this->publishes([
            __DIR__ . '/../../database/seeders' => database_path('seeders'),
            __DIR__ . '/../../database/migrations' => database_path('migrations'),
        ], 'wnip-database');

        $this->registerPolicies();
    }

    protected function registerPolicies(): void
    {
        $policies = [
            'webinar' => \Jobi\WebinarNetworkingInterviewPodcast\Policies\WebinarPolicy::class,
            'networking' => \Jobi\WebinarNetworkingInterviewPodcast\Policies\NetworkingPolicy::class,
            'podcast' => \Jobi\WebinarNetworkingInterviewPodcast\Policies\PodcastPolicy::class,
            'interview' => \Jobi\WebinarNetworkingInterviewPodcast\Policies\InterviewPolicy::class,
        ];

        foreach ($policies as $key => $policy) {
            Gate::policy($key, $policy);
        }
    }
}

