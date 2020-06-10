<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\BelongsToTenant;

class Profile extends Model
{
    //use BelongsToTenant;

    // public static function boot() {
        
    //     parent::boot();

    //     static::creating(function($profile) {
            
    //         $profile->tenant_id = $static->user->tenant_id;
    //     });


    // }

    protected $guarded = ['id'];

    public function user(){

    	return $this->belongsTo(User::class);
    }

    
}
