<?php

namespace ProNetwork\Services;

use Illuminate\Support\Facades\DB;
use ProNetwork\Models\MarketplaceDispute;
use ProNetwork\Models\MarketplaceDisputeMessage;
use ProNetwork\Models\MarketplaceEscrow;
use ProNetwork\Models\MarketplaceMilestone;
use ProNetwork\Models\MarketplaceTransaction;

class MarketplaceEscrowDomain
{
    public function open(int $orderId, float $amount, string $currency = 'USD', string $deliveryMethod = 'delivery'): MarketplaceEscrow
    {
        return MarketplaceEscrow::updateOrCreate(
            ['order_id' => $orderId],
            [
                'status' => 'held',
                'amount' => $amount,
                'currency' => $currency,
                'delivery_method' => $deliveryMethod,
                'held_at' => now(),
            ]
        );
    }

    public function holdMilestone(MarketplaceEscrow $escrow, string $title, float $amount, ?string $dueAt = null): MarketplaceMilestone
    {
        return $escrow->milestones()->create([
            'title' => $title,
            'amount' => $amount,
            'status' => 'pending',
            'due_at' => $dueAt,
        ]);
    }

    public function release(MarketplaceEscrow $escrow, float $amount): MarketplaceTransaction
    {
        return DB::transaction(function () use ($escrow, $amount) {
            $escrow->update([
                'status' => 'released',
                'released_at' => now(),
            ]);

            return $escrow->transactions()->create([
                'type' => 'release',
                'amount' => $amount,
                'currency' => $escrow->currency,
            ]);
        });
    }

    public function refund(MarketplaceEscrow $escrow, float $amount): MarketplaceTransaction
    {
        return DB::transaction(function () use ($escrow, $amount) {
            $escrow->update([
                'status' => 'refunded',
                'refunded_at' => now(),
            ]);

            return $escrow->transactions()->create([
                'type' => 'refund',
                'amount' => $amount,
                'currency' => $escrow->currency,
            ]);
        });
    }

    public function openDispute(MarketplaceEscrow $escrow, int $userId, string $reason): MarketplaceDispute
    {
        return $escrow->disputes()->create([
            'raised_by' => $userId,
            'reason' => $reason,
            'status' => 'open',
        ]);
    }

    public function adjustInventory(int $orderId, int $delta): void
    {
        // This defers to the host application to listen for the event and mutate inventory accordingly.
        event('pro-network.inventory.adjusted', ['order_id' => $orderId, 'delta' => $delta]);
    }

    public function resolveDispute(MarketplaceDispute $dispute, int $resolverId, string $resolution): MarketplaceDispute
    {
        $dispute->update([
            'status' => 'resolved',
            'resolution_notes' => $resolution,
            'resolved_by' => $resolverId,
            'resolved_at' => now(),
        ]);

        return $dispute->fresh();
    }

    public function addDisputeMessage(MarketplaceDispute $dispute, int $userId, string $message, array $attachments = []): MarketplaceDisputeMessage
    {
        return $dispute->messages()->create([
            'user_id' => $userId,
            'message' => $message,
            'attachments' => $attachments,
        ]);
    }
}
