<?php

use Illuminate\Support\Facades\Route;
use Jobs\Http\Controllers\ApplicationController;
use Jobs\Http\Controllers\CompanyController;
use Jobs\Http\Controllers\JobController;

Route::middleware(['web'])->group(function () {
    Route::get('jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::get('jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
    Route::get('jobs/{job}/apply', [JobController::class, 'apply'])->name('jobs.apply');
    Route::post('jobs/{job}/apply', [ApplicationController::class, 'storeForJob'])->name('jobs.apply.submit');
    Route::get('jobs/saved/list', [JobController::class, 'saved'])->name('jobs.saved');
    Route::post('jobs/{job}/save', [JobController::class, 'toggleSave'])->name('jobs.save');
    Route::delete('jobs/{job}/save', [JobController::class, 'toggleSave']);
    Route::post('companies', [CompanyController::class, 'store'])->name('jobs.companies.store');
});
