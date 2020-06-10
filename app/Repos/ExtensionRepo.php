<?php

namespace App\Repos;

use App\Models\CallFlow;
use App\Models\CallRoute;
use App\Models\Extension;
use App\Models\PilotLine;

use App\Models\User;
use App\Models\VirtualReceptionistMenu;

use Auth;

class ExtensionRepo extends DestinationRepo {

	public function __construct() {

	}

	public function store($data) {

		$tenant = Auth::user()?Auth::user()->tenant:null;

		$user  = User::find($data['user_id']);
		$exten = Extension::create([
				'name'           => $data['name'],
				'user_id'        => $data['user_id'],
				'tenant_id'      => $tenant->id,
				'number'         => $data['extension'],
				'context'        => $tenant->code,
				'exten_reg'      => $tenant->code.$data['extension'],
				'password'       => substr(md5('freeswitch'.time().'abcdefg'), 0, 10),
				'voicemail'      => isset($data['voicemail'])?1:0,
				'type'           => 'sip_profile',
				'call_recording' => isset($data['call_recording'])?1:0,
			]);

		$flow = CallFlow::create([
				'tenant_id'         => $exten->tenant_id,
				'context'           => $exten->context,
				'code'              => $exten->context,
				'title'             => $exten->name,
				'direction'         => 'intercom', //intercom, outbound, inbound, test - extension
				'dial_string'       => $exten->number,
				'conditions'        => 'destination',
				'wday'              => '',
				'mon'               => '',
				'start_day'         => '',
				'end_day'           => '',
				'start_time'        => '',
				'end_time'          => '',
				'custom_day'        => '',
				'active'            => $tenant->status(),
				'send_to_voicemail' => $exten->voicemail,
				'voicemail_number'  => $exten->exten_reg,
				'record_call'       => $exten->call_recording,
				'dest_type'         => 'Extension',
				'dest_id'           => $exten->id,
				'dest_params'       => json_encode([
						'action'          => 'bridge',
						'value'           => $exten->exten_reg,
					]),

			]);

		$exten->update([
				'call_flow_id' => $flow->id,
			]);

		$this->generateFile($exten);

		return $exten->load(['call_flow', 'user']);
	}

	public function update($exten, $data) {

		$tenant = Auth::user()?Auth::user()->tenant:null;

		shell_exec('rm -r /var/www/freeswitch/'.$exten->exten_reg.'.xml');
		$password = $exten->password;
		if (isset($data['change_password'])) {
			$password = substr(md5('freeswitch'.time().'abcdefg'), 0, 10);
		}

		$user = User::find($data['user_id']);
		$exten->update([
				'name'           => $data['name'],
				'user_id'        => $data['user_id'],
				'number'         => $data['extension'],
				'exten_reg'      => $tenant->code.$data['extension'],
				'context'        => $tenant->code,
				'password'       => $password,
				'voicemail'      => isset($data['voicemail'])?1:0,
				'call_recording' => isset($data['call_recording'])?1:0,
			]);

		$this->generateFile($exten);

		if ($exten->call_flow) {

			$flow = $exten->call_flow;
			$flow->update([
					'dial_string'       => $exten->number,
					'title'             => $exten->name,
					'conditions'        => 'destination',
					'active'            => $tenant->status(),
					'send_to_voicemail' => $exten->voicemail,
					'voicemail_number'  => $exten->exten_reg,
					'record_call'       => $exten->call_recording,
					'dest_type'         => 'Extension',
					'dest_id'           => $exten->id,
					'dest_params'       => json_encode([
							'action'          => 'bridge',
							'value'           => $exten->exten_reg,
						]),
					'fallback'        => strtolower($user->fallback_type) == 'hangup'?false:true,
					'fallback_params' => $user->fallback_action,
					'fallback_type'   => strtolower($user->fallback_type),
				]);

		} else {

			$flow = CallFlow::create([
					'tenant_id'         => $exten->tenant_id,
					'context'           => $exten->context,
					'title'             => $exten->name,
					'code'              => $exten->context,
					'direction'         => 'intercom', //intercom, outbound, inbound, test - extension
					'dial_string'       => $exten->number,
					'conditions'        => 'destination',
					'wday'              => '',
					'mon'               => '',
					'start_day'         => '',
					'end_day'           => '',
					'start_time'        => '',
					'end_time'          => '',
					'custom_day'        => '',
					'active'            => $tenant->status(),
					'send_to_voicemail' => $exten->voicemail,
					'voicemail_number'  => $exten->exten_reg,
					'record_call'       => $exten->call_recording,
					'dest_type'         => 'Extension',
					'dest_id'           => $exten->id,
					'dest_params'       => json_encode([
							'action'          => 'bridge',
							'valiue'          => $exten->exten_reg,
						]),
					'fallback'        => strtolower($user->fallback_type) == 'hangup'?false:true,
					'fallback_params' => $user->fallback_action,
					'fallback_type'   => strtolower($user->fallback_type),

				]);

			$exten->update([
					'call_flow_id' => $flow->id,
				]);
		}

		return $exten->load(['call_flow', 'user']);
	}

	public function changePassword($exten) {

		$tenant = Auth::user()?Auth::user()->tenant:null;

		shell_exec('rm -r /var/www/freeswitch/'.$exten->exten_reg.'.xml');

		$password = substr(md5('freeswitch'.time().'abcdefg'), 0, 10);

		$exten->update([
				'password' => $password,
			]);

		$this->generateFile($exten);

		return $exten->load(['call_flow', 'user']);
	}

