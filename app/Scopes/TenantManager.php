<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Exceptions\TenantException;
use Auth;

class TenantManager
{
    protected $tenant;

    public function __constuct()
    {
        
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
            throw new TenantException("Tenant not valid");
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

        if (Auth::check()) {
            return Auth::user()->tenant;
        } else {
            // throw new TenantException("Tenant not found");
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
        if (Auth::check()) {
            return Auth::user()->tenant_id;
            // return $this->tenant->getKey();
        } else {
            throw new TenantException("Tenant not found");
        }
    }

    /**
     * Applies applicable tenant scopes to a model using anonymous global scope.
     *
     * @param Model|BelongsToTenants $model
     */
    public function applyTenantScope(Model $model)
    {


        $model::addGlobalScope(new TenantScope);
    }

    public function tm(){

        return $this;
    }

}