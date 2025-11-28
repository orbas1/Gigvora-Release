<?php

namespace Jobi\WebinarNetworkingInterviewPodcast\Policies;

use Illuminate\Contracts\Auth\Authenticatable;
use Jobi\WebinarNetworkingInterviewPodcast\Models\PodcastSeries;
use Jobi\WebinarNetworkingInterviewPodcast\Policies\Concerns\HandlesRoles;

class PodcastPolicy
{
    use HandlesRoles;

    public function view(?Authenticatable $user, PodcastSeries $series): bool
    {
        return $series->is_public || $this->hasRole($user, 'admin') || $this->hasRole($user, 'host');
    }

    public function create(?Authenticatable $user): bool
    {
        return $this->hasRole($user, 'admin') || $this->hasRole($user, 'host');
    }

    public function update(?Authenticatable $user, PodcastSeries $series): bool
    {
        return $this->hasRole($user, 'admin') || ($user && $user->getAuthIdentifier() === $series->host_id);
    }
}

