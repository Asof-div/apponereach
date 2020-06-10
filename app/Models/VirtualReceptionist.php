<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Scopes\BelongsToTenant;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use App\Traits\NonGlobalTenantScopeTrait;

class VirtualReceptionist extends Model
{	
	use BelongsToTenant, NonGlobalTenantScopeTrait;
	
	protected $guarded = ['id'];

	public function ivr_media(){

		return $this->belongsTo(PlayMedia::class, 'play_media_id');
	}

	public function tenant(){

		return $this->belongsTo(Tenant::class);
	}

	public function menus(){

        return $this->hasMany(VirtualReceptionistMenu::class, 'virtual_receptionist_id', 'id')->orderBy('key_press', 'asc');
    }


    public function call_flow(){

    	return $this->belongsTo(CallFlow::class);
    }
    

}