	public function updateFallback($user) {
		foreach ($user->extensions as $exten) {
			$flow = $exten->call_flow;
			if ($flow) {

				$flow->update([

						'fallback'        => strtolower($user->fallback_type) == 'hangup'?false:true,
						'fallback_params' => $user->fallback_action,
						'fallback_type'   => strtolower($user->fallback_type),
					]);
			}
		}
	}

	public function delete($exten) {

		$flow = $exten->call_flow;
		if ($flow) {
			$flow->delete();
		}
		$exten->delete();
	}

	public function deletable($exten, &$destination) {

		$pilot_lines = PilotLine::company($exten->tenant_id)->where('module_type', 'App\Models\Extension')->where('module_id', $exten->id)->get();
		if (count($pilot_lines) > 0) {
			$destination = 'Pilot-number';
			return false;
		}

		$routes = CallRoute::company($exten->tenant_id)->where('module_type', 'App\Models\Extension')->where('module_id', $exten->id)->get();
		if (count($routes) > 0) {
			$destination = 'Automated Route';
			return false;
		}

		$menus = VirtualReceptionistMenu::company($exten->tenant_id)->where('module_type', 'App\Models\Extension')->where('module_id', $exten->id)->get();
		if (count($menus) > 0) {
			$destination = 'IVR';
			return false;
		}

		if (count($exten->groups) > 0) {
			$destination = 'Group Call';
			return false;
		}

		return true;
	}

	public function generateFile(Extension $exten) {

		$tenant = Auth::user()->tenant;

		$xmlout = new \XMLWriter();
		// $xmlout->openURI('10001.xml');

		$xmlout->openMemory();
		$xmlout->setIndent(true);
		$xmlout->setIndentString('  ');
		// $xmlout->startDocument('1.0', 'UTF-8', 'no');

		//start include
		$xmlout->startElement('include');

		//start user
		$xmlout->startElement('user');
		$xmlout->writeAttribute('id', $exten->exten_reg);

		//start params
		$xmlout->startElement('params');

		$xmlout->startElement('param');
		$xmlout->writeAttribute('name', 'password');
		// $xmlout->writeAttribute('value', '$${default_password}');
		$xmlout->writeAttribute('value', $exten->password);

		//end param
		$xmlout->endElement();
		if ($exten->voicemail) {

			$xmlout->startElement('param');
			$xmlout->writeAttribute('name', 'vm-password');
			$xmlout->writeAttribute('value', $exten->voicemail_pin?$exten->voicemail_pin:$exten->number);
			//end param
			$xmlout->endElement();
		}

		//end params
		$xmlout->endElement();

		//start variables
		$xmlout->startElement('variables');

		$xmlout->startElement('variable');
		$xmlout->writeAttribute('name', 'toll_allow');
		$xmlout->writeAttribute('value', 'domestic,international,local');
		//end variable
		$xmlout->endElement();

		$xmlout->startElement('variable');
		$xmlout->writeAttribute('name', 'accountcode');
		$xmlout->writeAttribute('value', $exten->exten_reg);
		//end variable
		$xmlout->endElement();

		$xmlout->startElement('variable');
		$xmlout->writeAttribute('name', 'user_context');
		// $xmlout->writeAttribute('value', $exten->context);
		$xmlout->writeAttribute('value', 'default');
		//end variable
		$xmlout->endElement();

		$xmlout->startElement('variable');
		$xmlout->writeAttribute('name', 'tenant_code');
		$xmlout->writeAttribute('value', $exten->context);
		//end variable
		$xmlout->endElement();

		$xmlout->startElement('variable');
		$xmlout->writeAttribute('name', 'tenant_id');
		$xmlout->writeAttribute('value', $exten->tenant_id);
		//end variable
		$xmlout->endElement();

		$xmlout->startElement('variable');
		$xmlout->writeAttribute('name', 'user_id');
		$xmlout->writeAttribute('value', $exten->user_id);
		//end variable
		$xmlout->endElement();

		$xmlout->startElement('variable');
		$xmlout->writeAttribute('name', 'effective_caller_id_name');
		$xmlout->writeAttribute('value', $exten->name."  ".$exten->number);
		//end variable
		$xmlout->endElement();

		$xmlout->startElement('variable');
		$xmlout->writeAttribute('name', 'effective_caller_id_number');
		$xmlout->writeAttribute('value', $exten->number);
		//end variable
		$xmlout->endElement();

		$xmlout->startElement('variable');
		$xmlout->writeAttribute('name', 'outbound_caller_id_name');
		$xmlout->writeAttribute('value', $tenant->name);
		//end variable
		$xmlout->endElement();

		$xmlout->startElement('variable');
		$xmlout->writeAttribute('name', 'outbound_caller_id_number');
		$xmlout->writeAttribute('value', '$${outbound_caller_id}');
		//end variable
		$xmlout->endElement();

		$xmlout->startElement('variable');
		$xmlout->writeAttribute('name', 'callgroup');
		$xmlout->writeAttribute('value', 'techsupport');
		//end variable
		$xmlout->endElement();

		// end variables
		$xmlout->endElement();

		// end user
		$xmlout->endElement();

		// end include
		$xmlout->endElement();

		// $xmlout->endDocument();
		//echo $xmlout->outputMemory();

		// $xmlout->flush();

		file_put_contents('/var/www/freeswitch/'.$exten->exten_reg.'.xml', $xmlout->flush(true));

		shell_exec('chmod -f 777 /var/www/freeswitch/'.$exten->exten_reg.'.xml');

		try {
			// $connection = ssh2_connect('192.168.234.41', 22);
			// ssh2_auth_password($connection,'root','T3chlab!');
			// $shell = ssh2_shell($connection,"bash /root/reloadxml.sh");
		} catch (\Exception $e) {

			// return ['error' => ['Cant not complete acction.'], 'status' => 'error'];

		}

	}

}