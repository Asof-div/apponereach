<?php

namespace App\Repos;

use App\Mail\ScheduleMeetingMail;

use App\Models\Meeting;
use Carbon\Carbon;
use Mail;

class MeetingRepo extends DestinationRepo {

	public function store($user, $conf, $data) {

		$tenant = $user->tenant;

		$members = $data['members'];

		$now = Carbon::today();
		$end = isset($data['expiration']) && strtolower($data['expiration']) != 'never'?$now->copy()->modify('+'.$data['expiration']):null;

		$start   = Carbon::parse($data['start_date'].' '.$data['start_time']);
		$end     = $start->copy()->modify($data['duration']);
		$collect = explode(',', $data['invite_by_email']);
		foreach ($collect as $value) {
			$emails[] = trim($value);
		}

		$meeting = Meeting::create([
				'subject'         => $data['subject'],
				'conference_id'   => $data['conference_id'],
				'description'     => $data['description'],
				'duration'        => $data['duration'],
				'creator_id'      => $user->name,
				'created_by'      => $user->id,
				'start_date'      => $start->format('Y-m-d'),
				'start_time'      => $start->format('H:s'),
				'end_date'        => $end->format('Y-m-d'),
				'end_time'        => $end->format('H:s'),
				'tenant_id'       => $conf->tenant_id,
				'code'            => $conf->code,
				'context'         => $conf->context,
				'meeting_room_id' => $conf->number,
				'email_invites'   => json_encode($emails),
			]);

		if (count($emails) > 0) {
			Mail::to($emails)->queue((new ScheduleMeetingMail($meeting)));
		}
		return $meeting;

	}

}