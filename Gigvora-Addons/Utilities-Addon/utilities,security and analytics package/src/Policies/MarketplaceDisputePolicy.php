<?php

namespace ProNetwork\Policies;

use App\Models\User;
use ProNetwork\Models\MarketplaceDispute;
use ProNetwork\Models\MarketplaceEscrow;

class MarketplaceDisputePolicy
{
    public function view(User $user, MarketplaceDispute $dispute): bool
    {
        if ($this->isAdmin($user) || $dispute->raised_by === $user->id) {
            return true;
        }

        return $this->escrowParticipant($user, $dispute->escrow);
    }

    public function resolve(User $user, MarketplaceDispute $dispute): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        return $this->escrowParticipant($user, $dispute->escrow);
    }

    protected function escrowParticipant(User $user, ?MarketplaceEscrow $escrow): bool
    {
        if (! $escrow) {
            return false;
        }

        $escrow->loadMissing('order');
        $order = $escrow->order;
        $participants = array_filter([
            $escrow->order_id,
            $order->buyer_id ?? null,
            $order->seller_id ?? null,
            $order->user_id ?? null,
        ]);

        return in_array($user->id, array_map('intval', $participants), true);
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
