<?php

namespace App\Listeners;

use App\Events\SubscriptionReminder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use App\Models\User;
use App\Mail\SubscriptionReminderMail;
class SubscriptionReminderListener
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
     * @param  SubscriptionReminder  $event
     * @return void
     */
    public function handle(SubscriptionReminder $event)
    {
        $subscription = $event->subscription;
        $tenant = $subscription->tenant;
        $users = User::administrator()->company($tenant->id)->get();
        $cc = $users->whereNotIn('email', [$tenant->info->email])->pluck('email');
        Mail::to($tenant->info->email)->cc($cc)->bcc('abiodun.adeyinka@telvida.com')->queue((new SubscriptionReminderMail($event->subscription)) );

    }
}
