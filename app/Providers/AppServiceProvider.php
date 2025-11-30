<?php

namespace App\Providers;

use App\Observers\InteractiveInterviewSlotObserver;
use App\Observers\JobApplicationObserver;
use App\Observers\JobsInterviewScheduleObserver;
use App\Observers\TalentAiHeadhunterInterviewObserver;
use App\Services\UtilitiesQuickToolsService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Jobs\Models\InterviewSchedule;
use Jobs\Models\JobApplication;
use Jobi\WebinarNetworkingInterviewPodcast\Models\InterviewSlot;
use Gigvora\TalentAi\Domain\Headhunters\Models\HeadhunterInterview;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // app()->bind('first_service_helper', function($app){
        //     dd("this is my first service container");
        // });

        // app()->bind('second_service_helper', function($app){
        //     dd("this is my second service container");
        // });

        // app()->bind('third_service_helper', function($app){
        //     dd("this is my third service container");
        // });

        //Call from hole application
        //Pattern: app()->make('first_service_helper');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        $this->registerInterviewObservers();

        if (! $this->app->runningInConsole()) {
            View::composer('*', function ($view) {
                $service = app(UtilitiesQuickToolsService::class);
                $view->with('utilitiesContext', $service->detectContext());
            });
        }
    }

    protected function registerInterviewObservers(): void
    {
        if (class_exists(InterviewSchedule::class)) {
            InterviewSchedule::observe(JobsInterviewScheduleObserver::class);
        }

        if (class_exists(InterviewSlot::class)) {
            InterviewSlot::observe(InteractiveInterviewSlotObserver::class);
        }

        if (class_exists(JobApplication::class)) {
            JobApplication::observe(JobApplicationObserver::class);
        }

        if (class_exists(HeadhunterInterview::class)) {
            HeadhunterInterview::observe(TalentAiHeadhunterInterviewObserver::class);
        }
    }
}
