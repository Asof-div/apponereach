<?php

namespace App\Traits;


trait TenantScopeTrait
{

	protected static function boot()
	{

		parent::boot();

		static::addGlobalScope(new TenantScope);
	}
		
}