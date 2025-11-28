<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Policies;

use Illuminate\Contracts\Auth\Authenticatable;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Interview;
use Jobi\WebinarNetworkingInterviewPodcast\Policies\Concerns\HandlesRoles;

class InterviewPolicy
{
    use HandlesRoles;

    public function view(?Authenticatable $user, Interview $interview): bool
    {
        return $this->hasRole($user, 'admin') || $this->hasRole($user, 'host') || $this->hasRole($user, 'interviewer') || $this->hasRole($user, 'interviewee');
    }

    public function create(?Authenticatable $user): bool
    {
        return $this->hasRole($user, 'admin') || $this->hasRole($user, 'host');
    }

    public function score(?Authenticatable $user, Interview $interview): bool
    {
        return $this->hasRole($user, 'admin') || $this->hasRole($user, 'interviewer');
    }

    public function update(?Authenticatable $user, Interview $interview): bool
    {
        return $this->hasRole($user, 'admin') || ($user && $user->getAuthIdentifier() === $interview->host_id);
    }
}

