<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;
use App\Traits\NonGlobalTenantScopeTrait;

class CallFlow extends Model
{
	use NonGlobalTenantScopeTrait;
	
    protected $guarded = ['id'];


    public function getFlowIconAttribute(){

    	switch (strtolower($this->type)) {
    		case 'voicemail':
					return 'fa fa-inbox';
    			break;
    		
    		case 'short_code':
    				return 'fa fa-phone'; 
    			break;
            case 'dial_external':
                    return 'fa fa-phone'; 
                break;
            case 'dial_group':
                    return 'fa fa-users';
                break;
            case 'sub_menu':
                    return 'fa fa-th-large';
                break;
    		default:
    				return $this->type;
    			break;
    	}
    }

    public function getFlowTypeAttribute(){

    	switch (strtolower($this->type)) {
    		case 'dial_sip_profile':
    				return 'USER EXTENSION';
    			break;
    		case 'voicemail':
    				return 'VOICEMAIL';
    			break;
    		case 'short_code':
    				return 'CUG NUMBER';
    			break;
    		case 'dial_external':
    				return 'CUG NUMBER';
    			break;
            case 'dial_group':
                    return 'GROUP RING';
                break;
            case 'sub_menu':
                    return 'Virtual Receptionist';
                break;
    		default:
    				return strtoupper($this->type);
    			break;
    	}

    }
    
    
}
