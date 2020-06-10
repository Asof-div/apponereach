<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ActivateAccountMail extends Mailable implements ShouldQueue {
	use InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */
	public $user;
	public $tenant;

	public function __construct($user) {
		$this->user   = $user;
		$this->tenant = $user->tenant;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {

		$email        = env('MAIL_FROM_ADDRESS');
		$subscription = $this->tenant->subscription;
		$package      = $subscription->package;
		$this->from($email, "Service Plan")->subject("Onereach Service Info")->view('mails.account', ['subscription' => $subscription, 'package' => $package, 'tenant' => $this->tenant, 'title' => 'Subscription Information']);

		return $this;
	}
}
