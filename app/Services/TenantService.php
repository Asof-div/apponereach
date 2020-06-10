<?php
namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant;
use Carbon\Carbon;

class TenantService{

	public function __construct(){
		$this->tenant = null;
	}

	public function getTenant(){

		return $this->tenant;
	}

    public function getTenantKey()
    {
        if ($this->tenant instanceof Model) {
            return $this->tenant->getKey();
        } else {
            return null;
        }
    }

   	public function setTenantKey($id){

   		if($id){
   			$this->tenant = Tenant::find($id);
   		}

	}

	public function setTenant($tenant){

		if($tenant){
			$this->tenant = $tenant;	
		}

	} 


	public function activeStatus(){

		$status = false;
		$tenant = $this->tenant;
		$now = Carbon::now();
		$expiration = Carbon::createFromFormat('Y-m-d H:i:s', $tenant->expiration_date);
		if($tenant && $tenant->status && $tenant->expired ){

			$status = false;
			
		}



		return $status;
	}


}