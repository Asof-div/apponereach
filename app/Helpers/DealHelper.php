<?php 
namespace App\Helpers;

class DealHelper{

	private $stages = null;
	private $lostStages = null;

	public function __construct(){
		$this->stages = [
			'In Development',
			'Information Gathering',
			'Proposal/Price Quote',
			'Negotiation/Review',
			'Invoice',
			'Close Won'

		];

		$this->lostStages = [
			'In Development',
			'Information Gathering',
			'Proposal/Price Quote',
			'Negotiation/Review',
			'Closed',

		];

	}

	public function getTypes(){

		return $this->types;
	}

	public function getSources(){

		return $this->sources;
	}

}