<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use App\Traits\NonGlobalTenantScopeTrait;

class Billing extends Model
{
    use NonGlobalTenantScopeTrait;

    protected $guarded = ['id'];
  
	public $incrementing = false;
    
    protected $dates = ['due_date', 'created_at', 'updated_at'];
	
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::generate()->string;
        });
    }

    public function pilotNumbers(){

        return $this->hasMany('App\Models\Operator\PilotNumber', 'billing_id', 'id');
    }

    public function tenant(){

        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function subscription(){

        return $this->belongsTo(Subscription::class, 'subscription_id');
    }


    public function getNameAttribute(){

        return ucwords($this->lastname . " " . $this->firstname);
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

    public function payment_status(){
        if( strtolower($this->payment_status) == "paid"){

            return "<span class='label label-success'> Paid </label> ";
        }elseif( strtolower($this->payment_status) == "unpaid"){
            
            return "<span class='label label-primary'> Unpaid </label> ";

        }elseif( strtolower($this->payment_status) == "processing"){
            
            return "<span class='label label-info'> Proccessing </label> ";
        }else{

            return "<span class='label label-danger'> Overdue </label> ";
        }
    }

}
