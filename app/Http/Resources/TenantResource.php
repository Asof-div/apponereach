<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TenantResource extends Resource {
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request) {
		// return parent::toArray([]);
		return [
			'id'         => $this->id,
			'name'       => $this->name,
			'domain'     => $this->domain,
			'status'     => $this->status,
			'expiration' => $this->expiration,
			'credits'    => $this->credits,
			'logo'       => $this->logo(),
			'info'       => $this->info,
			'cart'       => $this->subscription->cart
		];
	}
}
