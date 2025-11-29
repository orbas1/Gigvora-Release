<?php

namespace Jobs\Policies;

use Illuminate\Contracts\Auth\Authenticatable;
use Jobs\Models\Job;

class JobPolicy
{
    public function manage(?Authenticatable $user, Job $job): bool
    {
        return $user && ((int) $user->id === (int) $job->company_id || method_exists($user, 'isAdmin') && $user->isAdmin());
    }
}
