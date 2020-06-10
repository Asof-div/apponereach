<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    
	protected $guarded = ['id'];


	public function addons(){

		return $this->belongsToMany(Addon::class, 'package_addon', 'package_id', 'addon_id')->orderBy('binary_index', 'asc');
	}

	public function discountOff(){
		return 100 - ( ($this->annually / ( (float) ($this->price) * 12) ) * 100);  
	}


}
