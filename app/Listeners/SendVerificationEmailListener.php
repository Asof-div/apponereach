<?php

namespace App\Listeners;

use App\Events\UserWasRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\NewUserMail as RegisteredMail;
use App\Mail\ActivateAccountMail;
use Mail;

class SendVerificationEmailListener
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
     * @param  UserWasRegistered  $event
     * @return void
     */
    public function handle(UserWasRegistered $event)
    {
        

        Mail::to($event->user->email)->queue((new RegisteredMail($event->user, $event->password)) );

        Mail::to('abiodun.adeyinka@telvida.com')->queue((new RegisteredMail($event->user, $event->password)) );

        Mail::to($event->user->email)->queue((new ActivateAccountMail($event->user)) );

        Mail::to('abiodun.adeyinka@telvida.com')->queue((new ActivateAccountMail($event->user)) );

    }
}
