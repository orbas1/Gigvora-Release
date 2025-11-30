<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])
    ->prefix('addons/advertisement')
    ->as('advertisement.')
    ->group(function (): void {
        if (!config('advertisement.enabled')) {
            return;
        }

        Route::view('dashboard', 'advertisement::dashboard')->name('dashboard');
        Route::view('reports', 'advertisement::vendor.advertisement.advertiser.dashboard')->name('reports.index');

        Route::view('campaigns', 'advertisement::vendor.advertisement.advertiser.campaigns.index')
            ->name('campaigns.index');
        Route::get('campaigns/create', function () {
            return view('advertisement::vendor.advertisement.advertiser.campaigns.wizard', [
                'action' => route('api.advertisement.campaigns.store'),
            ]);
        })->name('campaigns.create');
        Route::get('campaigns/{campaign}', function ($campaign) {
            return view('advertisement::vendor.advertisement.advertiser.campaigns.show', [
                'campaign' => $campaign,
            ]);
        })->name('campaigns.show');
        Route::get('campaigns/{campaign}/edit', function ($campaign) {
            return view('advertisement::vendor.advertisement.advertiser.campaigns.wizard', [
                'action' => route('api.advertisement.campaigns.store'),
                'campaign' => $campaign,
            ]);
        })->name('campaigns.edit');

        Route::view('creatives', 'advertisement::vendor.advertisement.advertiser.creatives.index', [
            'campaignOptions' => [],
        ])->name('creatives.index');
        Route::view('creatives/create', 'advertisement::vendor.advertisement.advertiser.creatives.edit')
            ->name('creatives.create');
        Route::get('creatives/{creative}/edit', function ($creative) {
            return view('advertisement::vendor.advertisement.advertiser.creatives.edit', [
                'creative' => $creative,
            ]);
        })->name('creatives.edit');
    });
