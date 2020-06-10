<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\NonGlobalTenantScopeTrait;

class Tracker extends Model
{
	use NonGlobalTenantScopeTrait;
	
    protected $guarded = ['id'];

    public function user(){

    	return $this->belongsTo(User::class);
    }

    public function tenant(){

    	return $this->belongsTo(Tenant::class);
    }

}
