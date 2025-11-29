<?php

namespace Advertisement\Policies;

use Advertisement\Models\Campaign;
use Illuminate\Contracts\Auth\Authenticatable;

class CampaignPolicy
{
    public function view(?Authenticatable $user, Campaign $campaign): bool
    {
        return $this->ownsCampaign($user, $campaign) || $this->isAdmin($user);
    }

    public function update(?Authenticatable $user, Campaign $campaign): bool
    {
        return $this->ownsCampaign($user, $campaign) || $this->isAdmin($user);
    }

    protected function ownsCampaign(?Authenticatable $user, Campaign $campaign): bool
    {
        return $user && method_exists($user, 'getAuthIdentifier') && $campaign->advertiser?->user_id === $user->getAuthIdentifier();
    }

    protected function isAdmin(?Authenticatable $user): bool
    {
        return $user && property_exists($user, 'is_admin') && $user->is_admin;
    }
}
