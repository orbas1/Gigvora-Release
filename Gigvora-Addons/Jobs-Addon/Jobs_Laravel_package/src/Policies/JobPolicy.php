<?php

namespace Jobs\Policies;

use Illuminate\Contracts\Auth\Authenticatable;
use Jobs\Models\Job;

class JobPolicy
{
    public function manage(?Authenticatable $user, Job $job): bool
    {
        if (! $user) {
            return false;
        }

        $companyOwnerId = optional($job->company)->user_id;
        if ($companyOwnerId && (int) $companyOwnerId === (int) $user->id) {
            return true;
        }

        $employerRoles = (array) config('jobs.roles.employer_access', []);
        if (! empty($user->user_role) && in_array($user->user_role, $employerRoles, true)) {
            return true;
        }

        return method_exists($user, 'isAdmin') && $user->isAdmin();
    }
}
