<?php 
namespace App\Helpers;

use App\Models\Package;
use App\Models\Tenant;
use Carbon\Carbon;

class PlanHelper{

	private $plan = null;
	private $package = null;
	private $period = 'monthly';
	private $saving = null;
	private $stage = 1;
	private $price = null;
	private $currency = null;
	private $type = 'prepaid';
	private $start_date = null;
	private $end_date = null;

	public function __construct($plan = null){
 
        if(!is_null('plan') && count(explode('_', $plan)) > 1 ){
       		$this->plan = $plan;
            $content = explode('_', $plan);
        	$this->period = strtolower($content[0]) ?? null;    	
			$this->package = Package::where('name', 'ilike', $content[1])->first() ?? null;            
            
        	$this->calculatePrice();
        }

	}
	public function setPackageByID($id){

		$this->package = Package::find($id) ?? null;            
	}

	public function setPackageByName($name){

		$this->package = Package::where('name', 'ilike', $name)->first() ?? null;            
	}

	public function setPackage(Package $package){

		$this->package = $package;
	}

	public function calculatePrice(){
		if($this->type == 'prepaid' && $this->period == 'monthly'){

			$this->price = $this->package ? $this->package->price : null;  
			$this->saving = null;
			$this->currency = $this->package ? $this->package->currency : null; 

		}elseif ($this->type == 'prepaid' && $this->period == 'annually'){

			$this->price = $this->package ? $this->package->annually : null;  
			$this->saving = $this->package ? number_format( $this->package->discountOff(), 2 ).'% off' : null;  
			$this->currency = $this->package ? $this->package->currency : null; 

		}
	}
	public function setPeriod($cycle = 'monthly'){
		switch (strtolower($cycle)) {
			case 'monthly':
				$this->period == 'monthly';
				break;
			case 'annually':
				$this->period == 'annually';
				break;
			
			default:
				$this->period == 'monthly';
				break;
		}
	}

	public function setPlan($plan){
		
		if(!is_null('plan') && count(explode('_', $plan)) > 1 ){
       		$this->plan = $plan;
            $content = explode('_', $plan);
        	$this->period = strtolower($content[0]) ?? null;    	
			$this->package = Package::where('name', 'ilike', $content[1])->first() ?? null;            
            
        	$this->calculatePrice();
        }            
	}

	public function getPackage(){
		return $this->package;
	}

	public function getPlan(){
		return $this->plan;
	}

	public function getCurrency(){
		return $this->currency;
	}

	public function getPrice(){
		return $this->price;
	}

	public function getPeriod(){
		return $this->period;
	}

	public function getDuration(){
		if($this->period == 'monthly'){
			return '1 Month';
		}
		if($this->period == 'annually'){
			return '1 Year';
		}
	}

	public function getDesc(){
		if($this->period == 'monthly'){
			return 'Monthly Subscription for '. $this->package->name.' Plan .';
		}
		if($this->period == 'annually'){
			return 'Annual Subscription for '. $this->package->name.' Plan .';
		}	
	}

	public function getSavings(){
		return $this->saving;
	}

	public function getStage(){
		return $this->stage;
	}

	public function isValid(){
		
		if( !(is_null($this->package)) && !(is_null($this->period)) ){
			return true;
		}
		return false;
	}

	public function setStage($stage = 1){
		$this->stage = $stage;
	}

	public function getStartDate(){
		return $this->start_date;
	}

	public function getEndDate(){
		return $this->end_date;
	}

	public function setStartDate($start = null){
		
	    $this->start_date = is_null($start) ? $start : Carbon::now();  
	    if( strtolower($this->period) == 'monthly' ){

	        $this->end_date = $this->start_date->copy()->addMonth();

	    }

	    elseif( strtolower($this->period) == 'annually' ){
	    	
	        $this->end_date = $this->start_date->copy()->addYear();

	    }
	}

}