<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Scopes\BelongsToTenant;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use App\Traits\NonGlobalTenantScopeTrait;

class VirtualReceptionistMenu extends Model
{
	use BelongsToTenant, NonGlobalTenantScopeTrait;
	    
    protected $guarded = ['id'];

    public function module(){

    	return $this->morphTo();
    }

    public function getMenuIconAttribute(){

    	switch (strtolower($this->action)) {
            case 'extension':
					return 'fa fa-tty';
    			break;
    		
    		case 'number':
    				return 'fa fa-phone'; 
    			break;
            case 'group':
                    return 'fa fa-users';
                break;
            case 'receptionist':
                    return 'fa fa-th-large';
                break;
            case 'sub_menu':
                    return 'fa fa-th-large';
                break;
            case 'voicemail':
                    return 'fa fa-inbox';
                break;
            case 'playback':
                    return 'fa fa-volume-up';
                break;
            
    		default:
    				return '';
    			break;
    	}
    }

    public function getTypeAttribute(){

    	switch (strtolower($this->action)) {
    		case 'extension':
    				return 'USER EXTENSION';
    			break;
    		case 'receptionist':
    				return 'SUB MENU';
    			break;
            case 'number':
    				return 'CUG NUMBER';
    			break;
            case 'group':
                    return 'GROUP RING';
                break;
    		default:
    				return strtoupper($this->action);
    			break;
    	}

    }

    public function getDestinationAttribute(){

    	switch (strtolower($this->action)) {
    		case 'extension':
    				return $this->module->number;
    			break;
            case 'receptionist':
    				return $this->module->name ;
    			break;
            case 'reception':
    				return $this->module->name;
    			break;
            case 'number':
    				return $this->module->number;
    			break;
            case 'group':
                    return $this->module->name;
                break;
    		default:
    				return strtoupper($this->action);
    			break;
    	}
    }


}
