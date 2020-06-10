<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use App\Traits\NonGlobalTenantScopeTrait;


class Invoice extends Model
{
	use NonGlobalTenantScopeTrait;
	
    protected $guarded = ['id'];

    public function tenant(){

    	return $this->belongsTo(Tenant::class);
    }

    public function account(){

    	return $this->belongsTo(Account::class);
    }

    public function quote(){

    	return $this->belongsTo(Quote::class);
    }

    public function opportunity(){

    	return $this->belongsTo(Opportunity::class);
    }
    
    public function currency(){

    	return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function contacts(){

        return $this->belongsToMany(Contact::class, 'invoice_contacts', 'invoice_id', 'contact_id');
    }

    public function historys(){

        return $this->belongsToMany(Resource::class, 'invoice_histories', 'invoice_id', 'resource_id')->withPivot(['revision', 'update'])->withTimestamps()->orderBy('created_at', 'desc');
    }

    public function items(){

        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

	public function addContact($contact) {
		if (is_int($contact)) {
			return $this->contacts()->save(Contact::find($contact));
		}elseif (is_object($contact)) {
			return $this->contacts()->save($contact);
		}

	}

    public function hasContact($contact) {
        if (is_array($contact)) {
            foreach ($contact as $contactName) {
                if ($this->hasRole($contactName)) {
                    return true;
                }
            }
        } else {
            return $this->contacts->contains('id', $contact);
        }

        return false;
    }
    
    public function status(){
        if($this->status == 2){
            return "<label class='bg-primary p-5'> Sent </label>";
        }elseif ($this->status >= 3) {
            return "<label class='bg-success p-5'> Paid </label>";
        }else{
            return "<label class='bg-info p-5'> Draft </label>";
        }
    }

}
