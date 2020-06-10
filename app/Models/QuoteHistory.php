<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use App\Traits\NonGlobalTenantScopeTrait;

class QuoteHistory extends Model
{
    
	use NonGlobalTenantScopeTrait;
	
    protected $guarded = ['id'];

    
}
