<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\NonGlobalTenantScopeTrait;

class OrderItem extends Model
{
	use NonGlobalTenantScopeTrait;

    protected $guarded = ['id'];

    
    public function tenant(){

        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function order(){

        return $this->belongsTo(Order::class, 'order_id');
    }


}
