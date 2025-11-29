<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use ProNetwork\Models\MarketplaceEscrow;
use ProNetwork\Services\MarketplaceEscrowDomain;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\OpenEscrowRequest;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\RefundEscrowRequest;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\ReleaseEscrowRequest;

class MarketplaceEscrowController extends Controller
{
    public function __construct(protected MarketplaceEscrowDomain $escrowService)
    {
    }

    public function showByOrder(Request $request, $order)
    {
        $orderModel = $this->resolveOrderModel($order);
        $escrow = MarketplaceEscrow::with(['milestones', 'transactions', 'disputes.messages'])
            ->where('order_id', $orderModel->getKey())
            ->firstOrFail();

        Gate::authorize('view', $escrow);

        if ($request->expectsJson()) {
            return response()->json([
                'escrow' => $escrow,
            ]);
        }

        return view('pro_network::escrow.show', [
            'escrow' => $escrow,
        ]);
    }

    public function open(OpenEscrowRequest $request, $order)
    {
        $data = $request->validated();
        $orderModel = $this->resolveOrderModel($order);
        $escrow = MarketplaceEscrow::firstOrNew(['order_id' => $orderModel->getKey()]);
        $escrow->setRelation('order', $orderModel);
        Gate::authorize('manage', $escrow);

        $escrow = $this->escrowService->open(
            $orderModel->getKey(),
            (float) $data['amount'],
            $data['currency'] ?? 'USD',
            $data['delivery_method'] ?? 'delivery'
        );

        if (! empty($data['delivery_notes'])) {
            $escrow->delivery_notes = $data['delivery_notes'];
            $escrow->save();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'escrow' => $escrow->fresh(),
                'message' => 'Escrow opened successfully.',
            ]);
        }

        return view('pro_network::escrow.show', [
            'escrow' => $escrow->fresh(),
        ]);
    }

    public function release(ReleaseEscrowRequest $request, int $escrowId)
    {
        $data = $request->validated();
        $escrow = MarketplaceEscrow::findOrFail($escrowId);
        Gate::authorize('manage', $escrow);

        $transaction = $this->escrowService->release($escrow, (float) $data['amount']);
        $transaction->user_id = $request->user()->id;
        $transaction->save();
        $escrow->refresh();

        if ($request->expectsJson()) {
            return response()->json([
                'escrow' => $escrow,
                'transaction' => $transaction,
                'message' => 'Escrow released.',
            ]);
        }

        return view('pro_network::escrow.show', [
            'escrow' => $escrow,
            'transaction' => $transaction,
        ]);
    }

    public function refund(RefundEscrowRequest $request, int $escrowId)
    {
        $data = $request->validated();
        $escrow = MarketplaceEscrow::findOrFail($escrowId);
        Gate::authorize('manage', $escrow);

        $transaction = $this->escrowService->refund($escrow, (float) $data['amount']);
        $transaction->user_id = $request->user()->id;
        if (! empty($data['reason'])) {
            $transaction->notes = $data['reason'];
        }
        $transaction->save();
        $escrow->refresh();

        if ($request->expectsJson()) {
            return response()->json([
                'escrow' => $escrow,
                'transaction' => $transaction,
                'message' => 'Escrow refunded.',
            ]);
        }

        return view('pro_network::escrow.show', [
            'escrow' => $escrow,
            'transaction' => $transaction,
        ]);
    }

    protected function resolveOrderModel($order)
    {
        $orderClass = config('pro_network_utilities_security_analytics.models.marketplace_order');

        if (is_object($order)) {
            return $order;
        }

        return $orderClass::findOrFail((int) $order);
    }
}
