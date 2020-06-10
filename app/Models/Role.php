<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\NonGlobalTenantScopeTrait;

class Role extends Model
{
	use  NonGlobalTenantScopeTrait;
	    
    protected $guarded = ['id'];

	public function users() {

		return $this->belongsToMany(User::class, 'user_role', 'role_id', 'user_id');
	}

    public function tenant(){

        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

	public function permissions() {

		return $this->belongsToMany(Permission::class, 'permission_role', 'role_id', 'permission_id');
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
				User::find($user)
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
			return $this->permissions()->save(Permission::find($permission));
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

	public function match($domain){
		if( ($this->tenant&& $this->tenant->domain == $domain ) || $this->system == true){

			return true;
		}

		return false;		
	}

	public function editable(){

		if( ($this->tenant_id == '' || $this->tenant_id == null ) && $this->system == true){

			return false;
		}

		return true;
	}

}
