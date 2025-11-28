<?php

use Illuminate\Support\Facades\Route;
use Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers\InterviewController;
use Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers\NetworkingController;
use Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers\PodcastController;
use Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers\WebinarController;

Route::group(['prefix' => 'wnip', 'middleware' => ['api', 'auth:sanctum']], function () {
    Route::get('/webinars', [WebinarController::class, 'index']);
    Route::post('/webinars', [WebinarController::class, 'store']);
    Route::get('/webinars/{webinar}', [WebinarController::class, 'show']);
    Route::put('/webinars/{webinar}', [WebinarController::class, 'update']);
    Route::delete('/webinars/{webinar}', [WebinarController::class, 'destroy']);
    Route::post('/webinars/{webinar}/register', [WebinarController::class, 'register']);
    Route::post('/webinars/{webinar}/toggle-live', [WebinarController::class, 'toggleLive']);

    Route::get('/networking', [NetworkingController::class, 'index']);
    Route::post('/networking', [NetworkingController::class, 'store']);
    Route::get('/networking/{networkingSession}', [NetworkingController::class, 'show']);
    Route::put('/networking/{networkingSession}', [NetworkingController::class, 'update']);
    Route::post('/networking/{networkingSession}/register', [NetworkingController::class, 'register']);
    Route::post('/networking/{networkingSession}/rotate', [NetworkingController::class, 'rotate']);

    Route::get('/podcasts', [PodcastController::class, 'index']);
    Route::post('/podcast-series', [PodcastController::class, 'storeSeries']);
    Route::get('/podcast-series/{podcastSeries}', [PodcastController::class, 'showSeries']);
    Route::put('/podcast-series/{podcastSeries}', [PodcastController::class, 'updateSeries']);
    Route::post('/podcast-series/{podcastSeries}/episodes', [PodcastController::class, 'storeEpisode']);
    Route::post('/podcast-series/{podcastSeries}/episodes/{episode}/publish', [PodcastController::class, 'publishEpisode']);

    Route::get('/interviews', [InterviewController::class, 'index']);
    Route::post('/interviews', [InterviewController::class, 'store']);
    Route::get('/interviews/{interview}', [InterviewController::class, 'show']);
    Route::put('/interviews/{interview}', [InterviewController::class, 'update']);
    Route::post('/interviews/{interview}/slots', [InterviewController::class, 'addSlot']);
    Route::post('/interviews/{interview}/slots/{interviewSlot}/score', [InterviewController::class, 'score']);
});

