<?php

use Illuminate\Support\Facades\Route;
use Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers\InterviewPageController;
use Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers\NetworkingPageController;
use Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers\PodcastPageController;
use Jobi\WebinarNetworkingInterviewPodcast\Http\Controllers\WebinarPageController;

Route::group(['prefix' => 'events', 'middleware' => ['web']], function () {
    Route::get('/webinars', [WebinarPageController::class, 'index'])->name('wnip.webinars.index');
    Route::get('/webinars/{webinar}', [WebinarPageController::class, 'show'])->name('wnip.webinars.show');
    Route::post('/webinars/{webinar}/register', [WebinarPageController::class, 'register'])
        ->middleware('auth')
        ->name('wnip.webinars.register');
    Route::get('/webinars/{webinar}/waiting-room', [WebinarPageController::class, 'waitingRoom'])->name('wnip.webinars.waiting');
    Route::get('/webinars/{webinar}/live', [WebinarPageController::class, 'live'])->name('wnip.webinars.live');

    Route::get('/networking', [NetworkingPageController::class, 'index'])->name('wnip.networking.index');
    Route::get('/networking/{networkingSession}', [NetworkingPageController::class, 'show'])->name('wnip.networking.show');
    Route::post('/networking/{networkingSession}/register', [NetworkingPageController::class, 'register'])
        ->middleware('auth')
        ->name('wnip.networking.register');
    Route::get('/networking/{networkingSession}/waiting', [NetworkingPageController::class, 'waitingRoom'])->name('wnip.networking.waiting');
    Route::get('/networking/{networkingSession}/live', [NetworkingPageController::class, 'live'])->name('wnip.networking.live');

    Route::get('/podcasts', [PodcastPageController::class, 'index'])->name('wnip.podcasts.index');
    Route::get('/podcasts/{podcastSeries}', [PodcastPageController::class, 'show'])->name('wnip.podcasts.series');

    Route::get('/interviews', [InterviewPageController::class, 'index'])->name('wnip.interviews.index');
    Route::get('/interviews/{interview}', [InterviewPageController::class, 'show'])->name('wnip.interviews.show');
    Route::get('/interviews/{interview}/waiting-room', [InterviewPageController::class, 'waitingRoom'])->name('wnip.interviews.waiting');
    Route::post('/interviews/{interview}/slots/{interviewSlot}/score', [InterviewPageController::class, 'score'])
        ->middleware('auth')
        ->name('wnip.interviews.score');
});

