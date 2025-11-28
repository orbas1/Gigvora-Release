<?php

use Illuminate\Support\Facades\Route;
use Jobs\Http\Controllers\ApplicationController;
use Jobs\Http\Controllers\CompanyController;
use Jobs\Http\Controllers\JobController;

$webMiddleware = config('jobs.middleware.web', ['web']);
$protectedWebMiddleware = config('jobs.middleware.web_protected', ['web', 'auth', 'verified']);

Route::prefix(config('jobs.prefixes.web', 'jobs'))
    ->middleware($webMiddleware)
    ->as('jobs.')
    ->group(function () use ($protectedWebMiddleware) {
        Route::get('/', [JobController::class, 'index'])->name('index');
        Route::get('/{job}', [JobController::class, 'show'])->name('show');

        Route::middleware($protectedWebMiddleware)->group(function () {
            Route::get('/{job}/apply', [JobController::class, 'apply'])->name('apply');
            Route::post('/{job}/apply', [ApplicationController::class, 'storeForJob'])->name('apply.submit');
            Route::get('/saved/list', [JobController::class, 'saved'])->name('saved');
            Route::post('/{job}/save', [JobController::class, 'toggleSave'])->name('save');
            Route::delete('/{job}/save', [JobController::class, 'toggleSave']);
            Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
        });
    });
