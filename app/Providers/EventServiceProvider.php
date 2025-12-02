<?php

namespace App\Providers;

use App\Events\AnalyticsEvent as CoreAnalyticsEvent;
use App\Listeners\ForwardJobsAnalyticsEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Jobs\Events\AnalyticsEvent;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AnalyticsEvent::class => [
            ForwardJobsAnalyticsEvent::class,
        ],
        CoreAnalyticsEvent::class => [
            ForwardJobsAnalyticsEvent::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
