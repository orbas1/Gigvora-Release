<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Policies;

use Illuminate\Contracts\Auth\Authenticatable;
use Jobi\WebinarNetworkingInterviewPodcast\Models\NetworkingSession;
use Jobi\WebinarNetworkingInterviewPodcast\Policies\Concerns\HandlesRoles;

class NetworkingPolicy
{
    use HandlesRoles;

    public function view(?Authenticatable $user, NetworkingSession $session): bool
    {
        return !$session->is_paid || $this->hasRole($user, 'admin') || $this->hasRole($user, 'host') || $this->hasRole($user, 'attendee');
    }

    public function create(?Authenticatable $user): bool
    {
        return $this->hasRole($user, 'admin') || $this->hasRole($user, 'host');
    }

    public function update(?Authenticatable $user, NetworkingSession $session): bool
    {
        return $this->hasRole($user, 'admin') || ($user && $user->getAuthIdentifier() === $session->host_id);
    }
}

