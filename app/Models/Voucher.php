<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $guarded = ['id'];
    

    public function order(){

    	return $this->belongsTo(Order::class, 'order_id');
    }


    public function tenant(){

    	return $this->belongsTo(Tenant::class, 'tenant_id');
    }

}
