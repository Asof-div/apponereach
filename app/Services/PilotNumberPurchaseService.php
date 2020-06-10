<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Models\Operator\PilotNumber;
use App\Models\Tenant;
use Carbon\Carbon;

class PilotNumberPurchaseService{

	public function __construct(){
	}

    public function search(Tenant $tenant, $data){
        
        $number = isset($data['number']) ? $data['number'] : null ;
        $pattern = isset($data['pattern']) ? $data['pattern'] : 'ends' ;
        $type = isset($data['type']) ? $data['type'] : 'regular' ;
        $match = '';
        if($pattern == 'ends'){
            $match = '%'.$number; 
        }
        elseif ($pattern == 'starts') {
            $match = $number.'%'; 
        }
     
        $pilot_numbers = PilotNumber::where('number', 'like', $match)->where('available',1)->where('tenant_id', null)->where('purchased', 0)->inRandomOrder()->take(20)->get(); 


        $pilot_numbers->map(function ($number){                
                $number['amount'] = $number->line_type->price;
                $number['type'] = $number->line_type->label;
                $number['currency'] = 'NGN';
                return $number;
            });

        return ['numbers' => $pilot_numbers, 'success' => 'Success'];
 
    }


    public function vanitySearch(Tenant $tenant, $data){
        
        $number = isset($data['number']) ? $data['number'] : null ;
        $type = isset($data['type']) ? $data['type'] : 'Local' ;

        if ($type) {
            $pilot_numbers = PilotNumber::where('number', 'like', '%' . $number . '%')->where('available',1)->where('tenant_id', null)->where('purchased', 0)->where('status', 'unallocated')->inRandomOrder()->take(20)->get(); 

            return ['numbers' => $pilot_numbers, 'success' => 'Success'];
        }

        return collect([]);
    }


    public function addToCart(Tenant $tenant, $data){
        
        if(!$tenant){

            return ['numbers' => [], 'status' => 'error', 'error'=> ['System can not recognise customer ']];
        }
        
        
        // $this->flushPilotNumbers($tenant);
        $number_id = isset($data['number_id']) ? (int)$data['number_id'] : '';

        $pilot = PilotNumber::where('tenant_id', null)->where('available', 1)->where('id', $number_id)->first();
        $time = Carbon::now();
        
        if($pilot){
            
            $pilot->update(['tenant_id' => $tenant->id, 'available' => 0, 'release_time' => $time->addMinutes('360'), 'status' => 'cart']);  

            $pilot_numbers = PilotNumber::where('tenant_id', $tenant->id)->where('purchased', 0)->orderBy('release_time', 'desc')->where('available',0)->get();  
            $pilot_numbers->map(function ($number){
                $number['countdown'] = $number->countdown;
                $number['amount'] = $number->line_type->price;
                $number['type'] = $number->line_type->label;
                $number['currency'] = 'NGN';
                return $number;
            });

            return ['numbers' =>$pilot_numbers, 'status' => 'success', 'success'=> 'This Number have been reserved for you '];

        }else{
            
            $pilot_numbers = PilotNumber::where('tenant_id', $tenant->id)->where('purchased', 0)->orderBy('release_time', 'desc')->where('available',0)->get();  
            $pilot_numbers->map(function ($number){
                $number['countdown'] = $number->countdown;
                $number['amount'] = $number->line_type->price;
                $number['type'] = $number->line_type->label;
                $number['currency'] = 'NGN';
                return $number;
            });

            return ['numbers' => $pilot_numbers, 'status' => 'error', 'error'=> ['This Number have been reserved by another customer ']];    
        }

       
    }

    public function removeFromCart(Tenant $tenant, $data){

        if(!$tenant){

            return ['numbers' => [], 'status' => 'error', 'error'=> ['System can not recognise customer ']];
        }

        $number_id = isset($data['number_id']) ? (int) $data['number_id'] : '';

        $pilot = PilotNumber::where('tenant_id', $tenant->id)->where('status', 'cart')->where('id', $number_id)->get()->first();
        if($pilot){

            $pilot->update(['tenant_id' => null, 'available' => 1, 'release_time' => null, 'status' => 'unallocated']);  

           $pilot_numbers = PilotNumber::where('tenant_id', $tenant->id)->where('purchased', 0)->orderBy('release_time', 'desc')->where('available',0)->get();  
            $pilot_numbers->map(function ($number){
                $number['countdown'] = $number->countdown;
                $number['amount'] = $number->line_type->price;
                $number['type'] = $number->line_type->label;
                $number['currency'] = 'NGN';
            return $number;
            });

            return ['status'=>'success', 'success' => 'Number successfully released', 'numbers' => $pilot_numbers];

        }else{

            $pilot_numbers = PilotNumber::where('tenant_id', $tenant->id)->where('purchased', 0)->orderBy('release_time', 'desc')->where('available',0)->get();  
            $pilot_numbers->map(function ($number){
                $number['countdown'] = $number->countdown;
                $number['amount'] = $number->line_type->price;
                $number['type'] = $number->line_type->label;
                $number['currency'] = 'NGN';

            return $number;
            });
            
            return ['status'=>'error', 'error' => 'This action cannot be performed', 'numbers' => $pilot_numbers];
        }
        
    }

    private function flushPilotNumbers($tenant){

        $pilot_numbers = PilotNumber::where('tenant_id', $tenant->id)->where('purchased', 0)->where('available',0)->orderBy('release_time', 'desc')->get();  

        foreach ($pilot_numbers as $pilot) {
            
            $pilot->update(['tenant_id' => null, 'available' => 1, 'release_time' => null, 'status' => 'unallocated']);

        }

    }

}