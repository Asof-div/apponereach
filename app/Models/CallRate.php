<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallRate extends Model {

	protected $guarded = ['id'];

	public function country() {

		return $this->belongsTo(Country::class );
	}
}
