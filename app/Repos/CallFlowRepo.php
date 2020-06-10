<?php

namespace App\Repos;

use App\Models\CallRoute;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

class CallFlowRepo extends DestinationRepo {

	public function __construct() {

	}

	public function store($data) {

		$tenant = TenantManager::get();

		$param       = substr($data['greeting'], 0, 1) == 't'?$data['greeting_tts']:$data['sound_path'];
		$application = substr($data['greeting'], 0, 1) == 't'?'tts':'file';
		$this->setDestination($data['destination_type']);

		$call_route = CallRoute::create([
				'title'          => $data['title'],
				'greeting'       => substr($data['greeting'], 1),
				'tenant_id'      => $tenant->id,
				'greeting_type'  => $application,
				'greeting_param' => $param,
				'record'         => isset($data['record'])?1:0,
				'voicemail'      => isset($data['voicemail'])?1:0,
				'action'         => $data['destination_type'],
				'value'          => $data['destination_value'],
				'ring_time'      => $data['ring_time'],
				'module_id'      => $data['destination'],
				'module_type'    => $this->getModuleType(),
				'dest_type'      => $data['module'],
				'params'         => json_encode(["application"         => $application, "greeting"         => $param, "action"         => $this->getParams($data['destination_type'], $data['destination'])])

			]);

	}

	public function update($data) {

		$tenant = TenantManager::get();

		$call_route = CallRoute::find($data['route_id']);
		if ($call_route) {
			$param       = substr($data['greeting'], 0, 1) == 't'?$data['greeting_tts']:$data['sound_path'];
			$application = substr($data['greeting'], 0, 1) == 't'?'tts':'file';
			$this->setDestination($data['destination_type']);

			$call_route->update([
					'title'          => $data['title'],
					'greeting'       => substr($data['greeting'], 1),
					'tenant_id'      => $tenant->id,
					'greeting_type'  => $application,
					'greeting_param' => $param,
					'record'         => isset($data['record'])?1:0,
					'voicemail'      => isset($data['voicemail'])?1:0,
					'action'         => $data['destination_type'],
					'value'          => $data['destination_value'],
					'ring_time'      => $data['ring_time'],
					'module_id'      => $data['destination'],
					'module_type'    => $this->getModuleType(),
					'dest_type'      => $data['module'],
					'params'         => json_encode(["application"         => $application, "greeting"         => $param, "action"         => $this->getParams($data['destination_type'], $data['destination'])])

				]);
		}

	}
	function processDestination() {

	}

}