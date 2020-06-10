<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Ticket;

class AssignTicketMail extends Mailable implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $ticket;
    public $operator;
    public $assignee;

    public function __construct($ticket, $operator, $assignee)
    {

        $this->ticket = $ticket;   
        $this->operator = $operator;
        $this->assignee = $assignee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $ticket = Ticket::find($this->ticket->id);
        $assignee = $this->assignee;
        $operator = $this->operator;

        $from_email = env('MAIL_FROM_ADDRESS');        $this->from($from_email, "Onereach ")->subject('Assign Ticket: '. $ticket->title ." [". $ticket->ticket_no ."]")->view('mails.assign_ticket', compact('ticket', 'operator', 'assignee'));

        return $this;
    }
}
