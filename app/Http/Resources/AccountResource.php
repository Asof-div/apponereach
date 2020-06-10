<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class AccountResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
        // return [
        //     'id' => $this->id,
        //     'tenant_id' => $this->tenant_id,
        //     'name' => $this->name,
        //     'email' => $this->email,
        //     'curreny' => $this->username,
        //     'active' => $this->active,
        //     'credits' => $this->credits,
        //     'used_credit' => $this->used_credit,
        //     'credit_limit' => $this->credit_limit,
        //     'photo' => $this->getPhoto(),
        //     'tenant' => $this->tenant,
        //     'profile' => $this->profile,
        //     'roles' => $this->roles->toArray(),
        //     'extensions' => $this->extensions->toArray(),
        //     'calls' => $this->calls->toArray(),
        //     'cart' => $this->tenant->subscription->cart,
        //     'contacts' => $this->contacts,
        //     'sip_server' => 'sip.onereach.ng',
        // ];
    }
}
