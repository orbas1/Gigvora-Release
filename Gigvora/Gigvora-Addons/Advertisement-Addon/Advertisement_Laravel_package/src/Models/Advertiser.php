<?php

namespace Advertisement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertiser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'billing_email',
        'daily_spend_limit',
        'lifetime_spend_limit',
        'wallet_balance',
        'status',
        'affiliate_id',
    ];

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function affiliateReferrals()
    {
        return $this->hasMany(AffiliateReferral::class, 'referrer_id');
    }
}
