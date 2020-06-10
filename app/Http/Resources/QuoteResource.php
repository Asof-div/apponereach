<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class QuoteResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'account_id' => $this->account_id,
            'account' => $this->account,
            'currency_id' => $this->currency_id,
            'currency' => $this->currency,
            'deposit' => $this->deposit,
            'discount' => $this->discount,
            'discount_rate' => $this->discount_rate,
            'discount_type' => $this->discount_type,
            'expiration_date' => $this->expiration_date,
            'footer' => $this->footer,
            'grand_total' => $this->grand_total,
            'tenant' => $this->tenant,
            'opportunity_id' => $this->opportunity_id,
            'payment_terms' => $this->payment_terms,
            'po_no' => $this->po_no,
            'pdf_link' => $this->getPdf(),
            'private_note' => $this->private_note,
            'public_note' => $this->public_note,
            'quote_date' => $this->quote_date,
            'quote_no' => $this->quote_no,
            'status' => $this->status,
            'sub_total' => $this->sub_total,
            'terms' => $this->terms,
            'title' => $this->title,
            'user_id' => $this->user_id,
            'validated' => $this->validated,
            'vat' => $this->vat,
            'vat_type' => $this->vat_type,
            'vat_rate' => $this->vat_rate,
            'historys' => $this->historys->toArray(),
        ];
    }
}
