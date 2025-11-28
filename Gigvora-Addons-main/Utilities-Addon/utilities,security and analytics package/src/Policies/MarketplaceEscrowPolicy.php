<?php

namespace ProNetwork\Policies;

use App\Models\User;
use ProNetwork\Models\MarketplaceEscrow;

class MarketplaceEscrowPolicy
{
    public function view(User $user, MarketplaceEscrow $escrow): bool
    {
        return $this->participantOrAdmin($user, $escrow);
    }

    public function manage(User $user, MarketplaceEscrow $escrow): bool
    {
        return $this->participantOrAdmin($user, $escrow);
    }

    protected function participantOrAdmin(User $user, MarketplaceEscrow $escrow): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        $order = $escrow->relationLoaded('order') ? $escrow->order : $escrow->order()->first();
        $participantIds = array_filter([
            $order->buyer_id ?? null,
            $order->seller_id ?? null,
            $order->user_id ?? null,
        ]);

        return in_array($user->id, array_map('intval', $participantIds), true);
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
