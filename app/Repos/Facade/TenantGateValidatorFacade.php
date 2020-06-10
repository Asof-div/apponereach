<?php

namespace App\Repos\Facade;

use Illuminate\Support\Facades\Facade;

use App\Repos\TenantGateValidator;

class TenantGateValidatorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return TenantGateValidator::class;
    }
}
