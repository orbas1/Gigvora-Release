<?php

use Advertisement\Http\Controllers\AffiliateController;
use Advertisement\Http\Controllers\AdvertiserController;
use Advertisement\Http\Controllers\CampaignController;
use Advertisement\Http\Controllers\CreativeController;
use Advertisement\Http\Controllers\KeywordPlannerController;
use Advertisement\Http\Controllers\ReportController;
use Advertisement\Http\Controllers\TargetingController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/advertisement')->middleware(['api'])->group(function () {
    Route::get('advertisers', [AdvertiserController::class, 'index']);
    Route::post('advertisers', [AdvertiserController::class, 'store']);
    Route::put('advertisers/{advertiser}', [AdvertiserController::class, 'update']);

    Route::get('campaigns', [CampaignController::class, 'index']);
    Route::get('campaigns/{campaign}', [CampaignController::class, 'show']);
    Route::post('campaigns', [CampaignController::class, 'store']);
    Route::put('campaigns/{campaign}', [CampaignController::class, 'update']);
    Route::post('campaigns/{campaign}/forecast', [CampaignController::class, 'forecast']);

    Route::get('creatives', [CreativeController::class, 'index']);
    Route::post('creatives', [CreativeController::class, 'store']);
    Route::put('creatives/{creative}', [CreativeController::class, 'update']);

    Route::post('campaigns/{campaign}/targeting', [TargetingController::class, 'store']);

    Route::get('campaigns/{campaign}/reports', [ReportController::class, 'index']);
    Route::post('campaigns/{campaign}/reports', [ReportController::class, 'store']);

    Route::post('keyword-planner', KeywordPlannerController::class);

    Route::get('affiliates/referrals', [AffiliateController::class, 'referrals']);
    Route::post('affiliates/referrals', [AffiliateController::class, 'storeReferral']);
    Route::post('affiliates/payouts', [AffiliateController::class, 'requestPayout']);
    Route::get('affiliates/payouts', [AffiliateController::class, 'payouts']);
});
