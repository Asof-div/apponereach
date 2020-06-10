<?php

namespace App\Scopes\Facade;

use Illuminate\Support\Facades\Facade;

use App\Scopes\TenantManager;

class TenantManagerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return TenantManager::class;
    }
}
