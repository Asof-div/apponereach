<?php

namespace App\Repositories;

use App\Models\User;

class CreditRepository
{
	public function creditUser(User $user, float $amount)
    {
        $user_credits = $user->credits + $amount;

        return $user->update(['credits' => $user_credits]);
    }

    public function debitUser(User $user, float $amount)
    {
        $tenant = $user->tenant;

    	$tenant_credits = $tenant->credits - $amount;
        $user_credit = $user->used_credit + $amount;
        if ($tenant_credits < 0) {
            $tenant_credits = 0;
        }

        $tenant->update(['credits' => $tenant_credits]);
        return $user->update(['used_credit' => $user_credit]);
    }
}