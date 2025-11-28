<?php

namespace Advertisement\Http\Controllers;

use Advertisement\Models\AffiliatePayout;
use Advertisement\Models\AffiliateReferral;
use Advertisement\Services\AffiliateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AffiliateController
{
    public function referrals(Request $request): JsonResponse
    {
        $referrals = AffiliateReferral::query()
            ->when($request->get('referrer_id'), fn ($q) => $q->where('referrer_id', $request->get('referrer_id')))
            ->latest()
            ->paginate();

        return response()->json($referrals);
    }

    public function storeReferral(Request $request, AffiliateService $service): JsonResponse
    {
        $data = $request->validate([
            'referrer_id' => 'required|integer',
            'referred_user_id' => 'required|integer',
            'campaign_id' => 'nullable|integer|exists:campaigns,id'
        ]);

        $referral = $service->registerReferral($data['referrer_id'], $data['referred_user_id'], $data['campaign_id'] ? \Advertisement\Models\Campaign::find($data['campaign_id']) : null);

        return response()->json($referral, 201);
    }

    public function requestPayout(Request $request, AffiliateService $service): JsonResponse
    {
        $data = $request->validate([
            'affiliate_id' => 'required|integer',
            'amount' => 'required|numeric|min:' . config('advertisement.affiliate.payout_threshold'),
        ]);

        $payout = $service->requestPayout($data['affiliate_id'], $data['amount']);

        return response()->json($payout, 201);
    }

    public function payouts(): JsonResponse
    {
        return response()->json(AffiliatePayout::latest()->paginate());
    }
}
