<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->group(function () {
    Route::view('/advertisement/dashboard', 'advertisement::dashboard')->name('advertisement.dashboard');
});
