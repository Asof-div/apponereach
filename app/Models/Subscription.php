<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;
use App\Traits\NonGlobalTenantScopeTrait;
use Auth;

class Subscription extends Model
{
	use NonGlobalTenantScopeTrait;
	
    protected $guarded = ['id'];

    protected $dates = ['start_time', 'end_time', 'created_at', 'updated_at'];
 
    protected $casts = [
        // 'extra_msisdn' => 'array',
        // 'addons' => 'array',
    ];


    public function orders(){

    	return $this->hasMany(Order::class, 'subscription_id');
    }


    public function transactions(){

        return $this->hasMany(Transaction::class, 'subscription_id');
    }

    public function tenant(){

    	return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function cart(){

        return $this->belongsTo(Order::class, 'order_id');
    }

    public function generateCart(){

        if(!$this->cart){
            if(Auth::user()){
                $order = Order::Create([
                    'tenant_id' => $this->tenant_id,
                    'subscription_id' => $this->id,
                    'firstname' => Auth::user()->firstname,
                    'lastname' => Auth::user()->lastname,
                    'email' => Auth::user()->email,
                    'ordered_date' => \Carbon\Carbon::now(),
                    'expiry_date' => \Carbon\Carbon::now()->addHour(6),
                    'status' => 'processing',
                ]);    
            }else{
                $order = Order::Create([
                    'tenant_id' => $this->tenant_id,
                    'subscription_id' => $this->id,
                    'ordered_date' => \Carbon\Carbon::now(),
                    'expiry_date' => \Carbon\Carbon::now()->addHour(6),
                    'status' => 'processing',
                ]);
            }
            
            $this->update(['order_id' => $order->id]);
            return $order;
        }

        return $this->cart;
    }

    public function package(){

    	return $this->belongsTo(Package::class, 'package_id');
    }

    public function getNameAttribute(){

        return ucwords($this->lastname . " " . $this->firstname);
    }

    public function manager(){

        return $this->belongsTo(Operator::class, 'manager_id')->withDefault();
    }

    public function getDurationInMonthAttribute(){

        return (int) $this->duration / 30 ;
    }

    public function status(){
        if( strtolower($this->status) == 'success'){

            return "<span class='label label-success'> Success </label> ";
        }elseif( strtolower($this->status) == 'cancel'){
            
            return "<span class='label label-danger'> Cancel </label> ";
        }elseif( strtolower($this->status) == 'processing'){
            
            return "<span class='label label-primary'> Processing </label> ";
        }else{

            return "<span class='label label-info'> Pending </label> ";
        }
    }

    public function payment_status(){
        if( strtolower($this->payment_status) == "paid"){

            return "<span class='label label-success'> Paid </label> ";
        }elseif( strtolower($this->payment_status) == "unpaid"){
            
            return "<span class='label label-primary'> Unpaid </label> ";
        
        }elseif( strtolower($this->payment_status) == "processing"){
            
            return "<span class='label label-info'> Processing </label> ";
        }else{

            return "<span class='label label-danger'> Overdue </label> ";
        }
    }
    
}
