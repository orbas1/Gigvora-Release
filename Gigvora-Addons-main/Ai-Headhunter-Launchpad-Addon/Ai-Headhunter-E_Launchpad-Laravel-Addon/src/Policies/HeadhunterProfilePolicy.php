<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Policies;

use App\Models\User;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterProfile;

class HeadhunterProfilePolicy
{
    public function view(User $user, HeadhunterProfile $profile): bool
    {
        return $user->can('manage_talent_ai') || $profile->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can('manage_talent_ai') || $user->can('apply_headhunter');
    }

    public function update(User $user, HeadhunterProfile $profile): bool
    {
        return $user->can('manage_headhunter_pipelines') || $profile->user_id === $user->id;
    }
}
