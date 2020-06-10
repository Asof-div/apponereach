<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class UserResource extends Resource {
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	public function toArray($request) {
		// return parent::toArray([]);
		return [
			'id'           => $this->id,
			'tenant_id'    => $this->tenant_id,
			'company'      => $this->tenant->name,
			'domain'       => $this->tenant->domain,
			'firstname'    => $this->firstname,
			'middlename'   => $this->middlename,
			'lastname'     => $this->lastname,
			'email'        => $this->email,
			'username'     => $this->username,
			'active'       => $this->active,
			'credits'      => $this->credits,
			'used_credit'  => $this->used_credit,
			'credit_limit' => $this->credit_limit,
			'photo'        => $this->getPhoto(),
			'tenant'       => new TenantResource($this->tenant),
			'profile'      => $this->profile,
			'roles'        => $this->roles->toArray(),
			'extensions'   => $this->extensions->toArray(),
			'calls'        => $this->calls->toArray(),
			'cart'         => $this->tenant->subscription->cart,
			'sip_server'   => 'sip.onereach.ng',
		];
	}
}
