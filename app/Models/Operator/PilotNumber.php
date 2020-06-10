<?php

namespace App\Models\Operator;

use Illuminate\Database\Eloquent\Model;
use App\Models\Billing;
use App\Models\LineType;

class PilotNumber extends Model
{
    protected $guarded = ['id'];

    protected $dates = [
		'created_at', 'updated_at', 'release_time'
	];

	public function getCountdownAttribute(){

		return $this->release_time ? $this->release_time->timestamp :'' ;
	}

	public function billing(){

		return $this->belongsTo(Billing::class, 'billing_id');
	}
	
	public function line_type(){

		return $this->belongsTo(LineType::class, 'type_id');
	}

	public function getTypeAttribute(){

		return $this->line_type->label;
	}
}
