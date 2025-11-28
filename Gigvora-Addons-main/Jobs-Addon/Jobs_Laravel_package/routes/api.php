<?php

use Illuminate\Support\Facades\Route;
use Jobs\Http\Controllers\ApplicationController;
use Jobs\Http\Controllers\AtsController;
use Jobs\Http\Controllers\CoverLetterController;
use Jobs\Http\Controllers\CvController;
use Jobs\Http\Controllers\InterviewController;
use Jobs\Http\Controllers\JobController;
use Jobs\Http\Controllers\ScreeningController;
use Jobs\Http\Controllers\SubscriptionController;

$apiMiddleware = config('jobs.middleware.api', ['api']);
$protectedApiMiddleware = config('jobs.middleware.api_protected', ['api', 'auth:sanctum']);

Route::prefix(config('jobs.prefixes.api', 'api'))
    ->middleware($apiMiddleware)
    ->as('api.jobs.')
    ->group(function () use ($protectedApiMiddleware) {
        Route::apiResource('jobs', JobController::class)->except(['create', 'edit']);

        Route::middleware($protectedApiMiddleware)->group(function () {
            Route::apiResource('applications', ApplicationController::class)->only(['index', 'store', 'update', 'show']);

            Route::post('jobs/{job}/applications', [ApplicationController::class, 'storeForJob']);
            Route::get('jobs/saved', [JobController::class, 'saved']);
            Route::post('jobs/{job}/save', [JobController::class, 'toggleSave']);
            Route::delete('jobs/{job}/save', [JobController::class, 'toggleSave']);

            Route::get('ats/stages', [AtsController::class, 'stages']);
            Route::post('applications/{jobApplication}/ats/move', [AtsController::class, 'move']);
            Route::get('jobs/{job}/pipeline', [AtsController::class, 'pipeline']);

            Route::get('jobs/{job}/screening', [ScreeningController::class, 'questions']);
            Route::post('jobs/{job}/screening', [ScreeningController::class, 'storeQuestion']);
            Route::post('applications/{jobApplication}/screening-answers', [ScreeningController::class, 'answer']);

            Route::get('cover-letters', [CoverLetterController::class, 'index']);
            Route::post('cover-letters', [CoverLetterController::class, 'store']);
            Route::get('cover-letters/{coverLetter}', [CoverLetterController::class, 'show']);
            Route::put('cover-letters/{coverLetter}', [CoverLetterController::class, 'update']);
            Route::delete('cover-letters/{coverLetter}', [CoverLetterController::class, 'destroy']);

            Route::get('cvs', [CvController::class, 'index']);
            Route::post('cvs', [CvController::class, 'store']);
            Route::get('cvs/{cv}', [CvController::class, 'show']);
            Route::put('cvs/{cv}', [CvController::class, 'update']);
            Route::delete('cvs/{cv}', [CvController::class, 'destroy']);

            Route::get('subscriptions', [SubscriptionController::class, 'index']);
            Route::post('subscriptions', [SubscriptionController::class, 'store']);

            Route::get('applications/{jobApplication}/interviews', [InterviewController::class, 'index']);
            Route::post('applications/{jobApplication}/interviews', [InterviewController::class, 'store']);
            Route::post('applications/{jobApplication}/withdraw', [ApplicationController::class, 'withdraw']);
        });

        Route::get('jobs/{job}/similar', [JobController::class, 'similar']);
    });
