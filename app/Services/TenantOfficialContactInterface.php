<?php 

namespace App\Services;


interface TenantOfficialContactInterface{


	public function getEmail();

	public function setEmail($email);

	public function getPhone();

	public function setPhone($phone);

	public function getNoOfStaffs();

	public function setNoOfStaffs($no_of_staffs);

}