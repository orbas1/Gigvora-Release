<?php

namespace App\Providers;

use App\Support\Authorization\PermissionMatrix;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Jobs\Models\Job;
use Jobs\Policies\JobPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Job::class => JobPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(PermissionMatrix $permissions)
    {
        $this->registerPolicies();

        foreach ($permissions->permissions() as $permission => $meta) {
            Gate::define($permission, function ($user) use ($permissions, $permission) {
                return $permissions->allowed($user, $permission);
            });
        }
    }
}
