<?php

namespace Advertisement\Services;

use Advertisement\Models\AffiliatePayout;
use Advertisement\Models\AffiliateReferral;
use Advertisement\Models\Campaign;
use Illuminate\Support\Carbon;

class AffiliateService
{
    public function registerReferral(int $referrerId, int $referredUserId, ?Campaign $campaign = null): AffiliateReferral
    {
        $commissionRate = config('advertisement.affiliate.commission_rate');
        $commission = $campaign ? $campaign->budget * $commissionRate : 0;

        return AffiliateReferral::create([
            'referrer_id' => $referrerId,
            'referred_user_id' => $referredUserId,
            'campaign_id' => $campaign?->id,
            'commission' => $commission,
            'status' => 'pending',
        ]);
    }

    public function requestPayout(int $affiliateId, float $amount): AffiliatePayout
    {
        return AffiliatePayout::create([
            'affiliate_id' => $affiliateId,
            'amount' => $amount,
            'status' => 'requested',
            'requested_at' => Carbon::now(),
        ]);
    }
}
