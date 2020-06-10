<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Ticket;

class StatusTicketMail extends Mailable implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $ticket;
    public $operator;
    public $status;

    public function __construct($ticket, $operator, $status)
    {

        $this->ticket = $ticket;   
        $this->operator = $operator;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $ticket = $this->ticket;
        $status = $this->status;
        $operator = $this->operator;

        $from_email = env('MAIL_FROM_ADDRESS');        
        $this->from($from_email, "Onereach ")->subject('Status Ticket Update: '. $ticket->title ." [". $ticket->ticket_no ."]")->view('mails.operator_status_ticket', compact('ticket', 'operator', 'status'));

        return $this;
    }
}
