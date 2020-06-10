<?php

namespace App\Http\Controllers\App\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant;

class TenantController extends Controller
{


    public function setDomain($domain){

        $tenant = Tenant::where('domain', $domain)->get()->first();
        if(!$tenant){
            abort(404);
        }
        $this->tenant = $tenant;
    }

    /**
     * Set the tenant to scope by.
     *
     * @param string|Model $tenant
     *
     */
    public function set(Model $tenant)
    {
        if ($tenant instanceof Model) {
            $this->tenant = $tenant;
        } else {
            throw new \Exception("Tenant not valid");
        }
    }

    /**
     * Get the tenant.
     *
     * @param string|Model $tenant
     *
     * @return Model|tenant
     */
    public function get()
    {
        if ($this->tenant instanceof Model) {
            return $this->tenant;
        } else {
            abort(404);
        }
    }

    /**
     * Get the primary key of the tenant.
     *
     * @param string|Model $tenant
     *
     * @return integer
     */
    public function getTenantKey()
    {
        if ($this->tenant instanceof Model) {
            return $this->tenant->getKey();
        } else {
            abort(404);
        }
    }

}
