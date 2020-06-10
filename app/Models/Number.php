<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;

use App\Traits\NonGlobalTenantScopeTrait;


class Number extends Model
{
 	use NonGlobalTenantScopeTrait;
 	
 	protected $guarded = ['id'];
    //

    static function boot(){

        parent::boot();

    }

    public function tenant(){

    	return $this->belongsTo(Tenant::class);
    }

    public function call_flow(){

    	return $this->belongsTo(CallFlow::class);
    }


    public function user(){

        return $this->belongsTo(User::class);
    }

    public function groups() {

        return $this->morphMany(GroupMember::class, 'member')->latest();
    }


    public function cdrs(){
        $number = $this;
        return CDR::where('tenant_id', $this->tenant_id)->where('caller_id_num', 'like', '%'. $this->number)->orWhere('callee_id_num', 'like', '%'. $this->number)->orderBy('start_timestamp', 'desc')->orWhere(function($query) use ($number){
                $query->where('destination_type', 'number')->where('destination', $number->number);
            })->get()->take(30);
    }



}
