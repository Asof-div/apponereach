<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NewTicketMail extends Mailable implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $ticket;

    public function __construct($ticket)
    {

        $this->ticket = $ticket;   

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $ticket = $this->ticket;

        $from_email = env('MAIL_FROM_ADDRESS');

        $this->from($from_email, "Onereach ")->subject('Ticket: '. $ticket->title ." [". $ticket->ticket_no ."]")->view('mails.new_ticket', compact('ticket'));

        return $this;
    }
}
