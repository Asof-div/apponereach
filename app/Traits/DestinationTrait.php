<?php

namespace App\Traits;


trait DestinationTrait
{

	public function getDestinationLabelAttribute(){
		$label = ''; 
		switch (strtolower($this->destination_type)) {
			case 'extension' :
                $label = 'Ring On An Extension';
                break;

            case 'number' :
                $label = 'Ring On A Number';
                break;

            case 'group' :
                $label = 'Ring A Group';
                break;

            case 'receptionist' :
                $label = 'Direct To Virtual Receptionist';
                break;

            case 'playback' :
                $label = 'Play A Custom Message';
                break;

            case 'voicemail' :
                $label = 'Send To Voicemail';
                break;

            case 'conference' :
                $label = 'Join A Private Conference';
                break;

            default :
                $label = '';
                break;

		}
		return $label;
	}
		
}