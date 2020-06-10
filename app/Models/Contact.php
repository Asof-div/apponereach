<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;

use App\Traits\NonGlobalTenantScopeTrait;


class Contact extends Model
{
	use BelongsToTenant, NonGlobalTenantScopeTrait;
	
    protected $guarded = ['id'];

    public function tenant(){

    	return $this->belongsTo(Tenant::class);
    }

    public function account(){

    	return $this->belongsTo(Account::class, 'account_id');
    }

    public function calls(){

    	return Call::where('tenant_id', $this->tenant_id)->where('caller_id_num', 'like', '%'. $this->phone)->orWhere('callee_id_num', 'like', '%'. $this->phone)->orderBy('start_timestamp', 'desc')->get()->take(20);
    }



}
