<?php

namespace ProNetwork\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use ProNetwork\Models\MarketplaceEscrow;
use ProNetwork\Services\EscrowService;

class EscrowController extends Controller
{
    public function __construct(protected EscrowService $service)
    {
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'delivery_type' => 'required|string',
        ]);
        $escrow = $this->service->hold($data['order_id'], $data['amount'], $data['delivery_type']);
        return response()->json($escrow, 201);
    }

    public function release(MarketplaceEscrow $escrow)
    {
        return response()->json($this->service->release($escrow));
    }

    public function dispute(MarketplaceEscrow $escrow, Request $request)
    {
        $data = $request->validate(['reason' => 'required|string']);
        $dispute = $this->service->openDispute($escrow, $request->user()->id, $data['reason']);
        return response()->json($dispute, 201);
    }
}
