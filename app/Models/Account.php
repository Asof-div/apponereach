<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;

use App\Traits\NonGlobalTenantScopeTrait;


class Account extends Model
{
	use NonGlobalTenantScopeTrait;
	
    protected $guarded = ['id'];


    public function tenant(){

    	return $this->belongsTo(Tenant::class);
    }

    public function state(){

        return $this->belongsTo(State::class, 'state_id');
    }

    public function country(){

        return $this->belongsTo(Country::class, 'country_id');
    }

    public function industry(){

    	return $this->belongsTo(Industry::class, 'industry_id');
    }

    public function manager(){

    	return $this->belongsTo(User::class, 'account_manager');
    }

    public function creator(){

    	return $this->belongsTo(User::class, 'created_by');
    }

    public function currency(){

        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function payment_term(){

        return $this->belongsTo(PaymentTerm::class, 'payment_term_id');
    }

    public function source(){

        return $this->belongsTo(AccountSource::class, 'account_source_id');
    }

    public function category(){

        return $this->belongsTo(AccountCategory::class, 'account_category_id', 'id');
    }

    public function contacts(){

        return $this->hasMany(Contact::class, 'account_id', 'id')->orderBy('name', 'asc');
    }

    public function opportunities(){

        return $this->hasMany(Opportunity::class, 'account_id', 'id')->orderBy('created_at', 'asc');
    }

    public function quotes(){

        return $this->hasMany(Quote::class, 'account_id', 'id')->orderBy('created_at', 'asc');
    }

    public function invoices(){

        return $this->hasMany(Invoice::class, 'account_id', 'id')->orderBy('created_at', 'asc');
    }


}
