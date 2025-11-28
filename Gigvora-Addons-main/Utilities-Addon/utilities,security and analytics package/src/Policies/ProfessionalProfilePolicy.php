<?php

namespace ProNetwork\Policies;

use App\Models\User;
use ProNetwork\Models\ProfessionalProfile;

class ProfessionalProfilePolicy
{
    public function update(User $user, ProfessionalProfile $profile): bool
    {
        return $user->id === $profile->user_id;
    }
}
