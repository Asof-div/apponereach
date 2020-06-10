<?php

namespace App\Scopes;

use App\Scopes\Facade\TenantManagerFacade as TenantMan;

trait BelongsToTenant
{
    /**
     * @var TenantManager
     */
    protected static $tenantManager;

    public static function bootBelongsToTenant()
    {
        // dd(app(TenantManager::class));
        // dd(TenantManager::get());
        // Get tenantmanager from IOC
        $tenantManager = TenantMan::tm();
        // dd(TenantMan::tm());
        
        $tenantManager->applyTenantScope(new static());

        // Add tenant scope automatically when creating model
        static::creating(function($model) use ($tenantManager) {
            $model->tenant_id = $tenantManager->getTenantKey();
        });

    }
}