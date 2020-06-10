<?php 

namespace App\Services\PaymentGateway;

use App\Services\PaymentGateway\Interfaces\BillingInterface;
use App\Models\Billing;
use App\Models\PaymentTransaction;

use Carbon\Carbon;
use Auth;
use Yabacon\Paystack;

class PayStackBillingService implements BillingInterface {


	public function __construct(){
		$this->skey = 'sk_test_fc8eb69dd71f788a529e3a3afec0bf3e620b5046';
	}


	public function startBillingProcess(Array $data){
        
        $start = Carbon::now();
    	$end = $start->copy();    
        if(isset($data['duration'])){
        	$end = $start->copy();
		    $end->addMonths((int) $data['duration']);
        }else{

	        $end->addMonths(1);
        }


		$tenant =  Tenant::find($data['tenant_id']);
		$billing = Billing::create([
	        'tenant_id' => $tenant->id,
	        'billing_period' => 1,
	        'email' => $request->email,
	        'firstname' => $data['firstname'],
	        'lastname' => $data['lastname'],
	        'start_date' => $start,
	        'end_date' => $end,
	        'amount' => $data['amount'],
	        'currency' => "NGN",
	        'billing_type' => 'Online',
	        'status' => 'Processing',
	        'description' => $data['description'],
	    ]);

	    return $billing;

	}

	public function verifyPayment(Array $data){

 		$reference = isset($data['reference']) ? $data['reference'] : null;
        if(!$reference){

            return ['msg' => ['status' => 'error', 'error'=> ['Reference no. required.'] ], 'code' => 422] ;
        }

        $paystack = new Paystack($this->skey);

        $tranx = $paystack->transaction->verify(
            [
                'reference'=>$data['reference']
            ]
        );

        if(!$tranx->status){
            
            $payment = PaymentTransaction::find($data['transaction_id'] );
            $payment->update(['status' => $tranx->message]);

            return ['msg' => ['status' => 'error', 'error'=> [$tranx->message] ], 'code' => 422] ;
        }

        if('success' === $tranx->data->status){
            $payment = PaymentTransaction::find($data['transaction_id'] );
            $payment->update(['status' => $tranx->data->status, 'payment_method' => 'Secure Card Payment']);
            
            foreach ($payment->billings as $billing) {
                
                $billing->update(['status' => 'success', 'paid_date' => Carbon::now(),]);
                
            }
                
            return ['msg' => ['status' => 'success', 'success'=> 'Transaction Completed' ], 'code' => 200] ;
       
        }

        return ['msg' => ['status' => 'error', 'error'=> ['Incomplete Transaction'] ], 'code' => 422] ;
        

	}

	public function resumeBilling(Billing $billing){


	}


}