<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    
    protected $guarded = ['id'];


    public function packages(){

    	return $this->belongsToMany(Package::class, 'package_addon', 'addon_id', 'package_id');
    }
}
