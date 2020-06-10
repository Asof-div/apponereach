<?php

namespace App\Repositories;

use App\Models\Topup;

class TopupRepository
{
	public function save($user_id, $amount, $payment_type)
    {
        return Topup::create([
            'user_id' => $user_id,
            'amount' => $amount,
            'payment_type' => $payment_type
        ]);
    }
}