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
        if ($this->hasRole($user, 'admin') || $this->hasRole($user, 'host')) {
            return true;
        }

        if ($webinar->is_paid === false) {
            return true;
        }

        $isRegistered = $user
            ? $webinar->registrations()->where('user_id', $user->getAuthIdentifier())->exists()
            : false;

        return $isRegistered || $this->hasRole($user, 'attendee');
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

    public function accessReplay(?Authenticatable $user, Webinar $webinar): bool
    {
        if ($this->hasRole($user, 'admin') || $webinar->host_id === optional($user)->getAuthIdentifier()) {
            return true;
        }

        $registration = $user
            ? $webinar->registrations()->where('user_id', $user->getAuthIdentifier())->first()
            : null;

        if (! $registration) {
            return false;
        }

        if ($webinar->is_paid && ($webinar->price ?? 0) > 0) {
            return (bool) $registration->ticket_id;
        }

        return true;
    }
}

