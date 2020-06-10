<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CallResource extends Resource {
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request) {
		return [
			'id'             => $this->id,
			'caller_num'     => $this->caller_num,
			'callee_num'     => $this->callee_num,
			'status'         => $this->status,
			'answer_time'    => $this->answer_time?$this->answer_time->format('Y-m-d H:i:s'):'',
			'airtime'        => $this->airtime,
			'duration'       => $this->getCallDurationInStringFormat($this->duration),
			'billsec'        => $this->getCallDurationInStringFormat($this->billsec),
			'call_rate'      => $this->call_rate,
			'dest_type'      => $this->dest_type,
			'direction'      => $this->direction,
			'end_time'       => $this->end_time?$this->end_time->format('Y-m-d H:i:s'):'',
			'source'         => $this->source,
			'tenant_id'      => $this->tenant_id,
			'uuid'           => $this->uuid,
			'start_time'     => $this->start_time?$this->start_time->format('Y-m-d H:i:s'):'',
			'recorded'       => $this->recorded && trim($this->recording()) != ''?true:false,
			'call_recording' => $this->recording(),
		];
	}
}
