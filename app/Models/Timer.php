<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;
use App\Traits\NonGlobalTenantScopeTrait;

class Timer extends Model
{
   	use BelongsToTenant, NonGlobalTenantScopeTrait;

   	protected $table = "timers";

   	protected $guarded = ['id'];

    public function getDaysOfWeekAttribute(){

   		$binary = str_split( strrev( base_convert($this->days, 10, 2) ) );

   		$days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

   		$string = "";
   		$status = false;
   		foreach ($binary as $key => $value) {
   			
   			if($value == 1){
   				if($status){

   					$string .= ", ".$days[$key];
   				}else{
   					$string .= $days[$key];
   					$status = true;
   				}
   			}

   		}

   		return $string;

   		
   	}

   	public function getStrategyAttribute(){

   		$binary = str_split( strrev( base_convert($this->days, 10, 2) ) );

   		$days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

   		switch (strtolower($this->period)) {
   			case 'date':
   				return  $this->start_mon .', '. $this->start_day .', '. $this->start_time .' to '. $this->end_time;
   				break;
   			case 'custom_date':
   				return  $this->start_mon .', '. $this->custom_day .', '.  $this->days_of_week .', '. $this->start_time .' to '. $this->end_time;
   				break;
   			case 'range':
   				return  $this->start_mon .', '. $this->start_day .', '. $this->start_time .' to '. $this->end_time .' , '. $this->end_day   ;
   				break;
   			default:
   				return $this->days_of_week .' '. $this->start_time .' to '. $this->end_time;
   				break;
   		}
   		
   	}

    public function auto_attendants(){

    	return $this->hasMany(AutoAttendant::class, 'timer_id');
    }

}	
