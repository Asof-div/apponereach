<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\NonGlobalTenantScopeTrait;

class OperatorRole extends Model
{
	use  NonGlobalTenantScopeTrait;
	
	protected $table = "operator_roles";	    
    protected $guarded = ['id'];

	public function getRouteKeyName()
	{
	    return 'id';
	}

	public function users() {

		return $this->belongsToMany(Operator::class, 'operator_role', 'role_id', 'operator_id');
	}

	public function permissions() {

		return $this->belongsToMany(OperatorPermission::class, 'operator_permission_role', 'role_id', 'permission_id');
	}

	public function hasPermission($name) {
		if (is_array($name)) {
			foreach ($name as $permissionName) {
				$hasPermission = $this->hasPermission($permissionName);
				if ($hasPermission) {
					return true;
				}
			}

			return false;
		} else {
			return $this->permissions->contains('name', $name);
		}

		return false;
	}

	public function assignToUser($user) {
		if (is_int($user)) {
			return $this->users()->save(
				Operator::find($user)
			);
		}elseif(is_object($user)){
			return $this->users()->save($user);
		}
	}

	public function revokeFromUser($user) {
		if (is_int($user)) {
			return $this->users()->detach($user);
		}elseif (is_object($user)) {
			return $this->users()->detach($user->id);
		}

	}

	public function addPermission($permission) {
		if (is_int($permission)) {
			return $this->permissions()->save(OperatorPermission::find($permission));
		}elseif (is_object($permission)) {
			return $this->permissions()->save($permission);
		}

	}

	public function removePermission($permission) {
		if (is_int($permission)) {
			return $this->permissions()->detach($permission);
		}elseif (is_object($permission)) {
			return $this->permissions()->detach($permission->id);
		}
	}

	public function editable(){

		if( $this->system == true){

			return false;
		}

		return true;
	}

}
