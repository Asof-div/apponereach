<?php

namespace App\Repos;

use App\Models\AutoAttendant;
use App\Models\CallFlow;

use App\Models\PlayMedia;

class AutoAttendantRepo extends DestinationRepo {

	public function __construct() {

	}

	public function store($user, $data) {

		$tenant = $user->tenant;
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
			$greeting_path = $this->generateSoundFile($user, $greeting_text, 'attendant'.time());
		}

		$record     = false;
		$weekdays   = 0;
		$start_time = '00:00';
		$end_time   = '23:00';
		if ($data['record'] == 'all') {
			$record   = true;
			$weekdays = 127;

		}

		$attendant = AutoAttendant::create([
				'title'              => $data['name'],
				'days'               => $data['weekdays'],
				'period'             => $data['period'],
				'start_mon'          => $data['start_mon'],
				'start_day'          => $data['start_day'],
				'end_day'            => $data['end_day'],
				'start_time'         => $data['start_time'],
				'custom_day'         => $data['custom_day'],
				'end_time'           => $data['end_time'],
				'pilot_line_id'      => $data['line_id'],
				'number'             => $data['number'],
				'order'              => 0,
				'tenant_id'          => $tenant->id,
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

	}

	public function reorder($user, $number, $data) {

		$tenant = $user->tenant;
		\DB::table('call_flows')->where('dial_string', $number)->where('priority', '<>', 0)->delete();
		if (count($data) > 0) {
			foreach ($data as $key => $value) {
				$attendant = AutoAttendant::find($value['id']);
				if ($attendant) {

					$flow = CallFlow::create([
							'tenant_id'         => $tenant->id,
							'context'           => $tenant->code,
							'code'              => $tenant->code,
							'direction'         => 'inbound', //intercom, outbound, inbound, test - extension
							'dial_string'       => $attendant->number,
							'conditions'        => $attendant->period,
							'greeting'          => $attendant->greeting,
							'greeting_type'     => $attendant->greeting_type,
							'greeting_path'     => $attendant->greeting_path,
							'active'            => true,
							'priority'          => $key+1,
							'wday'              => json_encode($this->wday($attendant->days)),
							'mon'               => $this->mon($attendant->start_mon),
							'start_day'         => $attendant->start_day,
							'end_day'           => $attendant->end_day,
							'start_time'        => $attendant->start_time,
							'end_time'          => $attendant->end_time,
							'custom_day'        => $attendant->custom_day,
							'send_to_voicemail' => $attendant->voicemail,
							'voicemail_number'  => $attendant->number,
							'record_call'       => $attendant->record,
							'recording_wday'    => json_encode($this->wday($attendant->recording_days)),
							'recording_period'  => $attendant->recording_period,
							'recording_start'   => $attendant->recording_start,
							'recording_end'     => $attendant->recording_end,
							'dest_type'         => ucfirst($attendant->destination_type),
							'dest_id'           => $attendant->module_id,
							'dest_params'       => json_encode([
									'action'          => 'table',
									'value'           => $attendant->module_id,
								]),

						]);

					$attendant->update([
							'order'        => $key+1,
							'enable'       => true,
							'call_flow_id' => $flow->id
						]);
				}
			}
		}

	}

	public function update($user, $attendant, $data) {

		$tenant = $user->tenant;
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
			$greeting_path = $this->generateSoundFile($user, $greeting_text, 'attendant'.$attendant->id);
		}

		$record     = false;
		$weekdays   = 0;
		$start_time = '00:00';
		$end_time   = '23:00';
		if ($data['record'] == 'all') {
			$record   = true;
			$weekdays = 127;

		}
		\DB::table('call_flows')->where('dial_string', $data['number'])->where('id', $attendant->call_flow_id)->delete();

		$attendant->update([
				'title'              => $data['name'],
				'days'               => $data['weekdays'],
				'period'             => $data['period'],
				'start_mon'          => $data['start_mon'],
				'start_day'          => $data['start_day'],
				'end_day'            => $data['end_day'],
				'start_time'         => $data['start_time'],
				'custom_day'         => $data['custom_day'],
				'end_time'           => $data['end_time'],
				'pilot_line_id'      => $data['line_id'],
				'number'             => $data['number'],
				'order'              => 0,
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
				'call_flow_id'       => null,
				'enable'             => false,
			]);

	}

	public function delete($attendant) {
		$flow = $attendant->flow;
		if ($flow) {
			$flow->delete();
		}
		$attendant->delete();
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

	private function mon($mon) {

		switch ($mon) {
			case 'JAN':
				return 1;
				break;
			case 'FEB':
				return 2;
				break;
			case 'MAR':
				return 3;
				break;
			case 'APR':
				return 4;
				break;
			case 'MAY':
				return 5;
				break;
			case 'JUN':
				return 6;
				break;
			case 'JUL':
				return 7;
				break;
			case 'AUG':
				return 8;
				break;
			case 'SEP':
				return 9;
				break;
			case 'OCT':
				return 10;
				break;
			case 'NOV':
				return 11;
				break;
			case 'DEC':
				return 12;
				break;

			default:
				return '';
				break;
		}

		return '';
	}

}