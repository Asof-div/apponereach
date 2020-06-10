<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;
use App\Traits\NonGlobalTenantScopeTrait;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;

/**
* 
*/
class CallRoute extends Model
{
    use NonGlobalTenantScopeTrait;
  
    protected $guarded = ['id'];

    public function actions(){

    	return $this->hasMany(CallRouteAction::class, 'call_route_id');
    }

    public function tenant(){

    	return $this->belongsTo(Tenant::class);
    }

    public function greeting_value(){
      if( strtolower($this->greeting_type) == 'file' ){
        return 's'.$this->greeting;
      }elseif ( strtolower($this->greeting_type) == 'tts' ) {
        return 't'.$this->greeting;
      }else{
        return '';
      }

    }

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

	public function welcome(){

		return $this->belongsTo(PlayMedia::class, 'greeting');
	}


   	public function getCallPeriodAttribute(){

   		switch ($this->period) {
   			case 'work':
   				return 'Work Hour';
   				break;
   			case 'all':
   				return 'All Day';
   				break;
   			default:
				return ucfirst($this->period);
   				break;
   		}

   	}


    public function auto_attendants(){

      return $this->hasMany(AutoAttendant::class, 'call_route_id');
    } 


}
