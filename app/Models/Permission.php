<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $guarded = ['id'];

	public function users() {

		return $this->belongsToMany(User::class, 'permission_user', 'permission_id', 'user_id');
	}

	public function roles() {

		return $this->belongsToMany(Role::class, 'permission_role', 'permission_id', 'role_id');
	}


	public function assign($role) {
		if (is_string($role)) {
			return $this->roles()->save(
				Role::whereName($role)->firstOrFail()
			);
		}

		return $this->roles()->save($role);
	}

	public function revoke($role) {

		if (is_string($role)) {

			return $this->roles()->detach(
				Role::whereName($role)->firstOrFail()->id
			);
		}
		return $this->roles()->detach($role->id);
	}

	public function give($user) {
		if (is_string($user)) {
			return $this->users()->save(
				User::whereUsername($user)->firstOrFail()
			);
		}

		return $this->users()->save($user);
	}

	public function take($user) {
		if (is_string($user)) {
			return $this->users()->detach(
				User::whereUsername($user)->firstOrFail()->id
			);
		}
		return $this->users()->detach($user->id);
	}

}
