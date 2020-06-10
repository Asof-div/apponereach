<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;
use App\Traits\NonGlobalTenantScopeTrait;

class OpportunityLine extends Model
{
	use NonGlobalTenantScopeTrait;
	
    protected $guarded = ['id'];

    public function tenant(){

    	return $this->belongsTo(Tenant::class);
    }

    public function opportunity(){

    	return $this->belongsTo(Opportunity::class);
    }
    
    public function currency(){

    	return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function contact(){

    	return $this->belongsTo(Contact::class, 'contact_id');
    }

    public function items(){

        return $this->hasMany(OpportunityItem::class, 'opportunity_line_id');
    }




}
