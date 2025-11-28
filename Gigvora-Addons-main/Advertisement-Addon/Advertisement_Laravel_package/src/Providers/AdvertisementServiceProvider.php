<?php

namespace Advertisement\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Advertisement\Models\Campaign;
use Advertisement\Policies\CampaignPolicy;
use App\Models\User;

class AdvertisementServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/advertisement.php' => config_path('advertisement.php'),
        ], 'advertisement-config');

        Gate::policy(Campaign::class, CampaignPolicy::class);
        Gate::define('manage_advertisement', static function (?User $user): bool {
            return $user?->user_role === 'admin';
        });

        if (!config('advertisement.enabled')) {
            return;
        }

        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'advertisement');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../resources/views' => resource_path('views/vendor/advertisement'),
            ], 'advertisement-views');

            $this->publishes([
                __DIR__ . '/../../database/seeders' => database_path('seeders'),
            ], 'advertisement-seeders');
        }
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/advertisement.php', 'advertisement');
    }
}
