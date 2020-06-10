<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\NonGlobalTenantScopeTrait;

class Ticket extends Model
{
	use NonGlobalTenantScopeTrait;

    protected $guarded = ['id'];

    public function incident(){

    	return $this->belongsTo(Incident::class, 'incident_id')->withDefault(['name' => 'Support', 'label' => 'Default Support']);
    }

    public function chat_room(){

    	return $this->belongsTo(ChatRoom::class, 'chat_room_id')->withDefault();
    }

    public function customer(){

    	return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    public function tenant(){

    	return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    public function operator(){

    	return $this->belongsTo(Operator::class, 'assigned_operator_id', 'id')->withDefault();
    }

    public function admin(){

    	return $this->belongsTo(Admin::class, 'assigned_admin_id', 'id')->withDefault();
    }

    public function getAccountAttribute(){

    	$account = '';
    	switch ($this->creator_type) {
    	 	case 'App\Models\Admin':
    	 		$account = "Admin";
    	 		break;
	 	 	case 'App\Models\Operator':
    	 		$account = "Operator";
    	 		break;
 		 	case 'App\Models\User':
    	 		$account = "User";
    	 		break;
       	 	
    	 	default:
    	 		$account = "";
    	 		break;
    	} 

    	return $account;
    }


	public function creator(){

		return $this->morphTo();
	}

    public function resources(){

        return $this->belongsToMany(Resource::class, 'ticket_resources', 'ticket_id', 'resource_id')->withPivot(['allow_tenant'])->withTimestamps()->orderBy('created_at', 'desc');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'ticket_id')->latest();
    }

    public function getInitialResponseTimeAttribute() {

		return $this->initial_response.' '.$this->initial_response_unit;
	}

    public function getExpectedResolutionTimeAttribute() {

		return $this->expected_resolution.' '.$this->expected_resolution_unit;
	}

    public function getEscalationIntervalTimeAttribute() {

		return $this->escalation_interval.' '.$this->escalation_interval_unit;
	}

	public function operators() {

		return $this->belongsToMany(Operator::class, 'ticket_operator', 'ticket_id', 'operator_id');
	}

	public function admins() {

		return $this->belongsToMany(Admin::class, 'ticket_admin', 'ticket_id', 'admin_id');
	}

	public function assignToOperator($user) {
		if (is_int($user)) {
			return $this->operators()->save(
				Operator::find($user)
			);
		}elseif(is_object($user)){
			return $this->operators()->save($user);
		}
	}

	public function revokeFromOperator($user) {
		if (is_int($user)) {
			return $this->operators()->detach($user);
		}elseif (is_object($user)) {
			return $this->operators()->detach($user->id);
		}

	}

	public function assignToAdmin($user) {
		if (is_int($user)) {
			return $this->admins()->save(
				Admin::find($user)
			);
		}elseif(is_object($user)){
			return $this->admins()->save($user);
		}
	}

	public function revokeFromAdmin($user) {
		if (is_int($user)) {
			return $this->admins()->detach($user);
		}elseif (is_object($user)) {
			return $this->admins()->detach($user->id);
		}

	}

    public function status(){
        if( strtolower($this->status) == "resolved"){

            return "<span class='label label-success'> Resolved </label> ";
        }elseif( strtolower($this->status) == "closed"){
            
            return "<span class='label label-success'> Closed </label> ";
        
        }elseif( strtolower($this->status) == "pending"){
            
            return "<span class='label label-primary'> Pending </label> ";

        }elseif( strtolower($this->status) == "open"){
            
            return "<span class='label label-info'> Open </label> ";

        }elseif( strtolower($this->status) == "unassigned"){
            
            return "<span class='label label-info'> Unassigned </label> ";
        }else{

            return "<span class='label label-danger'> Overdue </label> ";
        }
    }
    


}
