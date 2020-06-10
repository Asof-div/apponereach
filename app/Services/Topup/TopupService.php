<?php

namespace App\Services\Topup;

use App\Repositories\TopupRepository;
use App\Services\Payment\PaymentManager;
use App\Models\User;

class TopupService
{
	public function handle()
    {
        $users = User::whereNotNull('settings')->where('settings->auto_topup->status', 'enabled')->where('settings->auto_topup->amount', '<=', 1)->get();

        foreach ($users as $user) {
        	$data = array();
        	$auto_topup_settings = $user->getSetting('auto_topup');
        	$payment_processor = $auto_topup_settings['processor'];
        	$data['amount'] = $auto_topup_settings['amount'];

        	$processor = PaymentManager::getPaymentProcessor($payment_processor);
        	$processor->processAutoPayment($user, $data);
        }
    }
}