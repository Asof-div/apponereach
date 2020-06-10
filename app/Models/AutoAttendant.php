<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use App\Traits\NonGlobalTenantScopeTrait;


class AutoAttendant extends Model
{
    use NonGlobalTenantScopeTrait;
    
    protected $guarded = ['id'];

    public function tenant(){

    	return $this->belongsTo(Tenant::class);
    }

    public function flow(){

        return $this->belongsTo(CallRoute::class, 'call_route_id', 'id');
    }

    public function timer(){

        return $this->belongsTo(Timer::class, 'timer_id', 'id');
    }    

	


}
