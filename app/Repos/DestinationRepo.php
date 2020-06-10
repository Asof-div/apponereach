<?php

namespace App\Repos;

use App\Models\Conference;
use App\Models\Extension;
use App\Models\GroupCall;
use App\Models\Number;
use App\Models\PlayMedia;
use App\Models\VirtualReceptionist;

class DestinationRepo {
	public $tenant;
	public $type;

	public function __contruct() {

	}

	public function getModuleType() {

		return $this->type;
	}

	public function getAction() {

	}

	public function getActionLabel() {

	}

	public function setDestination($destination_type) {

		switch (strtolower($destination_type)) {
			case 'e':
			case 'extension':
			case 'sip_profile':
				$type = "App\Models\Extension";
				break;
			case 'n':
			case 'number':
				$type = "App\Models\Number";
				break;
			case 'g':
			case 'group':
			case 'groupcall':
				$type = "App\Models\GroupCall";
				break;
			case 'm':
			case 'receptionist':
				$type = "App\Models\VirtualReceptionist";
				break;
			case 's':
			case 'playback':
				$type = "App\Models\PlayMedia";
				break;
			case 'v':
			case 'voicemail':
				$type = "";
				break;
			case 'c':
			case 'conference':
				$type = "App\Models\Conference";
				break;
			default:
				$type = null;
				break;
		}

		$this->type = $type;

	}

	public function getParams($action, $id) {

		switch (strtolower($action)) {
			case 'e':
			case 'extension':
				$exten = Extension::find($id);
				if (!$exten) {

					$this->abort();

				}
				return ['type' => 'extension', 'action' => 'bridge', 'value' => $exten->exten_reg];
				break;
			case 'n':
			case 'number':
				$number = Number::find($id);
				if (!$number) {

					$this->abort();

				}
				return ['type' => 'number', 'action' => 'bridge', 'value' => $number->number];
				break;
			case 'g':
			case 'group':
			case 'groupcall':
				$group = GroupCall::find($id);
				if (!$group) {

					$this->abort();

				}
				return ['type' => 'group', 'action' => 'table', 'value' => $group->id];
				break;
			case 'm':
			case 'receptionist':
				$receptionist = VirtualReceptionist::find($id);
				if (!$receptionist) {

					$this->abort();

				}
				return ['type' => 'receptionist', 'action' => 'table', 'value' => json_decode($receptionist->menu_params, true)];
				break;
			case 's':
			case 'playback':
				$playmedia = PlayMedia::find($id);
				if (!$playmedia) {

					$this->abort();

				}
				return ['type' => 'playback', 'action' => 'table', 'value' => $id];
				break;
			case 'v':
			case 'voicemail':

				return ['type' => 'voicemail', 'action' => 'bridge', 'value' => "Voicemail Box"];

				break;
			case 'c':
			case 'conference':
				$conference = Conference::find($id);
				if ($conference) {
					return ['type' => 'conference', 'action' => 'table', 'value' => $id];
				} elseif ($id == 'any') {
					return ['type' => 'conference', 'action' => 'bridge', 'value' => $id];
				}
				break;
			default:
				$type = [];
				break;
		}
	}

	public function abort() {

		return [];
	}

	function generateSoundFile($user, $text, $name) {
		$path         = $user->tenant->tenant_no."/tts/";
		$hash         = md5($name);
		$storage_path = storage_path("app/public/").$path.$hash;
		shell_exec('mkdir -pm 777 '.storage_path("app/public/").$path);
		$this->removeStorageFile($storage_path.'.wav');
		shell_exec("touch ".$storage_path.'.txt');
		$fp = fopen($storage_path.'.txt', 'w');
		fwrite($fp, $text);
		fclose($fp);
		shell_exec("cat -A ".$storage_path.".txt | text2wave -F 8000 -o ".$storage_path.'.wav');
		shell_exec('chmod -f 777 '.public_path('storage/'.$path.$hash.'.*'));
		shell_exec('rm '.$storage_path.".txt");

		return $path.$hash.'.wav';
	}

	function removeStorageFile($path) {
		$file = public_path('storage/'.$path);

		if (file_exists($file)) {
			shell_exec('rm '.$file);

		}

	}

}