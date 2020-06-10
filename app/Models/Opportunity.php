<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;

use App\Traits\NonGlobalTenantScopeTrait;

class Opportunity extends Model
{
	use BelongsToTenant, NonGlobalTenantScopeTrait;
	
    protected $guarded = ['id'];
    protected $dates = ['close_date'];
    protected $casts = ['isRecurrent' => 'boolean', 'status' => 'boolean'];

    public function tenant(){

    	return $this->belongsTo(Tenant::class);
    }

    public function account(){

    	return $this->belongsTo(Account::class, 'account_id');
    }

    public function competitor(){

    	return $this->belongsTo(Account::class, 'competitor_id');
    }

    public function currency(){

    	return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function manager(){

    	return $this->belongsTo(User::class, 'manager_id');
    }

    public function lines(){

        return $this->hasMany(OpportunityLine::class, 'opportunity_id');
    }





}
