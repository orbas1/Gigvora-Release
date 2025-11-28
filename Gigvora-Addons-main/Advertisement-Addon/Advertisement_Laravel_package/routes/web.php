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
    });
