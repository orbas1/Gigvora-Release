<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Policies;

use Illuminate\Contracts\Auth\Authenticatable;
use Jobi\WebinarNetworkingInterviewPodcast\Models\Webinar;
use Jobi\WebinarNetworkingInterviewPodcast\Policies\Concerns\HandlesRoles;

class WebinarPolicy
{
    use HandlesRoles;

    public function view(?Authenticatable $user, Webinar $webinar): bool
    {
        return $webinar->is_paid === false || $this->hasRole($user, 'admin') || $this->hasRole($user, 'host') || $this->hasRole($user, 'attendee');
    }

    public function create(?Authenticatable $user): bool
    {
        return $this->hasRole($user, 'admin') || $this->hasRole($user, 'host');
    }

    public function update(?Authenticatable $user, Webinar $webinar): bool
    {
        return $this->hasRole($user, 'admin') || ($user && $user->getAuthIdentifier() === $webinar->host_id);
    }

    public function delete(?Authenticatable $user, Webinar $webinar): bool
    {
        return $this->update($user, $webinar);
    }
}

