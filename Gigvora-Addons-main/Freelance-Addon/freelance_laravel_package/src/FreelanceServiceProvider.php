<?php

namespace Taskup\Freelance;

use Illuminate\Support\ServiceProvider;

class FreelanceServiceProvider extends ServiceProvider
{
    /**
     * Register bindings.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../publishable/config/freelance.php',
            'freelance'
        );
    }

    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../publishable/database/migrations');
        $this->loadTranslationsFrom(__DIR__.'/../publishable/resources/lang', 'freelance');
        $this->loadViewsFrom(__DIR__.'/../publishable/resources/views', 'freelance');

        if (config('freelance.features.publish_routes', true)) {
            $this->loadRoutesFrom(__DIR__.'/../publishable/routes/api.php');
            $this->loadRoutesFrom(__DIR__.'/../publishable/routes/web.php');
            $this->loadRoutesFrom(__DIR__.'/../publishable/routes/admin.php');
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../publishable/config/freelance.php' => config_path('freelance.php'),
            ], 'freelance-config');

            $this->publishes([
                __DIR__.'/../publishable/app' => app_path(),
            ], 'freelance-app');

            $this->publishes([
                __DIR__.'/../publishable/database/migrations' => database_path('migrations'),
            ], 'freelance-migrations');

            $this->publishes([
                __DIR__.'/../publishable/resources/views' => resource_path('views/vendor/freelance'),
            ], 'freelance-views');

            $this->publishes([
                __DIR__.'/../publishable/resources/lang' => lang_path('vendor/freelance'),
            ], 'freelance-lang');

            $this->publishes([
                __DIR__.'/../publishable/routes' => base_path('routes'),
            ], 'freelance-routes');
        }
    }
}
