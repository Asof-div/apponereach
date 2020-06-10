<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PackageResource extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            // 'data' => $this->collection,
            "id" => $this->id,
            'name' => $this->name,
            "price" => $this->price,
            "currency" => $this->currency,
            "description" => $this->description,
            "msisdn_limit" => $this->msisdn_limit,
            "user_limit" => $this->user_limit,
            "extension_limit" => $this->extension_limit,
            "addon_binary" => $this->addon_binary,
            "note" => $this->note,
            "annually" => $this->annually,
            'addons' => $this->addons->pluck('label')->toArray(),
            'discount' => number_format( $this->discountOff(), 2 ).'% off'
        ];
    }
}
