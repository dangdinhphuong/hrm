<?php

namespace App\Providers;

use App\Events\Order\GenerateCode;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Listeners\Order\ShipmentDistributeAuto;
use App\Listeners\Order\GenerateActivationCode;
use App\Listeners\Order\GenerateActivationCodeWithGifts;
use App\Events\Work\TimesheetUpdated;
use App\Listeners\Work\UpdateMonthlyTimesheetSummaryListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        TimesheetUpdated::class => [
            UpdateMonthlyTimesheetSummaryListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
