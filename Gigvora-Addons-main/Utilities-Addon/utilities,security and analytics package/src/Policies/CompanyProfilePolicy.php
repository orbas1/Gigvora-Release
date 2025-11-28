<?php

namespace ProNetwork\Policies;

use App\Models\User;
use ProNetwork\Models\CompanyProfile;

class CompanyProfilePolicy
{
    public function update(User $user, CompanyProfile $profile): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        $page = $profile->relationLoaded('page') ? $profile->page : $profile->page()->first();
        $ownerId = $page->user_id ?? $page->owner_id ?? null;

        return $ownerId ? (int) $ownerId === (int) $user->id : false;
    }

    protected function isAdmin(User $user): bool
    {
        if (property_exists($user, 'is_admin') && $user->is_admin) {
            return true;
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('admin')) {
            return true;
        }

        if (method_exists($user, 'hasAnyRole') && $user->hasAnyRole(['admin', 'moderator'])) {
            return true;
        }

        return false;
    }
}
