<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Policies;

use App\Models\User;
use Gigvora\TalentAi\Domain\Launchpad\Models\LaunchpadApplication;

class LaunchpadApplicationPolicy
{
    public function view(User $user, LaunchpadApplication $application): bool
    {
        return $user->can('manage_launchpad_programmes') || $application->user_id === $user->id;
    }

    public function update(User $user, LaunchpadApplication $application): bool
    {
        return $user->can('manage_launchpad_programmes') || $application->programme?->creator_id === $user->id;
    }
}
