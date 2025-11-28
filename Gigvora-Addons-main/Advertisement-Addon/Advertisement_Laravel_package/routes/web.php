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

        Route::view('campaigns', 'advertisement::vendor.advertisement.advertiser.campaigns.index')
            ->name('campaigns.index');
        Route::view('campaigns/create', 'advertisement::vendor.advertisement.advertiser.campaigns.wizard', [
            'action' => route('api.advertisement.campaigns.store'),
        ])->name('campaigns.create');
        Route::view('campaigns/{campaign}', 'advertisement::vendor.advertisement.advertiser.campaigns.show', [
            'campaign' => [],
        ])->name('campaigns.show');
        Route::view('campaigns/{campaign}/edit', 'advertisement::vendor.advertisement.advertiser.campaigns.wizard', [
            'action' => route('api.advertisement.campaigns.store'),
        ])->name('campaigns.edit');

        Route::view('creatives', 'advertisement::vendor.advertisement.advertiser.creatives.index', [
            'campaignOptions' => [],
        ])->name('creatives.index');
        Route::view('creatives/create', 'advertisement::vendor.advertisement.advertiser.creatives.edit')
            ->name('creatives.create');
        Route::view('creatives/{creative}/edit', 'advertisement::vendor.advertisement.advertiser.creatives.edit')
            ->name('creatives.edit');
    });
