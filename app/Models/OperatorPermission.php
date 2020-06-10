<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OperatorPermission extends Model
{
    protected $guarded = ['id'];
	protected $table = "operator_permissions";	    

	public function roles() {

		return $this->belongsToMany(OperatorRole::class, 'operator_permission_role', 'permission_id', 'role_id');
	}


	public function assign($role) {
		if (is_string($role)) {
			return $this->roles()->save(
				OperatorRole::whereName($role)->firstOrFail()
			);
		}

		return $this->roles()->save($role);
	}

	public function revoke($role) {

		if (is_string($role)) {

			return $this->roles()->detach(
				OperatorRole::whereName($role)->firstOrFail()->id
			);
		}
		return $this->roles()->detach($role->id);
	}


}
