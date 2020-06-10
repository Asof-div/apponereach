<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AppErrorMailler extends Mailable implements ShouldQueue {
	use InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */

	public $exception;

	public function __construct($exception) {

		$this->exception = $exception;

	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {

		$exception = $this->exception;

		$from_email = env('MAIL_FROM_ADDRESS');

		\Log::log('info', $exception);

		$this->from($from_email, "APP ERROR")->subject("ERROR REPORT")->view('mails.error_mail', compact('exception'));

		return $this;
	}
}
