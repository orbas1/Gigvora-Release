<?php

use Illuminate\Support\Facades\Route;
use Jobs\Http\Controllers\ApplicationController;
use Jobs\Http\Controllers\CompanyController;
use Jobs\Http\Controllers\EmployerPortalController;
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

Route::prefix('employer')
    ->middleware($protectedWebMiddleware)
    ->name('employer.')
    ->group(function () {
        Route::get('/', [EmployerPortalController::class, 'dashboard'])->name('dashboard');
        Route::get('/dashboard/stats', [EmployerPortalController::class, 'dashboardStats'])->name('dashboard.stats');

        Route::get('/jobs', [EmployerPortalController::class, 'jobs'])->name('jobs.index');
        Route::get('/jobs/create', [EmployerPortalController::class, 'jobWizard'])->name('jobs.create');
        Route::get('/jobs/{job}/edit', [EmployerPortalController::class, 'jobWizard'])->name('jobs.edit');
        Route::get('/jobs/{job}/ats', [EmployerPortalController::class, 'ats'])->name('jobs.ats');
        Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
        Route::post('/jobs', [EmployerPortalController::class, 'store'])->name('jobs.store');
        Route::put('/jobs/{job}', [EmployerPortalController::class, 'update'])->name('jobs.update');

        Route::get('/interviews', [EmployerPortalController::class, 'interviews'])->name('interviews.index');
        Route::get('/interviews/calendar', [EmployerPortalController::class, 'interviewCalendar'])->name('interviews.calendar');
        Route::post('/interviews', [EmployerPortalController::class, 'scheduleInterview'])->name('interviews.store');
        Route::put('/interviews/{interview}', [EmployerPortalController::class, 'updateInterview'])->name('interviews.update');
        Route::delete('/interviews/{interview}', [EmployerPortalController::class, 'destroyInterview'])->name('interviews.destroy');

        Route::get('/candidates/{application}', [EmployerPortalController::class, 'candidate'])->name('candidates.show');

        Route::get('/company', [EmployerPortalController::class, 'company'])->name('company.edit');
        Route::put('/company', [EmployerPortalController::class, 'updateCompany'])->name('company.update');

        Route::get('/billing', [EmployerPortalController::class, 'billing'])->name('billing.index');

        Route::get('/create/job', [EmployerPortalController::class, 'jobWizard'])->name('create.job');
    });
