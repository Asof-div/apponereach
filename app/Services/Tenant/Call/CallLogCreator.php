<?php

namespace App\Services\Tenant\Call;

use App\Models\CDR;
use Carbon\Carbon;

class CallLogCreator
{
	protected $userRepo;
	protected $callRepo;

	public function __construct()
	{

	}

	public function create($details)
	{

		
		$duration = Carbon::parse($details['answer_time'])->diffInSeconds($details['end_time'], false);
		$details['duration'] = $duration < 0 ? 0 : $duration;

		CDR::create([
    		'uuid' => $details['uuid'],
    		'user_id' => $user->id,
    		'caller_num' => $details['caller'],
    		'callee_num' => $details['callee'],
    		'call_type' => $details['call_type'],
    		'direction' => $details['direction'],
    		'status' => $details['status'],
    		'duration' => $details['duration'],
    		'start_time' => (new \DateTime($details['start_time']))->format('Y-m-d H:i:s'),
    		'answer_time' => (new \DateTime($details['answer_time']))->format('Y-m-d H:i:s'),
    		'end_time' => (new \DateTime($details['end_time']))->format('Y-m-d H:i:s'),
    	]);

		return true;
	}

}