<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Policies\Concerns;

use Illuminate\Contracts\Auth\Authenticatable;

trait HandlesRoles
{
    protected function hasRole(?Authenticatable $user, string $role): bool
    {
        if (!$user) {
            return false;
        }

        if (method_exists($user, 'hasRole')) {
            return (bool) $user->hasRole($role);
        }

        if (property_exists($user, 'role')) {
            return $user->role === $role;
        }

        return false;
    }
}

