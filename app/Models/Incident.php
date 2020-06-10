<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $guarded = ['id'];

    public function getInitialResponseTimeAttribute() {

		return $this->initial_response.' '.$this->initial_response_unit;
	}

    public function getExpectedResolutionTimeAttribute() {

		return $this->expected_resolution.' '.$this->expected_resolution_unit;
	}

    public function getEscalationIntervalTimeAttribute() {

		return $this->escalation_interval.' '.$this->escalation_interval_unit;
	}

	public function operator(){

		return $this->belongsTo(Operator::class, 'operator_id', 'id');
	}


	public function admin(){

		return $this->belongsTo(Admin::class, 'admin_id', 'id');
	}

	public function operators() {

		return $this->belongsToMany(Operator::class, 'operator_teams', 'incident_id', 'operator_id');
	}

	public function admins() {

		return $this->belongsToMany(Admin::class, 'admin_teams', 'incident_id', 'admin_id');
	}

	public function assignToOperator($user) {
		if (is_int($user)) {
			return $this->operators()->save(
				Operator::find($user)
			);
		}elseif(is_object($user)){
			return $this->operators()->save($user);
		}
	}

	public function revokeFromOperator($user) {
		if (is_int($user)) {
			return $this->operators()->detach($user);
		}elseif (is_object($user)) {
			return $this->operators()->detach($user->id);
		}

	}

	public function assignToAdmin($user) {
		if (is_int($user)) {
			return $this->admins()->save(
				Admin::find($user)
			);
		}elseif(is_object($user)){
			return $this->admins()->save($user);
		}
	}

	public function revokeFromAdmin($user) {
		if (is_int($user)) {
			return $this->admins()->detach($user);
		}elseif (is_object($user)) {
			return $this->admins()->detach($user->id);
		}

	}



}
