<?php

namespace App\Models;

use App\Traits\NonGlobalTenantScopeTrait;
use Illuminate\Database\Eloquent\Model;

class Conference extends Model {
	use NonGlobalTenantScopeTrait;

	protected $guarded = ['id'];

	protected $dates = ['start_date', 'end_date', 'created_at', 'updated_at'];

	protected $casts = [
		'members' => 'array',
	];

	public function tenant() {

		return $this->belongsTo(Tenant::class );
	}

	public function call_flow() {

		return $this->belongsTo(CallFlow::class );
	}

	public function meetings() {

		return $this->hasMany(Meeting::class );
	}

}
