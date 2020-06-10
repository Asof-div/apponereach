<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Subscription;

class SubscriptionReminderMail extends Mailable implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $subscription;
    
    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $email = env('MAIL_FROM_ADDRESS');
        $subscription = $this->subscription;
        $tenant = $subscription->tenant;
        $days = $subscription->end_time->diffInDays(\Carbon\Carbon::today()); 

        $this->from($email, "Onereach Renewals ")->subject("Your onereach subscription is due to expire in {$days} days")->view('mails.subscription', ['days' => $days, 'tenant' => $tenant, 'package' => $subscription->package, 'subscription' => $subscription ]);

        return $this;
    }
}
