<?php
namespace App\Helpers;

class CountryCodeHelper{
	
	private $list = null;

	public function __contruct(){

		$this->list = $this->list();
	}

	public function getCountries(){

		return $this->list();
	} 


	private function list(){

		return [ 
				  ['name'=> 'USA', 'code'=> '+1'], 
				  ['name'=> 'UK', 'code'=> '+44'], 
				  ['name'=> 'Mexico', 'code'=> '+52'], 
				  ['name'=> 'India', 'code'=> '+91'], 
				  ['name'=> 'China', 'code'=> '+86'], 
				  ['name'=> 'Nigeria', 'code'=> '+234'], 
				
				];

	}

	public function getCountryByCode($code){

		$terms = array_filter($this->list(), function($term, $k) use ($code) {
            return $term['code'] == $code;
        } , ARRAY_FILTER_USE_BOTH );

		if(count($terms) < 1) {
			return ['name' => '', 'code' => ''];
		}
		$term = [];
		foreach ($terms as $value) {
			$term[] = $value;
		}
		return $term[0];
	}

}