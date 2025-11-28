<?php

use Advertisement\Http\Controllers\AffiliateController;
use Advertisement\Http\Controllers\AdvertiserController;
use Advertisement\Http\Controllers\CampaignController;
use Advertisement\Http\Controllers\CreativeController;
use Advertisement\Http\Controllers\KeywordPlannerController;
use Advertisement\Http\Controllers\ReportController;
use Advertisement\Http\Controllers\TargetingController;
use Illuminate\Support\Facades\Route;

Route::prefix('api/advertisement')
    ->middleware(['api', 'auth:sanctum'])
    ->as('api.advertisement.')
    ->group(function (): void {
        if (!config('advertisement.enabled')) {
            return;
        }

        Route::get('advertisers', [AdvertiserController::class, 'index'])->name('advertisers.index');
        Route::post('advertisers', [AdvertiserController::class, 'store'])->name('advertisers.store');
        Route::put('advertisers/{advertiser}', [AdvertiserController::class, 'update'])->name('advertisers.update');

        Route::get('campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
        Route::get('campaigns/{campaign}', [CampaignController::class, 'show'])->name('campaigns.show');
        Route::post('campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
        Route::put('campaigns/{campaign}', [CampaignController::class, 'update'])->name('campaigns.update');
        Route::post('campaigns/{campaign}/forecast', [CampaignController::class, 'forecast'])->name('campaigns.forecast');

        Route::get('creatives', [CreativeController::class, 'index'])->name('creatives.index');
        Route::post('creatives', [CreativeController::class, 'store'])->name('creatives.store');
        Route::put('creatives/{creative}', [CreativeController::class, 'update'])->name('creatives.update');

        Route::post('campaigns/{campaign}/targeting', [TargetingController::class, 'store'])->name('targeting.store');

        Route::get('campaigns/{campaign}/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('campaigns/{campaign}/reports', [ReportController::class, 'store'])->name('reports.store');

        Route::post('keyword-planner', KeywordPlannerController::class)->name('keyword_planner.store');

        Route::get('affiliates/referrals', [AffiliateController::class, 'referrals'])->name('affiliates.referrals');
        Route::post('affiliates/referrals', [AffiliateController::class, 'storeReferral'])->name('affiliates.referrals.store');
        Route::post('affiliates/payouts', [AffiliateController::class, 'requestPayout'])->name('affiliates.payouts.store');
        Route::get('affiliates/payouts', [AffiliateController::class, 'payouts'])->name('affiliates.payouts');
    });
