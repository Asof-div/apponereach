<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\OperatorResetPasswordNotification;

class Operator extends Authenticatable
{
    use Notifiable;

    protected $guard = 'operator';
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lastname', 'firstname', 'email', 'password', 'image', 'job_title', 'sadmin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getNameAttribute(){

        return ucwords($this->lastname . " " . $this->firstname);
    }

    public function getAvatarAttribute() {
        $image = public_path('storage/'.$this->image);

        if (!file_exists($image) || !$this->image) {
            // Return default
            return 'assets/img/avatar.jpg';
        }

        return 'storage/'.$this->image;
    }

    public function roles() {

        return $this->belongsToMany(OperatorRole::class, 'operator_role', 'operator_id', 'role_id');
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
    
    public function tickets() {

        return $this->morphMany(Ticket::class, 'creator')->latest();
    }

    public function addRole($role) {
        if (is_int($role)) {
            return $this->roles()->save(
                OperatorRole::find($role)
            );
        }elseif(is_object($role)){
            return $this->roles()->save($role);
        }
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new OperatorResetPasswordNotification($token));
    }


}
