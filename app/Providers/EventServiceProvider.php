<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\UserWasRegistered' => [
            'App\Listeners\SendVerificationEmailListener',
        ],
        'App\Events\AppError' => [
            'App\Listeners\AppErrorEmailListener',
        ],
        'App\Events\SubscriptionReminder' => [
            'App\Listeners\SubscriptionReminderListener',
        ],
        'App\Events\GenerateNextSubscription' => [
            'App\Listeners\GenerateNextSubscriptionListener',
        ],
        'App\Events\RenewSubscription' => [
            'App\Listeners\RenewSubscriptionListener',
        ],
        'App\Events\PilotNumberDestination' => [
            'App\Listeners\PilotGenerateDestination',
        
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
