<?php

namespace App\Repos;

use App\Models\Call;
use App\Models\CallRate;

class CallRepository {
	public function findCallRateByCountryId(int $country_id) {
		$call_rate = CallRate::where('country_id', $country_id)->first();

		if (!is_null($call_rate)) {
			return $call_rate->rate;
		}

		return null;
	}

	public function saveLog($details, $contact = null) {
		$call = Call::create([
				'uuid'            => $details['uuid'],
				'caller_num'      => $details['caller'],
				'callee_num'      => $details['callee'],
				'direction'       => $details['direction'],
				'status'          => $details['status'],
				'source'          => $details['source'],
				'dest_type'       => isset($details['dest_type'])?$details['dest_type']:'Unknown',
				'duration'        => $details['duration'],
				'billsec'         => $details['billsec'],
				'call_rate'       => $details['call_rate'],
				'airtime'         => $details['call_rate']*$details['billsec'],
				'tenant_id'       => $details['tenant_id'],
				'contact_id'      => $contact,
				'recorded'        => isset($details['recorded'])?true:false,
				'call_recording'  => isset($details['recorded'])?$details['call_recording']:null,
				'leave_voicemail' => isset($details['leave_voicemail'])?true:false,

				'start_time'  => (new \DateTime($details['start_time']))->format('Y-m-d H:i:s'),
				'answer_time' => $details['answer_time'] && $details['answer_time'] != 'nil'?(new \DateTime($details['answer_time']))->format('Y-m-d H:i:s'):null,
				'end_time'    => (new \DateTime($details['end_time']))->format('Y-m-d H:i:s'),

				// $table->text('play_media_name')->nullable();
				// $table->string('play_media_type')->nullable();
				// $table->jsonb('call_trace')->nullable();
			]);

		\Log::log('info', $call);

		return $call;

	}

}