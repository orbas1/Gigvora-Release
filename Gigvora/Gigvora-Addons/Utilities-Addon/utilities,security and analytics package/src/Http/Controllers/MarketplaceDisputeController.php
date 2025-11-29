<?php

namespace ProNetworkUtilitiesSecurityAnalytics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use ProNetwork\Models\MarketplaceDispute;
use ProNetwork\Models\MarketplaceEscrow;
use ProNetwork\Services\MarketplaceEscrowDomain;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\OpenDisputeRequest;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\ReplyDisputeRequest;
use ProNetworkUtilitiesSecurityAnalytics\Http\Requests\ResolveDisputeRequest;

class MarketplaceDisputeController extends Controller
{
    public function __construct(protected MarketplaceEscrowDomain $escrowService)
    {
    }

    public function create(int $orderId)
    {
        return view('pro_network::disputes.create', [
            'orderId' => $orderId,
        ]);
    }

    public function store(OpenDisputeRequest $request, int $orderId)
    {
        $data = $request->validated();
        $escrow = MarketplaceEscrow::where('order_id', $orderId)->firstOrFail();
        Gate::authorize('manage', $escrow);
        $dispute = $this->escrowService->openDispute($escrow, $request->user()->id, $data['reason']);

        if ($request->expectsJson()) {
            return response()->json([
                'dispute' => $dispute,
                'message' => 'Dispute opened.',
            ]);
        }

        return view('pro_network::disputes.show', [
            'dispute' => $dispute,
        ]);
    }

    public function show(Request $request, MarketplaceDispute $dispute)
    {
        $dispute->loadMissing(['messages', 'escrow']);
        Gate::authorize('view', $dispute);

        if ($request->expectsJson()) {
            return response()->json([
                'dispute' => $dispute,
            ]);
        }

        return view('pro_network::disputes.show', [
            'dispute' => $dispute,
        ]);
    }

    public function reply(ReplyDisputeRequest $request, MarketplaceDispute $dispute)
    {
        $data = $request->validated();
        Gate::authorize('view', $dispute);

        $message = $this->escrowService->addDisputeMessage(
            $dispute,
            $request->user()->id,
            $data['message'],
            $data['attachments'] ?? []
        );

        if ($request->expectsJson()) {
            return response()->json([
                'dispute' => $dispute->fresh(['messages']),
                'message' => 'Reply added.',
                'new_message' => $message,
            ]);
        }

        return view('pro_network::disputes.show', [
            'dispute' => $dispute->fresh(['messages']),
            'new_message' => $message,
        ]);
    }

    public function resolve(ResolveDisputeRequest $request, MarketplaceDispute $dispute)
    {
        $data = $request->validated();
        Gate::authorize('resolve', $dispute);

        $resolved = $this->escrowService->resolveDispute(
            $dispute,
            $request->user()->id,
            $data['resolution']
        );

        if ($request->expectsJson()) {
            return response()->json([
                'dispute' => $resolved,
                'message' => 'Dispute resolved.',
            ]);
        }

        return view('pro_network::disputes.show', [
            'dispute' => $resolved,
        ]);
    }
}
