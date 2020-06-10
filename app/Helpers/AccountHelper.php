<?php 
namespace App\Helpers;

class AccountHelper{

	private $types = null;
	private $sources = null;

	public function __construct(){
		$this->types = [
			'Analyst',
			'Competitor',
			'Consultant',
			'Customer',
			'Dead',
			'Other',
			'Personal',
			'Press',
			'Prospect',
			'Supplier',
			'Suspect'
		];

		$this->sources = [
			'Advertisement',
			'Email',
			'Mailshot',
			'Pay Per Click',
			'Press',
			'Refferal',
			'Social',
			'Telephone',
			'Web Search',
			'Web site',
			'Word of Mouth'
		];
	}

	public function getTypes(){

		return $this->types;
	}

	public function getSources(){

		return $this->sources;
	}

}