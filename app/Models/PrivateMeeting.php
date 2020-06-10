<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\NonGlobalTenantScopeTrait;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;

class PrivateMeeting extends Model
{
	use NonGlobalTenantScopeTrait;
	
    protected $guarded = ['id'];

    public function tenant(){

    	return $this->belongsTo(Tenant::class);
    }


}
