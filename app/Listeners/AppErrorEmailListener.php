<?php

namespace App\Listeners;

use App\Events\AppError;
use App\Mail\AppErrorMailler;

use Mail;

class AppErrorEmailListener {
	/**
	 * Create the event listener.
	 *
	 * @return void
	 */
	public function __construct() {
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  UserWasRegistered  $event
	 * @return void
	 */
	public function handle(AppError $event) {
		$exception = $event->exception;

		// \Log::log('info', $exception);
		Mail::to('adeyinkab24@yahoo.com')->queue((new AppErrorMailler($exception)));

	}
}
