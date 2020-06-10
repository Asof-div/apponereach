<?php

namespace App\Mail;

use App\Models\Meeting;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ScheduleMeetingMail extends Mailable implements ShouldQueue {
	use InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * Create a new message instance.
	 *
	 * @return void
	 */

	public $meeting;

	public function __construct($meeting) {
		$this->meeting = $meeting;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {

		$meeting  = Meeting::find($this->meeting->id);
		$start    = Carbon::parse($meeting->start_date.' '.$meeting->start_time);
		$end      = Carbon::parse($meeting->end_date.' '.$meeting->end_time);
		$filename = "invite.ics";
		$data[0]  = "BEGIN:VCALENDAR";
		$data[1]  = "PRODID:-//Google Inc//Google Calendar 70.9054//EN";
		$data[2]  = "VERSION:2.0";
		$data[3]  = "CALSCALE:GREGORIAN";
		$data[4]  = "METHOD:REQUEST";
		$data[8]  = "BEGIN:VEVENT";
		$data[9]  = "DTSTART:{$meeting->iCalDateFormat($start)}";
		$data[10] = "DTEND:{$meeting->iCalDateFormat($end)}";
		$data[11] = "DTSTAMP:{$meeting->iCalDateFormat($end)}";
		$data[12] = "UID:{$meeting->id}";
		$data[13] = "CREATED:{$meeting->iCalDateFormat($meeting->created_at)}";
		$data[14] = "DESCRIPTION:{ $meeting->description }";
		$data[15] = "LAST-MODIFIED:{$meeting->iCalDateFormat($meeting->updated_at)}";
		$data[16] = "LOCATION:{$meeting->meeting_room_id}";
		$data[17] = "SEQUENCE:0";
		$data[18] = "STATUS:CONFIRMED";
		$data[19] = "SUMMARY: { $meeting->conference->call_flow->dial_string }";
		$data[20] = "TRANSP:OPAQUE";
		$data[21] = "END:VEVENT";
		$data[22] = "END:VCALENDAR";

		$data = implode("\r\n", $data);
		header("text/calendar");
		file_put_contents($filename, "\xEF\xBB\xBF".$data);

		$from_email = env('MAIL_FROM_ADDRESS');
		$this->from($from_email, "UC PBX ")->subject($meeting->subject)->view('mails.schedule_meeting', compact('meeting'))->attach($filename, array('mime' => "text/calendar"));

		return $this;
	}
}
