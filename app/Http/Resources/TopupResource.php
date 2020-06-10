<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

use App\Models\Currency;

class TopupResource extends Resource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $currency = Currency::where('is_base', 1)->first();
        $user = $request->user();

        return [
            'id' => $this->id,
            'amount' => $currency->symbol . $this->amount,
            'payment_type' => $this->payment_type,
            'topup_date' => $this->created_at->timezone($user->timezone->name)->format('Y-m-d H:i:s')
        ];
    }
}
