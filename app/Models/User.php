<?php

namespace App\Models;

use App\Traits\NonGlobalTenantScopeTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable {
	use NonGlobalTenantScopeTrait;
	use Notifiable;
	use HasApiTokens;

	public static function boot() {

		parent::boot();

		static ::created(function ($user) {

				$profile = new Profile;
				$profile->tenant_id = $user->tenant_id;

				$user->profile()->save($profile);

			});

		static ::deleting(function ($user) {
				$user->profile->delete();
			});

	}

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'lastname', 'firstname', 'email', 'password', 'tenant_id', 'middlename',
		'quota', 'primary_did', 'external', 'international', 'fallback_type', 'fallback_action',
		'credit_limit', 'last_login_at'
	];

	protected $guard_name = 'web';/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token', 'last_login_at',
	];

	protected $casts = [
		'active' => 'boolean', 'credits' => 'float', 'last_login_at' => 'date',
	];

	public function tenant() {

		return $this->belongsTo(Tenant::class );
	}

	public function roles() {

		return $this->belongsToMany(Role::class , 'user_role', 'user_id', 'role_id');
	}

	public function profile() {

		return $this->hasOne(Profile::class );
	}

	public function chat_rooms() {

		return $this->belongsToMany(ChatRoom::class , 'user_chat_room', 'user_id', 'chat_room_id');
	}

	public function getNameAttribute() {

		return ucwords($this->lastname." ".$this->firstname);
	}

	public function name() {

		return ucwords($this->lastname." ".$this->firstname);
	}

	public function getAvatarAttribute() {
		$image = public_path('storage/'.$this->profile->avatar);

		if (!file_exists($image) || !$this->profile->avatar) {
			// Return default
			return 'assets/img/avatar.jpg';
		}

		return 'storage/'.$this->profile->avatar;
	}

	public function getPhoto() {
		$image = public_path('storage/'.$this->profile->avatar);

		if (!file_exists($image) || !$this->profile->avatar) {
			// Return default
			return asset('assets/img/avatar.jpg');
		}

		return asset('storage/'.$this->profile->avatar);
	}

	public function hasPermission($name) {
		if (is_array($name)) {
			foreach ($name as $permName) {
				if ($this->hasPermission($permName)) {
					return true;
				}
			}
		} else {

			// Check roles for permission
			foreach ($this->roles as $role) {
				if ($role->hasPermission($name)) {
					return true;
				}
			}
		}

		return false;
	}

	public function hasRole($name) {
		if (is_array($name)) {
			foreach ($name as $roleName) {
				if ($this->hasRole($roleName)) {
					return true;
				}
			}
		} else {
			return $this->roles->contains('name', $name);
		}

		return false;
	}

	public function addRole($role) {
		if (is_int($role)) {
			return $this->roles()->save(
				Role::find($role)
			);
		} elseif (is_object($role)) {
			return $this->roles()->save($role);
		}
	}

	public function removeRole($role) {
		if (is_int($role)) {
			return $this->roles()->detach($role);
		} elseif (is_object($role)) {
			return $this->roles()->detach($role->id);
		}

	}

	public function hasPackage($package) {
		if (is_object($package) && $package instanceof Package) {

			return $this->tenant->package_id == $package->id && $this->tenant->status()?true:false;

		} elseif (is_int($package)) {

			return $this->tenant->package_id == $package && $this->tenant->status()?true:false;
		}

		return false;
	}

	public function tickets() {

		return $this->morphMany(Ticket::class , 'creator')->latest();
	}

	public function scopeAdministrator($query) {

		return $query->where('manager', 1);
	}

	public function hasChatRoom($name) {
		if (is_array($name)) {
			foreach ($name as $chatId) {
				if ($this->hasChatRoom($chatId)) {
					return true;
				}
			}
		} else {
			// \Log::log('info', is_array( $name) );
			return $this->chat_rooms->contains('id', $name);
		}

		return false;
	}

	public function todos() {

		return $this->hasMany(Todo::class , 'assignee_id');
	}

	public function extensions() {

		return $this->hasMany(Extension::class , 'user_id');
	}

	public function calls() {

		return $this->belongsToMany(Call::class , 'user_call', 'user_id', 'call_id')->withPivot(['call_direction']);
	}

	public function trackings() {

		return $this->hasMany(Tracker::class , 'user_id');
	}

}
