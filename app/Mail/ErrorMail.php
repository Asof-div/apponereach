<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\AppError;

class ErrorMail extends Mailable implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $event;
    public $portal;
    public $action;
    public $exception;
    public $email;
    public $control;

    public function __construct($portal, $control_class, $action, $email, $exception)
    {
        $this->portal = $portal;
        $this->control_class = $control_class;
        $this->action = $action;
        $this->email = $email;
        $this->exception = $exception;       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $portal = $this->portal;
        $action = $this->action;
        $exception = $this->exception;
        $email = $this->email;
        $control = $this->control;

        $from_email = env('MAIL_FROM_ADDRESS');

        \Log::log('info', $this->event->control_class);

        $this->from($from_email, "Onereach ERROR")->subject("ERROR REPORT")->view('mails.error_mail', ['portal' => $portal, 'control' => $control, 'action' => $action, 'exception' => substr( $exception, 0,255) ]);

        return $this;
    }
}
