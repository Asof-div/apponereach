<?php

namespace App\Repos;

use App\Models\CallFlow;
use App\Models\CallRoute;

use App\Models\Number;
use App\Models\PilotLine;
use App\Models\PlayMedia;
use App\Models\VirtualReceptionist;
use App\Models\VirtualReceptionistMenu;

use Auth;

class VirtualReceptionistRepo extends DestinationRepo {

	public function __contruct() {

	}

	public function store($data) {
		$composingIvrMessage = null;

		$user       = Auth::user();
		$tenant     = $user->tenant;
		$ivrType    = isset($data['ivr_menu_type'])?$data['ivr_menu_type']:'tts';
		$data_param = [];

		$greeting_text = '';
		$greeting_path = '';

		if ($ivrType == 'sound') {

			$sound_id = isset($data['sound_id'])?$data['sound_id']:'';
			$sound    = PlayMedia::find($sound_id);
			if ($sound) {
				$greeting_path = $sound->path;
			}

		}

		$receptionist = VirtualReceptionist::create([
				'name'          => $data['name'],
				'context'       => $tenant->code,
				'number'        => $data['extension'],
				'tenant_id'     => $tenant->id,
				'play_media_id' => $ivrType == 'sound' && isset($data['sound_id'])?$data['sound_id']:null,
				'ivr_type'      => $ivrType,
			]);

		foreach ($data['menus_actions'] as $key => $menuAction) {
			$action = isset($menuAction['menu_type'])?$menuAction['menu_type']:"";
			$this->setDestination($action);
			$type = $this->getModuleType();
			$id   = $menuAction['menu_action_id'];

			$params       = $this->getParams($action, $id);
			$data_param[] = array_merge(['key' => $key+1], $params);

			$key_press = $key+1;

			$composingIvrMessage = $composingIvrMessage."Press {$key_press} " .$menuAction['menu_label'].". \n";

			VirtualReceptionistMenu::create(
				array('tenant_id'          => $tenant->id,
					'key_press'               => $key+1,
					'menu_type'               => $action,
					'module_type'             => $type,
					'module_id'               => (int) $id,
					'virtual_receptionist_id' => $receptionist->id,
					'menu_label'              => $menuAction['menu_label'],
					'menu_action_label'       => $menuAction['menu_action_label'],
					'params'                  => json_encode($params)
				)
			);
		}

		$composingIvrMessage = $composingIvrMessage."Press * to Repeat Menu \n";
		$composingIvrMessage = $composingIvrMessage."Press # to Go Back To Previous Menu \n";
		$composingIvrMessage = $composingIvrMessage."If You Know The Person's Extension Press 0 Now!. \n";

		$composingIvrMessage = $ivrType == 'tts'?$composingIvrMessage:'';
		$greeting_text       = $composingIvrMessage;
		if ($ivrType == 'tts') {
			$greeting_path = $this->generateSoundFile($user, $greeting_text, 'ivr'.$receptionist->id);
		}
		$flow = CallFlow::create([
				'tenant_id'         => $receptionist->tenant_id,
				'context'           => $receptionist->context,
				'code'              => $receptionist->context,
				'title'             => $receptionist->name,
				'direction'         => 'intercom', //intercom, outbound, inbound, test - extension
				'dial_string'       => $receptionist->number,
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
				'dest_type'         => 'IVR',
				'dest_id'           => $receptionist->id,
				'dest_params'       => json_encode([
						'action'          => 'bridge',
						'value'           => json_encode(['ivr_msg'           => $composingIvrMessage, 'ivr_path'           => $greeting_path, "ivr_type"           => $ivrType, "menu"           => $data_param]),

					]),

			]);

		$receptionist->update(
			[
				'ivr_msg'      => $composingIvrMessage,
				'ivr_path'     => $greeting_path,
				'call_flow_id' => $flow->id,
				'menu_params'  => json_encode(["application"  => $ivrType, "menu"  => $data_param])
			]);

	}

