<?php

use Illuminate\Support\Facades\Route;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\CompanyProfileController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\ConnectionsController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\ChatEnhancementController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\AnalyticsController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\HashtagController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\MarketplaceDisputeController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\MarketplaceEscrowController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\MusicLibraryController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\NewsletterController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\PostEnhancementController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\ProfessionalProfileController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\RecommendationsController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\ReactionsController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\SecurityModerationController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\StoryEnhancementController;
use ProNetworkUtilitiesSecurityAnalytics\Http\Controllers\AgeVerificationController;

Route::middleware(['api', 'auth:sanctum'])->prefix('api/pro-network')->group(function () {
    Route::middleware('pro-network.feature:connections_graph')->group(function () {
        Route::get('/connections', [ConnectionsController::class, 'list']);
        Route::get('/connections/mutual/{user}', [ConnectionsController::class, 'mutual']);
    });

    Route::middleware('pro-network.feature:recommendations')->group(function () {
        Route::get('/recommendations/people', [RecommendationsController::class, 'people']);
        Route::get('/recommendations/companies', [RecommendationsController::class, 'companies']);
        Route::get('/recommendations/groups', [RecommendationsController::class, 'groups']);
        Route::get('/recommendations/content', [RecommendationsController::class, 'content']);
    });

    Route::middleware('pro-network.feature:profile_professional_upgrades')->group(function () {
        Route::get('/profile/professional', [ProfessionalProfileController::class, 'show']);
        Route::post('/profile/professional', [ProfessionalProfileController::class, 'update']);
        Route::get('/company/{company}', [CompanyProfileController::class, 'show']);
        Route::post('/company/{company}', [CompanyProfileController::class, 'update'])->middleware('can:update,company');
    });

    Route::middleware('pro-network.feature:marketplace_escrow')->group(function () {
        Route::post('/marketplace/orders/{order}/escrow/open', [MarketplaceEscrowController::class, 'open']);
        Route::post('/marketplace/escrow/{escrow}/release', [MarketplaceEscrowController::class, 'release']);
        Route::post('/marketplace/escrow/{escrow}/refund', [MarketplaceEscrowController::class, 'refund']);

        Route::post('/marketplace/orders/{order}/disputes', [MarketplaceDisputeController::class, 'store']);
        Route::get('/marketplace/disputes/{dispute}', [MarketplaceDisputeController::class, 'show']);
        Route::post('/marketplace/disputes/{dispute}/reply', [MarketplaceDisputeController::class, 'reply']);
        Route::post('/marketplace/disputes/{dispute}/resolve', [MarketplaceDisputeController::class, 'resolve'])
            ->middleware('can:resolve,dispute');
    });

    Route::middleware('pro-network.feature:stories_wrapper')->group(function () {
        Route::post('/stories', [StoryEnhancementController::class, 'store']);
        Route::get('/stories/{story}/viewers', [StoryEnhancementController::class, 'viewers']);
    });

    Route::middleware('pro-network.feature:post_enhancements')->group(function () {
        Route::post('/posts/polls', [PostEnhancementController::class, 'storePoll']);
        Route::post('/posts/polls/{poll}/vote', [PostEnhancementController::class, 'votePoll']);
        Route::post('/posts/threads', [PostEnhancementController::class, 'storeThread']);
        Route::post('/posts/reshare', [PostEnhancementController::class, 'reshare']);
        Route::post('/posts/celebrate', [PostEnhancementController::class, 'storeCelebrate']);
    });

    Route::middleware('pro-network.feature:reactions_dislikes_scores')->group(function () {
        Route::post('/reactions', [ReactionsController::class, 'react']);
        Route::delete('/reactions', [ReactionsController::class, 'unreact']);
        Route::post('/reactions/dislike', [ReactionsController::class, 'dislike']);
        Route::delete('/reactions/dislike', [ReactionsController::class, 'undislike']);
        Route::get('/profiles/{user}/reaction-score', [ReactionsController::class, 'profileScore']);
    });

    Route::middleware('pro-network.feature:hashtags')->group(function () {
        Route::get('/hashtags', [HashtagController::class, 'index']);
        Route::post('/hashtags/search', [HashtagController::class, 'search']);
    });

    Route::middleware('pro-network.feature:music_library')->group(function () {
        Route::get('/music-library', [MusicLibraryController::class, 'index']);
        Route::post('/music-library/search', [MusicLibraryController::class, 'search']);
    });

    Route::middleware('pro-network.feature:chat_enhancements')->group(function () {
        Route::get('/chat/conversations', [ChatEnhancementController::class, 'listConversations']);
        Route::get('/chat/conversations/{conversation}', [ChatEnhancementController::class, 'showConversation']);
        Route::delete('/chat/conversations/{conversation}', [ChatEnhancementController::class, 'deleteConversation']);
        Route::post('/chat/conversations/{conversation}/clear', [ChatEnhancementController::class, 'clearConversation']);
        Route::post('/chat/settings', [ChatEnhancementController::class, 'updateSettings']);
        Route::get('/chat/requests', [ChatEnhancementController::class, 'messageRequests']);
        Route::post('/chat/requests/{request}/accept', [ChatEnhancementController::class, 'acceptRequest']);
        Route::post('/chat/requests/{request}/decline', [ChatEnhancementController::class, 'declineRequest']);
    });

    Route::middleware(['pro-network.feature:analytics_hub', 'can:viewAnalytics'])->group(function () {
        Route::post('/analytics/metrics', [AnalyticsController::class, 'metrics']);
        Route::post('/analytics/series', [AnalyticsController::class, 'series']);
    });

    Route::middleware(['pro-network.feature:security_hardening', 'can:viewSecurity'])->group(function () {
        Route::post('/security/events', [SecurityModerationController::class, 'events']);
    });

    Route::middleware(['pro-network.feature:moderation_tools', 'can:moderate'])->group(function () {
        Route::post('/moderation/queue', [SecurityModerationController::class, 'queue']);
        Route::post('/moderation/action', [SecurityModerationController::class, 'moderate']);
    });

    Route::middleware('pro-network.feature:newsletters')->group(function () {
        Route::post('/newsletters/subscribe', [NewsletterController::class, 'subscribe']);
        Route::post('/newsletters/unsubscribe', [NewsletterController::class, 'unsubscribe']);
    });

    Route::middleware('pro-network.feature:age_verification')->group(function () {
        Route::get('/age-verification/status', [AgeVerificationController::class, 'status']);
        Route::post('/age-verification/start', [AgeVerificationController::class, 'start']);
    });
});
