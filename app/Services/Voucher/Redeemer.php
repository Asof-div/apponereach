<?php

namespace App\Services\Voucher;

use App\Services\Response\ApiResponse;
use App\Repositories\TopupRepository;
use App\Repositories\CreditRepository;
use App\Models\Voucher;
use App\Models\User;
use DB;

class Redeemer
{
	public function redeemVoucher(Voucher $voucher, User $user)
	{
	    if ($voucher->active === false) {
	    	return (new ApiResponse())->error('Voucher is not active');
        }

        if ($voucher->user_id) {
        	return (new ApiResponse())->error('Voucher has already been used');
        }

        DB::beginTransaction();

        $voucher_updated = $voucher->update(['user_id' => $user->id, 'used' => 1]);
        $user_credited = (new CreditRepository)->creditUser($user, $voucher->value);
        $topup_created = (new TopupRepository)->save($user->id, $voucher->value, 'Voucher');

        if (!$voucher_updated || !$user_credited || !$topup_created) {
            DB::rollBack();
            return (new ApiResponse())->error('Voucher cannot be redeemed at the moment. Please try again later');
        }

        DB::commit();

        return (new ApiResponse())->success(['message' => 'Voucher has been redeemed']);
	}
}