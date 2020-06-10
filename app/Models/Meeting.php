<?php

namespace App\Models;

use App\Traits\NonGlobalTenantScopeTrait;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model {
	use NonGlobalTenantScopeTrait;

	protected $guarded = ['id'];

	public function participants() {

		return $this->hasMany(Participant::class , 'meeting_id');
	}

	public function conference() {

		return $this->belongsTo(Conference::class );
	}

	public function user() {

		return $this->belongsTo(User::class , 'created_by');
	}

	public function iCalDateFormat($date) {

		return $date->format('Ymd').'T'.$date->format('Hs').'00Z';
	}

	public function dialin() {
		$number = $this->conference?$this->conference->call_flow->dial_string:"";
		return 'Dial-in : '.$number;
	}

}
