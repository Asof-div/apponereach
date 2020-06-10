<?php

namespace App\Models;

use App\Traits\NonGlobalTenantScopeTrait;
use Illuminate\Database\Eloquent\Model;

class Call extends Model {
	use NonGlobalTenantScopeTrait;

	protected $guarded = ['id'];
	protected $table   = 'calls';
	protected $dates   = ['start_time', 'end_time', 'answer_time', 'created_at', 'updated_at'];

	public function matchNumber($field) {
		if ($field == 'caller') {

			return "<span class='label-success p-5'> {$this->caller_num} </span>";
		} elseif ($field == 'receiver' || $field == 'callee') {

			return "<span class='label-success p-5'> {$this->callee_num} </span>";
		}

		return "";
	}

	public function tenant() {

		return $this->belongsTo(Tenant::class );
	}

	public function status() {

		if (strtolower($this->status) == 'connected' || strtolower($this->status) == 'success') {

			return "<span class='label-success label'>&#10003; ".ucfirst($this->status)." </span>";
		} elseif (strtolower($this->status) == 'failed') {

			return "<span class='label-danger label'>&#10005; ".ucfirst($this->status)." </span>";

		} else {

			return "<span class='label-default label'>&#10005; ".ucfirst($this->status)." </span>";

		}

	}

	public function direction() {

		if (strtolower($this->direction) == 'inbound') {

			return "<span class='label label-default'><i class='fa fa-level-down'></i> IN</span>";
		} elseif (strtolower($this->direction) == 'outbound') {

			return "<span class='label label-default'><i class='fa fa-level-up'></i> OUT </span>";

		}

	}

	public function getCallDurationInStringFormat($duration) {
		if ($duration == 0 || $duration == '') {
			return '00:00:00';
		} else {
			$hours   = floor($duration/3600);
			$minutes = floor($duration/60);
			$seconds = ($duration%60);
			return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
		}
	}

	public function duration() {
		$duration = $this->duration;
		if ($duration == 0 || $duration == '') {
			return '00:00:00';
		} else {
			if ($duration >= 3600) {
				$hours = floor($duration/3600);
			} else {
				$hours = 0;
			}
			if ($duration > 60&$duration < 3600) {
				$minutes = floor($duration/60);

			} else {
				$minutes = 0;
			}

			if ($duration < 60) {
				$seconds = floor($duration/60);

			} else {
				$seconds = 0;
			}
			return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
		}
	}

	public function users() {

		return $this->belongsToMany(User::class , 'user_call', 'call_id', 'user_id')->withPivot(['call_direction']);
	}

	public function recording() {
		$image = public_path('storage/'.$this->call_recording);

		if (!file_exists($image) || !$this->call_recording) {
			// Return default
			return '';
		}

		return asset('storage/'.$this->call_recording);
	}

}
