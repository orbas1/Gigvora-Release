<?php

use Illuminate\Support\Facades\Route;
use ProNetwork\Http\Controllers\AgeVerificationController;
use ProNetwork\Http\Controllers\AnalyticsController;
use ProNetwork\Http\Controllers\EscrowController;
use ProNetwork\Http\Controllers\NetworkController;
use ProNetwork\Http\Controllers\NewsletterController;
use ProNetwork\Http\Controllers\ProfileController;
use ProNetwork\Http\Controllers\ReactionController;
use ProNetwork\Http\Controllers\RecommendationController;

Route::middleware(['web', 'auth'])
    ->prefix('pro-network')
    ->group(function () {
        Route::get('/network', [NetworkController::class, 'index']);
        Route::post('/network', [NetworkController::class, 'store']);
        Route::post('/profile', [ProfileController::class, 'update']);
        Route::get('/recommendations', [RecommendationController::class, 'index']);
        Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);
        Route::post('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe']);
        Route::post('/age-verification', [AgeVerificationController::class, 'request']);
        Route::get('/age-verification', [AgeVerificationController::class, 'status']);
    });

Route::middleware(['api', 'auth:sanctum'])
    ->prefix('api/pro-network')
    ->group(function () {
        Route::post('/analytics', [AnalyticsController::class, 'store']);
        Route::post('/reactions', [ReactionController::class, 'store']);
        Route::post('/escrows', [EscrowController::class, 'store']);
        Route::post('/escrows/{escrow}/release', [EscrowController::class, 'release']);
        Route::post('/escrows/{escrow}/dispute', [EscrowController::class, 'dispute']);
    });
