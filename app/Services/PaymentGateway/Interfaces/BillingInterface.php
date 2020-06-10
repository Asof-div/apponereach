<?php 

namespace App\Services\PaymentGateway\Interfaces;
use App\Models\Billing;

interface BillingInterface {



	public function startBillingProcess(Array $data);

	public function verifyPayment(Array $data);

	public function resumeBilling(Billing $billing);


}