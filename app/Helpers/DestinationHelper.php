<?php 
namespace App\Helpers;

class DestinationHelper{

	private $name = null;
	private $type = null;
	private $module = null;
	private $destination = null;
	private $icon = null;
	private $endpoints = null;

	public function __construct($module){
		
		if($module && is_object($module)){

			$type = (new \ReflectionClass($module))->getShortName();
			// $this->type = get_class($module);
			$this->destination = $module;
			$this->setTypes($type);
		}

	}

	public function setTypes($type){

		switch ($type) {
			case 'Extension':
				$this->extension();
				break;
			case 'Number':
				$this->number();
				break;
			case 'GroupCall':
				$this->group();
				break;
			case 'VirtualReceptionist':
				$this->receptionist();
				break;
			case 'PlayMedia':
				$this->playback();
				break;
			case 'VoicemailInbox':
				$this->voicemail();
				break;
			
			default:
				
				break;
		}
	}


	public function getName(){
		return $this->name;
	}

	public function getType(){
		return $this->type;
	}

	public function getIcon(){
		return $this->icon;
	}

	public function getEndpoints(){
		return $this->endpoints;
	}

	public function extension(){

		$this->type = "Ring On An Extension";
		$this->icon = "fa fa-user";
		$this->name = $this->destination->name;
		$this->endpoints[] = ['action' => '', 'icon' => 'fa fa-tty', 'number' => $this->destination->number];

	}

	public function group(){

		$this->type = "Ring A Group";
		$this->icon = "fa fa-users";
		$this->name = $this->destination->name;
		$this->endpoints[] = ['action' => '', 'icon' => 'fa fa-users', 'number' => $this->destination->numbers];

	}

	public function number(){

		$this->type = "Ring On A Number";
		$this->icon = "fa fa-phone";
		$this->name = $this->destination->name;
		$this->endpoints[] = ['action' => '', 'icon' => 'fa fa-phone', 'number' => $this->destination->number];


	}	

	public function voicemail(){

		$this->type = "Send To Voicemail";
		$this->icon = "fa fa-inbox";
		$this->name = $this->destination->title;
		$this->endpoints[] = ['action' => '', 'icon' => 'fa fa-inbox', 'number' => $this->destination->user];

	}

	public function playback(){

		$this->type = "Play A Message";
		$this->icon = "fa fa-sound";
		$this->name = $this->destination->name;
		$this->endpoints[] = ['action' => '', 'icon' => 'fa fa-sound', 'number' => $this->destination->path];

	}

	public function receptionist(){

		$this->type = "Send To Virtual Receptionist";
		$this->icon = "fa fa-th-large";
		$this->name = $this->destination->name;
		foreach ($this->destination->menus as $menu) {
	
			$this->endpoints[] = ['action' => "PRESS ". $menu->key ." TO " .$menu->action_label, 'icon' => $menu->menu_icon, 'number' => $menu->value];
			
		}

	}




}