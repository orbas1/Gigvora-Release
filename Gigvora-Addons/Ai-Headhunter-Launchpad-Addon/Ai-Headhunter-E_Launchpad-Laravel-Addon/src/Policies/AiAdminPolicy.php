<?php

declare(strict_types=1);

namespace Gigvora\TalentAi\Policies;

use App\Models\User;

class AiAdminPolicy
{
    public function manage(User $user): bool
    {
        return $user->user_role === 'admin';
    }
}
