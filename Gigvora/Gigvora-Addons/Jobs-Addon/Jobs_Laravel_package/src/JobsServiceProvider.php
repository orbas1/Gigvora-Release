<?php

namespace Jobs;

use Illuminate\Support\ServiceProvider;

class JobsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/jobs.php', 'jobs');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'jobs');
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'jobs');

        $this->publishes([
            __DIR__ . '/../config/jobs.php' => config_path('jobs.php'),
        ], 'jobs-config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/jobs'),
        ], 'jobs-views');

        $this->publishes([
            __DIR__ . '/../resources/lang' => resource_path('lang/vendor/jobs'),
        ], 'jobs-lang');
    }
}
