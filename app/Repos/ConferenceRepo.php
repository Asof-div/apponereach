<?php

namespace App\Repos;

use App\Models\CallFlow;
use App\Models\CallRoute;
use App\Models\Conference;
use App\Models\PilotLine;

use App\Models\VirtualReceptionistMenu;

use Carbon\Carbon;

class ConferenceRepo extends DestinationRepo {

	public function store($user, $data) {

		$tenant = $user->tenant;

		$members = $data['members'];

		$now        = Carbon::today();
		$end        = isset($data['expiration']) && strtolower($data['expiration']) != 'never'?$now->copy()->modify('+'.$data['expiration']):null;
		$conference = Conference::create([
				'tenant_id'    => $tenant->id,
				'code'         => $tenant->code,
				'context'      => $tenant->code,
				'number'       => $this->generateNumber($user),
				'name'         => $data['name'],
				'guest_pin'    => $data['guest_pin'],
				'admin_pin'    => rand(100000, 999999),
				'type'         => $data['type'],
				'enable_audio' => 1,
				'enable_video' => isset($data['enable_video']) && strtolower($data['type']) != 'local'?$data['enable_video']:false,
				'record'       => isset($data['record'])?$data['record']:false,
				'wait_admin'   => isset($data['wait_admin'])?$data['wait_admin']:false,
				'announce'     => isset($data['announce'])?$data['announce']:false,
				'lock'         => isset($data['lock'])?$data['lock']:false,
				'members'      => $members,
				'expiration'   => isset($data['expiration'])?$data['expiration']:'2weeks',
				'start_date'   => $now,
				'end_date'     => $end
			]);

		$flow = CallFlow::create([
				'tenant_id'         => $conference->tenant_id,
				'context'           => $conference->context,
				'code'              => $conference->context,
				'direction'         => 'intercom', //intercom, outbound, inbound, test - extension
				'dial_string'       => $data['extension'],
				'title'             => $conference->name,
				'conditions'        => 'destination',
				'wday'              => '',
				'mon'               => '',
				'start_day'         => '',
				'end_day'           => '',
				'start_time'        => '',
				'end_time'          => '',
				'custom_day'        => '',
				'active'            => $tenant->status(),
				'send_to_voicemail' => '0',
				'voicemail_number'  => '',
				'record_call'       => 0,
				'dest_type'         => 'Conference',
				'dest_id'           => $conference->id,
				'dest_params'       => json_encode([
						'action'          => 'table',
						'value'           => $conference->id,

					]),

			]);

		$conference->update([
				'call_flow_id' => $flow->id,
			]);

		return $conference;

	}

	public function update($user, $conference, $data) {

		$tenant = $user->tenant;

		$now = $conference->start_date;
		$end = isset($data['expiration']) && strtolower($data['expiration']) != 'never'?$now->copy()->modify('+'.$data['expiration']):null;

		$members = $data['members'];

		$conference->update([
				'name'         => $data['name'],
				'guest_pin'    => $data['guest_pin'],
				'type'         => $data['type'],
				'enable_audio' => 1,
				'enable_video' => isset($data['enable_video']) && strtolower($data['type']) != 'local'?$data['enable_video']:false,
				'record'       => isset($data['record'])?$data['record']:false,
				'wait_admin'   => isset($data['wait_admin'])?$data['wait_admin']:false,
				'announce'     => isset($data['announce'])?$data['announce']:false,
				'lock'         => isset($data['lock'])?$data['lock']:false,
				'members'      => $members,
				'expiration'   => isset($data['expiration'])?$data['expiration']:'2weeks',
				'start_date'   => $now,
				'end_date'     => $end
			]);

		$flow = $conference->call_flow;
		if ($flow) {

			$flow->update([
					'dial_string' => $data['extension'],
					'title'       => $conference->name,
					'conditions'  => 'destination',
					'active'      => $tenant->status(),

				]);
		} else {

			$flow = CallFlow::create([
					'tenant_id'         => $conference->tenant_id,
					'context'           => $conference->context,
					'title'             => $conference->name,
					'code'              => $conference->context,
					'direction'         => 'intercom', //intercom, outbound, inbound, test - extension
					'dial_string'       => $data['extension'],
					'conditions'        => 'destination',
					'wday'              => '',
					'mon'               => '',
					'start_day'         => '',
					'end_day'           => '',
					'start_time'        => '',
					'end_time'          => '',
					'custom_day'        => '',
					'active'            => $tenant->status(),
					'send_to_voicemail' => '0',
					'voicemail_number'  => '',
					'record_call'       => 0,
					'dest_type'         => 'Conference',
					'dest_id'           => $conference->id,
					'dest_params'       => json_encode([
							'action'          => 'table',
							'value'           => $conference->id,

						]),

				]);

			$conference->update([
					'call_flow_id' => $flow->id,
				]);
		}

		return $conference;
	}

	public function delete($conference) {

		if ($conference) {
			$flow = $conference->call_flow;
			if ($flow) {
				$flow->delete();
			}

			$conference->delete();

		}

	}

	public function deletable($conference, &$destination) {

		if (!$conference) {return false;}

		$routes = CallRoute::company($conference->tenant_id)->where('module_type', 'App\Models\Conference')->where('module_id', $conference->id)->get();
		if (count($routes) > 0) {
			$destination = "Automated Route";
			return false;
		}

		$menus = VirtualReceptionistMenu::company($conference->tenant_id)->where('module_type', 'App\Models\Conference')->where('module_id', $conference->id)->get();
		if (count($menus) > 0) {
			$destination = "IVR";
			return false;
		}

		$pilot_lines = PilotLine::company($conference->tenant_id)->where('module_type', 'App\Models\Conference')->where('module_id', $conference->id)->get();
		if (count($pilot_lines) > 0) {
			$destination = "Pilot Number";
			return false;
		}

		return true;
	}

	private function generateNumber($user) {
		$number = rand(100000, 999999);

		$conferences = Conference::company($user->tenant_id)->get()->pluck('number');

		while ($conferences->search($number) !== false) {
			$number = rand(100000, 999999);
		}

		return $number;
	}

}