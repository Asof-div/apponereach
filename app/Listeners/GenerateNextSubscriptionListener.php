<?php

namespace App\Listeners;

use App\Events\GenerateNextSubscription;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateNextSubscriptionListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  GenerateNextSubscription  $event
     * @return void
     */
    public function handle(GenerateNextSubscription $event)
    {
        //
    }
}
