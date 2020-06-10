<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\NonGlobalTenantScopeTrait;

class Transaction extends Model
{
	use NonGlobalTenantScopeTrait;

    protected $guarded = ['id'];
    
    protected $dates = ['paid_date', 'created_at', 'updated_at'];

    public function tenant(){

    	return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function subscription(){

    	return $this->belongsTo(Subscription::class, 'subscription_id');
    }


    public function order(){

    	return $this->belongsTo(Order::class, 'order_id');
    }



    public function status(){

        if( strtolower($this->status) == 'success' ){

            return "<span class='label-success label'>&#10003; ". ucfirst($this->status) ."</span>";
        }elseif(strtolower($this->status) == 'processing'){
                                    
            return "<span class='label label-primary'>&#10042; ". ucfirst($this->status) ."</span>";
        }elseif(strtolower($this->status) == 'pending'){
                                    
            return "<span class='label label-info'>&#10042; ". ucfirst($this->status) ."</span>";
        }else{
                                        
            return "<span class='label-danger label'>&#10005; ". ucfirst($this->status) ." </span>";

        }

    }

    public function payable(){
        
        if( strtolower($this->status) == 'success' ){

            return false;
        }elseif(strtolower($this->status) == 'processing'){
                                    
            return true;
        }elseif(strtolower($this->status) == 'pending'){
                                    
            return false;
        }else{
                                        
            return false;

        }

    }


}
