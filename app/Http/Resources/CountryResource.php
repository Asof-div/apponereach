<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CountryResource extends Resource
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
            'id' => $this->id,
            'name' => $this->nicename,
            'phonecode' => '+' . $this->phonecode,
            'iso' => $this->iso,
            'call_rate' => $this->callRate ? $this->callRate->rate : ''
        ];
    }
}
