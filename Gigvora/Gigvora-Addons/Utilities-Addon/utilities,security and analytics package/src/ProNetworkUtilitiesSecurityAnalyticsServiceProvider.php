<?php

namespace ProNetwork;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use ProNetwork\Services\AccountTypeService;
use ProNetwork\Services\AgeVerificationService;
use ProNetwork\Services\AnalyticsService;
use ProNetwork\Services\ChatEnhancementService;
use ProNetwork\Services\ConnectionService;
use ProNetwork\Services\HashtagService;
use ProNetwork\Services\InviteContributorsService;
use ProNetwork\Services\LiveStreamingWrapper;
use ProNetwork\Services\MarketplaceEscrowDomain;
use ProNetwork\Services\ModerationService;
use ProNetwork\Services\MultiLanguageService;
use ProNetwork\Services\MusicLibraryService;
use ProNetwork\Services\NewsletterService;
use ProNetwork\Services\NotificationsWrapper;
use ProNetwork\Services\PostEnhancementService;
use ProNetwork\Services\ProfileEnhancementService;
use ProNetwork\Services\ReactionsService;
use ProNetwork\Services\RecommendationService;
use ProNetwork\Services\SearchTagsDomain;
use ProNetwork\Services\SearchUpgradeService;
use ProNetwork\Services\SecurityEventService;
use ProNetwork\Services\StorageService;
use ProNetwork\Services\StoryEnhancementService;
use ProNetwork\Policies\CompanyProfilePolicy;
use ProNetwork\Policies\MarketplaceDisputePolicy;
use ProNetwork\Policies\MarketplaceEscrowPolicy;
use ProNetwork\Policies\ProfessionalProfilePolicy;
use ProNetwork\Models\CompanyProfile;
use ProNetwork\Models\MarketplaceDispute;
use ProNetwork\Models\MarketplaceEscrow;
use ProNetwork\Models\ProfessionalProfile;
use ProNetworkUtilitiesSecurityAnalytics\Http\Middleware\EnsureFeatureEnabled;

class ProNetworkUtilitiesSecurityAnalyticsServiceProvider extends ServiceProvider
{
    protected array $policies = [
        ProfessionalProfile::class => ProfessionalProfilePolicy::class,
        CompanyProfile::class => CompanyProfilePolicy::class,
        MarketplaceEscrow::class => MarketplaceEscrowPolicy::class,
        MarketplaceDispute::class => MarketplaceDisputePolicy::class,
    ];

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/pro_network_utilities_security_analytics.php', 'pro_network_utilities_security_analytics');

        $this->app->singleton(AnalyticsService::class);
        $this->app->singleton(SecurityEventService::class);
        $this->app->singleton(ModerationService::class);
        $this->app->singleton(StorageService::class);
        $this->app->singleton(AgeVerificationService::class);
        $this->app->singleton(NewsletterService::class);
        $this->app->singleton(ConnectionService::class);
        $this->app->singleton(RecommendationService::class);
        $this->app->singleton(MarketplaceEscrowDomain::class);
        $this->app->singleton(HashtagService::class);
        $this->app->singleton(ReactionsService::class);
        $this->app->singleton(ProfileEnhancementService::class);
        $this->app->singleton(StoryEnhancementService::class);
        $this->app->singleton(PostEnhancementService::class);
        $this->app->singleton(MusicLibraryService::class);
        $this->app->singleton(AccountTypeService::class);
        $this->app->singleton(SearchUpgradeService::class);
        $this->app->singleton(ChatEnhancementService::class);
        $this->app->singleton(LiveStreamingWrapper::class);
        $this->app->singleton(NotificationsWrapper::class);
        $this->app->singleton(SearchTagsDomain::class);
        $this->app->singleton(InviteContributorsService::class);
        $this->app->singleton(MultiLanguageService::class);
    }

    public function boot(): void
    {
        $this->registerMiddleware();
        $this->registerPolicies();
        $this->registerRouteBindings();

        $this->publishes([
            __DIR__.'/../config/pro_network_utilities_security_analytics.php' => config_path('pro_network_utilities_security_analytics.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'migrations');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'pro_network');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'pro_network');

        $this->loadRoutesFrom(__DIR__.'/../routes/pro_network_web.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/pro_network_api.php');
    }

    protected function registerMiddleware(): void
    {
        $this->app->make(Router::class)->aliasMiddleware('pro-network.feature', EnsureFeatureEnabled::class);
    }

    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        Gate::define('viewAnalytics', function ($user) {
            return $this->userHasPrivilege($user, ['analytics', 'admin'])
                && config('pro_network_utilities_security_analytics.features.analytics_hub');
        });

        Gate::define('viewSecurity', function ($user) {
            return $this->userHasPrivilege($user, ['security', 'admin', 'moderator'])
                && config('pro_network_utilities_security_analytics.features.security_hardening');
        });

        Gate::define('moderate', function ($user) {
            return $this->userHasPrivilege($user, ['moderator', 'admin'])
                && config('pro_network_utilities_security_analytics.features.moderation_tools');
        });
    }

    protected function registerRouteBindings(): void
    {
        $router = $this->app->make(Router::class);

        $router->bind('company', function ($value) {
            return CompanyProfile::where('page_id', $value)->firstOrFail();
        });

        $router->model('dispute', MarketplaceDispute::class);
        $router->model('escrow', MarketplaceEscrow::class);
    }

    protected function userHasPrivilege($user, array $roles): bool
    {
        if (! $user) {
            return false;
        }

        if (property_exists($user, 'is_admin') && $user->is_admin) {
            return true;
        }

        if (method_exists($user, 'hasAnyRole')) {
            return $user->hasAnyRole($roles);
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        if (method_exists($user, 'hasPermissionTo') && $user->hasPermissionTo(implode('|', $roles))) {
            return true;
        }

        return false;
    }
}