	public function update($receptionist, $data) {
		$composingIvrMessage = null;
		$user                = Auth::user();
		$tenant              = $user->tenant;
		$ivrType             = isset($data['ivr_menu_type'])?$data['ivr_menu_type']:'tts';
		$data_param          = [];

		$greeting_text = '';
		$greeting_path = '';

		if ($ivrType == 'sound') {

			$sound_id = isset($data['sound_id'])?$data['sound_id']:'';
			$sound    = PlayMedia::find($sound_id);
			if ($sound) {
				$greeting_path = $sound->path;
			}

		}

		$receptionist->update([
				'name'          => $data['name'],
				'number'        => $data['extension'],
				'context'       => $tenant->code,
				'play_media_id' => $ivrType == 'sound' && isset($data['sound_id'])?$data['sound_id']:null,
				'ivr_type'      => $ivrType,
			]);
		$receptionist->menus()->delete();

		foreach ($data['menus_actions'] as $key => $menuAction) {
			$action = isset($menuAction['menu_type'])?$menuAction['menu_type']:"";
			$this->setDestination($action);
			$type = $this->getModuleType();
			$id   = $menuAction['menu_action_id'];

			$params       = $this->getParams($action, $id);
			$data_param[] = array_merge(['key' => $key+1], $params);

			$key_press = $key+1;

			$composingIvrMessage = $composingIvrMessage."Press {$key_press} " .$menuAction['menu_label'].". \n";

			VirtualReceptionistMenu::create(
				array('tenant_id'          => $tenant->id,
					'key_press'               => $key+1,
					'menu_type'               => $action,
					'module_type'             => $type,
					'module_id'               => (int) $id,
					'virtual_receptionist_id' => $receptionist->id,
					'menu_label'              => $menuAction['menu_label'],
					'menu_action_label'       => $menuAction['menu_action_label'],
					'params'                  => json_encode($params)
				)
			);

		}

		$composingIvrMessage = $composingIvrMessage."Press * to Repeat Menu \n";
		$composingIvrMessage = $composingIvrMessage."Press # to Go Back To Previous Menu \n";
		$composingIvrMessage = $composingIvrMessage."If You Know The Person's Extension Press 0 Now! \n";

		$composingIvrMessage = $ivrType == 'tts'?$composingIvrMessage:'';
		$greeting_text       = $composingIvrMessage;
		if ($ivrType == 'tts') {
			$greeting_path = $this->generateSoundFile($user, $greeting_text, 'ivr'.$receptionist->id);
		}

		$flow = $receptionist->call_flow;
		if ($flow) {

			$flow->update([
					'dial_string' => $receptionist->number,
					'conditions'  => 'destination',
					'title'       => $receptionist->name,
					'active'      => $tenant->status(),
					'dest_type'   => 'IVR',
					'dest_id'     => $receptionist->id,
					'dest_params' => json_encode([
							'action'    => 'bridge',
							'value'     => json_encode(['ivr_msg'     => $composingIvrMessage, 'ivr_path'     => $greeting_path, "ivr_type"     => $ivrType, "menu"     => $data_param]),
						]),

				]);
		} else {

			$flow = CallFlow::create([
					'tenant_id'         => $receptionist->tenant_id,
					'context'           => $receptionist->context,
					'code'              => $receptionist->context,
					'direction'         => 'intercom', //intercom, outbound, inbound, test - extension
					'title'             => $receptionist->name,
					'dial_string'       => $receptionist->number,
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
					'dest_type'         => 'IVR',
					'dest_id'           => $receptionist->id,
					'dest_params'       => json_encode([
							'action'          => 'bridge',
							'value'           => json_encode(['ivr_msg'           => $composingIvrMessage, 'ivr_path'           => $greeting_path, "ivr_type"           => $ivrType, "menu"           => $data_param]),

						]),

				]);

		}

		$receptionist->update(
			[
				'ivr_msg'      => $composingIvrMessage,
				'ivr_path'     => $greeting_path,
				'call_flow_id' => $flow->id,
				'menu_params'  => json_encode(["application"  => $ivrType, "menu"  => $data_param])
			]);

	}

	public function delete($receptionist) {

		if ($receptionist) {
			$flow = $receptionist->call_flow;
			if ($flow) {
				$flow->delete();
			}

			if ($receptionist->ivr_type == 'tts') {
				$this->removeStorageFile($receptionist->ivr_path);
			}
			foreach (VirtualReceptionistMenu::where('virtual_receptionist_id', $receptionist->id)->get() as $member) {
				$member->delete();
			}
			$receptionist->delete();

		}

	}

	public function deletable($receptionist) {
		if (!$receptionist) {return false;}
		$call_flows = CallFlow::where('dest_type', 'Receptionist')->where('dest_id', $receptionist->id)->get();
		if (count($call_flows) > 0) {
			return false;
		}

		$routes = CallRoute::where('module_type', 'App\Models\VirtualReceptionist')->where('module_id', $receptionist->id)->get();
		if (count($routes) > 0) {
			return false;
		}

		$menus = VirtualReceptionistMenu::company()->where('module_type', 'App\Models\VirtualReceptionist')->where('module_id', $receptionist->id)->get();
		if (count($menus) > 0) {
			return false;
		}

		$pilot_lines = PilotLine::where('module_type', 'App\Models\VirtualReceptionist')->where('module_id', $receptionist->id)->get();
		if (count($pilot_lines) > 0) {
			return false;
		}

		return true;
	}

	function generateComposedText($action, $key) {

	}

}