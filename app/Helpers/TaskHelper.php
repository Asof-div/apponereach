<?php 

namespace App\Helpers;


class TaskHelper
{
	
	function __construct()
	{
		
	}

	public function getStatus(){
		return [
			'Not Started',
			'In Progress',
			'Deffered',
			'Waiting',
			'Completed',
		];
	}

	public function getType(){
		return [
			'Call',
			'Email',
			'Meeting',
			'Event',
			'Others',
			'Opportunity',
			'Quote',
			'Invoice'

		];
	}

	public function getPriority(){

		return [
			'Low',
			'Medium',
			'High'

		];
	}

	public function getPeriod(){
		return [
			'Once',
			'Daily',
			'Weekly',
			'Biweekly',
			'Monthly',
		];
	}


	public function setData($column, $value){
		$data = [];
		switch ( strtolower($column) ){
			case 'call':
			case 'email':
				$data['contact_id'] = $value;
				break;
			case 'meeting':
			case 'event':
			case 'opportunity':
				$data['account_id'] = $value;
				break;
			case 'quote':
			case 'invoice':
				$data['opportunity_id'] = $value;
				break;
			default:
				
				break;
		}

		return $data;
	}

}