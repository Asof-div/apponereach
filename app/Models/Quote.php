<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use App\Traits\NonGlobalTenantScopeTrait;


class Quote extends Model
{
	use NonGlobalTenantScopeTrait;
	
    protected $guarded = ['id'];

    public function tenant(){

    	return $this->belongsTo(Tenant::class);
    }

    public function account(){

    	return $this->belongsTo(Account::class);
    }

    public function opportunity(){

    	return $this->belongsTo(Opportunity::class);
    }
    
    public function currency(){

    	return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function contacts(){

        return $this->belongsToMany(Contact::class, 'quote_contacts', 'quote_id', 'contact_id');
    }
    public function historys(){

        return $this->belongsToMany(Resource::class, 'quote_histories', 'quote_id', 'resource_id')->withPivot(['revision', 'update'])->withTimestamps()->orderBy('created_at', 'desc');
    }

    public function items(){

        return $this->hasMany(QuoteItem::class, 'quote_id');
    }

    public function getPdf()
    {
        $image = public_path('storage/'.$this->pdf_path);

        if (!file_exists($image) || !$this->pdf_path) {
            // Return default
            return null;
        }

        return asset('storage/'.$this->pdf_path);
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
            return "<label class='bg-success p-5'> Converted </label>";
        }else{
            return "<label class='bg-info p-5'> Draft </label>";
        }
    }

}
