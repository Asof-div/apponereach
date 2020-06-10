<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $guard = 'admin';
    //

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lastname', 'firstname', 'email', 'password', 'job_title', 'last_login_at', 'avatar',
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

    public function name(){

        return ucwords($this->lastname . " " . $this->firstname);
    }

    public function getAvatarAttribute() {
        $image = public_path('storage/'.$this->image);

        if (!file_exists($image) || !$this->image) {
            // Return default
            return 'assets/img/user-13.jpg';
        }

        return 'storage/'.$this->image;
    }

    public function tickets() {

        return $this->morphMany(Ticket::class, 'creator')->latest();
    }

    public function addRole($role) {
        if (is_int($role)) {
            return $this->roles()->save(
                AdminRole::find($role)
            );
        }elseif(is_object($role)){
            return $this->roles()->save($role);
        }
    }

    
}
