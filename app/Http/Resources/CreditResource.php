<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

use App\Models\Currency;

class CreditResource extends Resource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $currency = Currency::where('is_base', 1)->first();

        return [
            'id' => $this->id,
            'value' => $currency->symbol . $this->value,
        ];
    }
}
