<?php

namespace App\Traits;


trait NonGlobalTenantScopeTrait
{

    public function scopeCompany($query, $id = null){
    	
        return $query->where('tenant_id',  $id);
    }

    public function scopeCustomer($query, $id){

        return $query->where('tenant_id',  $id);
    }

}