<?php 

namespace App\Services;

class TenantOfficialContact {

	public $email = '';
	public $phone = '';
	public $no_of_staffs;

	public function __construct($old = null){

		if($old ){

			$this->email = $old->getEmail();
			$this->phone = $old->getPhone();
			$this->no_of_staff = $old->getNoOfStaffs();
		}
	}

	public function getEmail(){

		return $this->email;
	}

	public function setEmail($email){
		
		$this->email = $email;
	}

	public function getPhone(){

		return $this->phone;
	}

	public function setPhone($phone){
		
		$this->phone = $phone;
	}

	public function getNoOfStaffs(){

		return $this->no_of_staffs;
	}

	public function setNoOfStaffs($no_of_staffs){
		
		$this->no_of_staffs = $no_of_staffs;
	}
}