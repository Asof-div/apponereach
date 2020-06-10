<?php

namespace App\Repos;

use App\Models\CallFlow;

use App\Models\PilotLine;
use App\Models\PlayMedia;

class PilotLineRepo extends DestinationRepo {

	public function __construct() {

	}

	public function setDefaultRoute($pilot, $data) {
		$user = request()->user();
		$this->setDestination($data['destination_type']);

		$greetingType  = isset($data['greeting_type'])?$data['greeting_type']:'';
		$greeting_text = '';
		$greeting_path = '';

		if ($greetingType == 'sound') {

			$sound_id = isset($data['sound_id'])?$data['sound_id']:'';
			$sound    = PlayMedia::find($sound_id);
			if ($sound) {
				$greeting_path = $sound->path;
			}

		} else {
			$greeting_text = isset($data['tts_msg'])?$data['tts_msg']:'';
			$greeting_path = $this->generateSoundFile($user, $greeting_text, 'pilotline'.$pilot->id);
		}

		$record     = false;
		$weekdays   = 0;
		$start_time = '00:00';
		$end_time   = '23:00';
		if ($data['record'] == 'all') {
			$record   = true;
			$weekdays = 127;

		} elseif ($data['record'] == 'custom') {
			$record = true;
			$start_time == '08:00';
			$end_time == '17:00';
			$weekdays = 62;

		}

		$pilot->update([
				'greeting'           => isset($data['sound_id'])?$data['sound_id']:null,
				'greeting_type'      => $data['greeting_type'],
				'greeting_text'      => $greeting_text,
				'greeting_path'      => $greeting_path,
				'module_id'          => $data['destination_id'],
				'module_type'        => $this->type,
				'destination_number' => $data['destination_number'],
				'destination_label'  => $data['destination_label'],
				'destination_type'   => $data['destination_type'],
				'record'             => $record,
				'recording_period'   => $data['record'],
				'recording_start'    => $start_time,
				'recording_end'      => $end_time,
				'recording_days'     => $weekdays,
			]);

		$this->generateFlow($pilot);
	}

	private function generateFlow(PilotLine $pilotline) {
		$flow   = $pilotline->flow;
		$tenant = $pilotline->tenant;
		if ($flow) {

			$flow->update([
					'dial_string'       => $pilotline->number,
					'context'           => $tenant->code,
					'conditions'        => 'destination',
					'greeting'          => $pilotline->greeting,
					'greeting_type'     => $pilotline->greeting_type,
					'greeting_path'     => $pilotline->greeting_path,
					'active'            => $pilotline->status,
					'priority'          => 0,
					'send_to_voicemail' => $pilotline->voicemail,
					'voicemail_number'  => $pilotline->number,
					'record_call'       => $pilotline->record,
					'recording_wday'    => json_encode($this->wday($pilotline->recording_days)),
					'recording_period'  => $pilotline->recording_period,
					'recording_start'   => $pilotline->recording_start,
					'recording_end'     => $pilotline->recording_end,
					'dest_type'         => ucfirst($pilotline->destination_type),
					'dest_id'           => $pilotline->module_id,
					'dest_params'       => json_encode([
							'action'          => 'table',
							'value'           => $pilotline->module_id,
						]),

				]);

		} else {

			$flow = CallFlow::create([
					'tenant_id'         => $tenant->id,
					'context'           => $tenant->code,
					'code'              => $tenant->code,
					'direction'         => 'inbound', //intercom, outbound, inbound, test - extension
					'dial_string'       => $pilotline->number,
					'conditions'        => 'destination',
					'greeting'          => $pilotline->greeting,
					'greeting_type'     => $pilotline->greeting_type,
					'greeting_path'     => $pilotline->greeting_path,
					'wday'              => '',
					'mon'               => '',
					'start_day'         => '',
					'end_day'           => '',
					'start_time'        => '',
					'end_time'          => '',
					'custom_day'        => '',
					'active'            => $pilotline->status,
					'priority'          => 0,
					'send_to_voicemail' => $pilotline->voicemail,
					'voicemail_number'  => $pilotline->number,
					'record_call'       => $pilotline->record,
					'recording_wday'    => json_encode($this->wday($pilotline->recording_days)),
					'recording_period'  => $pilotline->recording_period,
					'recording_start'   => $pilotline->recording_start,
					'recording_end'     => $pilotline->recording_end,
					'dest_type'         => ucfirst($pilotline->destination_type),
					'dest_id'           => $pilotline->module_id,
					'dest_params'       => json_encode([
							'action'          => 'table',
							'value'           => $pilotline->module_id,
						]),

				]);

			$pilotline->update(['call_flow_id' => $flow->id]);

		}

	}

	private function wday($days) {

		$binary = str_split(strrev(base_convert($days, 10, 2)));

		$wdays = [1, 2, 3, 4, 5, 6, 7];

		$wday = [];

		foreach ($binary as $key => $value) {

			if ($value == 1) {

				$wday[] = $wdays[$key];

			}

		}

		return $wday;

	}

}