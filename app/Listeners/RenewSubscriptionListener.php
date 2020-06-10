<?php

namespace App\Listeners;

use App\Events\RenewSubscription;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Carbon\Carbon;

class RenewSubscriptionListener
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
     * @param  RenewSubscription  $event
     * @return void
     */
    public function handle(RenewSubscription $event)
    {
        $subscription = $event->subscription;
        $today = Carbon::today();
        $days = $today->diffInDays($subscription->start_time, false);
        if($days >= 1){
            $subscription->update([
                'status' => 'processing',

            ]);
            $billing = $subscription->billings->where('billing_type', 'renewal')->first();

            if($billing){
                $billing->update(['status' => 'success']);
            }
        }else {
            $subscription->update([
                'status' => 'success',

            ]);
            $tenant = $subscription->tenant;
            $tenant->update([
                'status' => 'Activated',
                'package_id' => $subscription->package_id,
                'expiration_date' => $subscription->end_time,
                'last_subscription' => $tenant->current_subscription,
                'current_subscription' => $subscription->id,
            ]);
            $billing = $subscription->billings->where('billing_type', 'renewal')->first();

            if($billing){
                $billing->update(['status' => 'success']);
            }

        }

    }
}
