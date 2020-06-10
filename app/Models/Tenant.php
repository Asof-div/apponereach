<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\ActivityLog;

class Tenant extends Model
{
    use ActivityLog;

    protected static $logs = [];
    protected $with = [];

    protected $guarded = ['id'];

    protected $dates = ['expiration_date', 'created_at', 'updated_at'];
    
    static function boot(){

        parent::boot();

        //Tenant::observe(new \App\Observers\TenantActionObserver);
    }
    
    public function info(){

    	return $this->hasOne(TenantInfo::class);
    }

    public function cart(){

        return $this->hasOne(Cart::class);
    }

    public function users(){

    	return $this->hasMany(User::class);
    }

    public function subscriptions(){

        return $this->hasMany(Subscription::class, 'tenant_id', 'id');
    }

    public function pilot_lines(){

        return $this->hasMany(PilotLine::class, 'tenant_id', 'id');
    }

    public function extensions(){

        return $this->hasMany(Extension::class, 'tenant_id', 'id');
    }

    public function numbers(){

        return $this->hasMany(Number::class, 'tenant_id', 'id')->orderBy('slot');
    }

    public function group_ring(){

        return $this->hasMany(GroupCall::class, 'tenant_id', 'id');
    }


    public function lastsubscription(){

        return $this->belongsTo(Subscription::class, 'last_subscription');
    }

    public function subscription(){

        return $this->belongsTo(Subscription::class, 'current_subscription');
    }

    public function package(){

        return $this->belongsTo(Package::class, 'package_id');
    }

    public function billings(){

        return $this->hasMany(Billing::class, 'tenant_id', 'id');
    }

    public function getValidityAttribute(){

        $start = $this->subscription ? $this->subscription->start_time ?  $this->subscription->start_time->format('Y-M-d') : '' : '';

        $end = $this->expiration_date ? $this->expiration_date->format('Y-M-d') : '';
        return  $start . ' : ' . $end;
    }

    public function getActivationAttribute(){

        if($this->activated == 0 && strtolower($this->status) == 'registration') {

            return 'INCOMPLETE SIGNUP';
        }

        return $this->status;
    }

    function editable(){

        if(strtolower($this->billing_method) == 'postpaid' || strtolower($this->billing_method) == 'postpay'){
            return true;
        }
        
        return false;
    }

    function deleteable(){

        if(strtolower($this->status) !== 'activated' ){
            return true;
        }
        
        return false;
    }

    function status(){

        if(strtolower($this->status) == 'registration'){
            return false;
        }elseif (strtolower($this->status) == 'activated') {
            return true;
        }elseif (strtolower($this->status) == 'expired') {
            return false;
        }elseif (strtolower($this->status) == 'suspended') {
            return false;
        }elseif (strtolower($this->status) == 'leave') {
            return false;
        }elseif (strtolower($this->status) == 'idle') {
            return false;
        }elseif (strtolower($this->status) == '') {
            return false;
        }

        return true;
    }

    function getStatusMsg(){

        if(strtolower($this->status) == 'registration'){
            return "Your account have not been activated!";
        }elseif (strtolower($this->status) == 'activated') {
            return "Your account is active.";
        }elseif (strtolower($this->status) == 'expired') {
            return "You account subscription have expired. Please Renew before your account is de-activated.";
        }elseif (strtolower($this->status) == 'suspended') {
            return "You account have been suspended. Please Renew your account to activate the system.";
        }elseif (strtolower($this->status) == 'leave') {
            return "You account no longer exist. Please contact the our customer care to revive your account.";
        }elseif (strtolower($this->status) == 'idle') {
            return "Your account have not been activated!";
        }elseif (strtolower($this->status) == '') {
            return false;
        }

        return "Your account have not been activated!";
    }

    function cycle(){

        if(strtolower($this->billing_method) == 'postpaid' && strtolower($this->billing_cycle) == 'monthly'){
            return 'Monthly';
        }

        return $this->billing_cycle;
    }


    public function logo()
    {
        $image = public_path('storage/'.$this->info->logo);

        if (!file_exists($image) || !$this->info->logo) {
            // Return default
            return '';
        }

        return asset('storage/'.$this->info->logo);
    }

}
