<?php

use Illuminate\Support\Facades\Route;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\CompanyProfileController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\ConnectionsController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\AnalyticsController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\HashtagController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\MarketplaceDisputeController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\MarketplaceEscrowController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\NewsletterController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\PostEnhancementController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\ProfessionalProfileController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\AgeVerificationController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\SecurityModerationController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\StoryEnhancementController;

Route::middleware(['web', 'auth'])->prefix('pro-network')->group(function () {
    Route::middleware('pro-network.feature:connections_graph')->group(function () {
        Route::get('/my-network', [ConnectionsController::class, 'index']);
        Route::get('/my-network/connections', [ConnectionsController::class, 'list']);
        Route::get('/my-network/mutual/{user}', [ConnectionsController::class, 'mutual']);
    });

    Route::middleware('pro-network.feature:profile_professional_upgrades')->group(function () {
        Route::get('/profile/professional', [ProfessionalProfileController::class, 'show']);
        Route::get('/profile/professional/{user}', [ProfessionalProfileController::class, 'show']);
        Route::get('/profile/professional/edit', [ProfessionalProfileController::class, 'edit']);
        Route::post('/profile/professional', [ProfessionalProfileController::class, 'update']);
        Route::get('/company/{company}', [CompanyProfileController::class, 'show']);
        Route::post('/company/{company}', [CompanyProfileController::class, 'update'])->middleware('can:update,company');
    });

    Route::middleware('pro-network.feature:marketplace_escrow')->group(function () {
        Route::get('/marketplace/orders/{order}/escrow', [MarketplaceEscrowController::class, 'showByOrder']);
        Route::get('/marketplace/orders/{order}/disputes/create', [MarketplaceDisputeController::class, 'create']);
        Route::get('/marketplace/disputes/{dispute}', [MarketplaceDisputeController::class, 'show']);
    });

    Route::middleware('pro-network.feature:stories_wrapper')->group(function () {
        Route::get('/stories/viewer', [StoryEnhancementController::class, 'viewer']);
        Route::get('/stories/creator', [StoryEnhancementController::class, 'creator']);
    });

    Route::middleware('pro-network.feature:hashtags')->group(function () {
        Route::get('/hashtags/{hashtag}', [HashtagController::class, 'show']);
    });

    Route::middleware('pro-network.feature:post_enhancements')->group(function () {
        Route::get('/posts/polls/create', [PostEnhancementController::class, 'createPoll']);
        Route::get('/posts/threads/create', [PostEnhancementController::class, 'createThread']);
        Route::get('/posts/celebrate/create', [PostEnhancementController::class, 'createCelebrate']);
    });

    Route::middleware(['pro-network.feature:analytics_hub', 'can:viewAnalytics'])->group(function () {
        Route::get('/analytics', [AnalyticsController::class, 'overview']);
        Route::get('/admin/newsletters', [NewsletterController::class, 'adminIndex']);
    });

    Route::middleware(['pro-network.feature:security_hardening', 'can:viewSecurity'])->group(function () {
        Route::get('/security/log', [SecurityModerationController::class, 'securityLog']);
    });

    Route::middleware(['pro-network.feature:moderation_tools', 'can:moderate'])->group(function () {
        Route::get('/moderation', [SecurityModerationController::class, 'moderationQueue']);
    });

    Route::middleware('pro-network.feature:newsletters')->group(function () {
        Route::get('/newsletters/manage', [NewsletterController::class, 'manage']);
    });

    Route::middleware('pro-network.feature:age_verification')->any('/age-verification/callback', [AgeVerificationController::class, 'callback']);
});
