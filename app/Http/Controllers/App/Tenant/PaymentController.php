<?php

namespace App\Http\Controllers\App\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\PaymentTransaction as Payment;

use Illuminate\Support\MessageBag;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use Carbon\Carbon;
use Paystack;
use Auth;
use App\Events\UserWasRegistered;

use App\Models\Operator\PilotNumber;
use App\Models\PilotLine;
use App\Helpers\PlanHelper;
use App\Services\Tenant\RegistrationService;

class PaymentController extends Controller
{
    /**
     * Redirect the User to Paystack Payment Page
     * @return Url
     */
    public function redirectToGateway($domain, Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email',

            ]);

        Paystack::getAuthorizationUrl()->redirectNow();
    	
        return redirect()->route('tenant.registration.payment/callback', [$domain])->with('flash_message', '');

    	// return 
    }


    /**
     * Obtain Paystack payment information
     * @return void
     */
    public function handleGatewayCallback($domain, Request $request, MessageBag $messageBag, RegistrationService $registration)
    {
        $this->validate($request, [
            'trxref' => 'required|exists:payment_transactions,transaction_no',
            ]);

        $trxref = $request->trxref;

        $paymentDetails = Paystack::getPaymentData();

        $tranx = json_decode(json_encode($paymentDetails) );

        $payment = Payment::where('transaction_no', $trxref )->first();
        if(!$payment){
            $messageBag->add('transaction_no', 'Invalid Transaction No.');

            return redirect()->route('tenant.registration.payment', [$domain])->withErrors($messageBag);
        }
        
        $tenant = Tenant::where('domain', $domain)->first();
        if(!$tranx->status){
            $messageBag->add('payment', $tranx->message);
            
            $payment->update(['status' => $tranx->message]);

            return redirect()->route('tenant.registration.payment', [$domain])->withErrors($messageBag);
        }

        if('success' === $tranx->data->status){

            $payment->update(['status' => $tranx->data->status, 'payment_method' => 'Secure Card Payment']);
            $now = Carbon::now();

            $subscription = $payment->subscription;
            if($subscription->status !== 'success'){            
                

                $registration->activateTenant($payment, Auth::user());


                return redirect()->route('tenant.account.profile', [$domain])->with('flash_message', 'Service successfully Activated');
       
            }else{

                return redirect()->route('tenant.account.profile', [$domain])->with('flash_message', 'Service Is Already Active');
            }

        }

    }

}
