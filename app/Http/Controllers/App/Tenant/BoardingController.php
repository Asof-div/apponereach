<?php

namespace App\Http\Controllers\App\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Billing;
use App\Models\Subscription;
use App\Models\Operator\PilotNumber;
use App\Events\UserWasRegistered;
use App\Models\PaymentTransaction as Payment;

use Illuminate\Support\MessageBag;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use App\Services\RegistrationService;
use App\Services\PilotNumberPurchaseService;

use Carbon\Carbon;

use Paystack;
use Validator;
use Auth;

class BoardingController extends Controller
{
   	/**
   	*	Landing Page For A Billing
   	*
   	*/

   	public function __construct(){
   		
        // $this->registration = new RegistrationService();
        // $this->pilotNumberService = new PilotNumberPurchaseService;
        $this->middleware(['auth:web']);

   	}


	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   	public function index($domain, Request $request){

        $tenant = TenantManager::get();
        $billings = Billing::company()->where('billing_type', 'registration')->get();

        $tenant = Tenant::where('domain', $domain)->first();

        if($tenant->active == 0 && $tenant->expired == 1 && strtolower( $tenant->billing_method ) == 'prepaid'  ){


          return redirect()->route('tenant.active-service', [$tenant->domain]);
        }
        TenantManager::set($tenant);

   	    return view('app.tenant.index');
   	}


    public function numbering($domain, Request $request){

        $tenant = TenantManager::get();
        $today = Carbon::today();
        $subscription = Subscription::company()->where('status', 'processing')->whereDate('start_time', '<=', $today)->whereDate('end_time', '>=', $today)->first();
        $pilot_numbers = PilotNumber::where('tenant_id', $tenant->id)->where('purchased', 0)->where('available',0)->orderBy('release_time', 'desc')->get();  
        $pilot_numbers->map(function ($number){
                $number['countdown'] = $number->countdown;
                $number['amount'] = strtolower($number->type) == 'regular' ? 20000 : 30000;
                return $number;
            });
        return view('app.tenant.boarding.numbering', compact('subscription', 'tenant', 'pilot_numbers'));
    }

    
    public function saveTransaction($domain, Request $request, MessageBag $message_bag){

        $validator = $this->validate($request, [
            'subscription_id' => 'required|exists:subscriptions,id',
            'tenant_id' => 'required|exists:tenants,id',
            'voucher' => 'required_if:discount,voucher|exists:vouchers,voucher_code',
        ]);

        $pilot_numbers = PilotNumber::where('tenant_id', $request->tenant_id)->where('purchased', 0)->where('available',0)->orderBy('release_time', 'desc')->get();  
        $subscription = Subscription::find($request->subscription_id);
        $tenant = Tenant::find($request->tenant_id);

        if($pilot_numbers->count() < 1 || !$subscription || !$tenant){
            $message_bag->add('pilot_number', 'No Pilot number have been added to cart.');
            return redirect()->route('tenant.registration.number_selection', [$domain])->withErrors($message_bag);
        }

        $pilot_price = 0;
        foreach ($pilot_numbers as $key => $pilot) {
            $pilot_price = $pilot_price + $pilot->line_type->price;
            
        }
        $ordered_items = [
            ['product' => 'DID LINE', 'type' => 'DID', 'items' => $pilot_numbers->count(), 'description' => implode(", ", $pilot_numbers->pluck('number')->toArray() ), 'period' => ' 1 Year', 'amount' => $pilot_price ,'charged' => $pilot_price, 'status' => 1 ],
        ];

        $payment = Payment::where('status', 'processing')->where('subscription_id', $subscription->id)->first();

        if(!$payment){
            $payment = new Payment;
        }
        $today = Carbon::today();

        $billing = Billing::create([
            'tenant_id' => $subscription->tenant_id,
            'email' => Auth::user()->email,
            'billing_method' => $subscription->billing_method,
            'subscription_id' => $subscription->id,
            'ordered_date' => Carbon::now(),
            'due_date' => Carbon::now()->addDay(3),
            'payment_method' => 'online',
            'amount' => $pilot_price,
            'charged' => $pilot_price,
            'currency' => '&#x20A6;',
            'billing_type' => 'Line',
            'status' => 'processing',
            'payment_status' => 'unpaid',
            'description' =>'DID LINES: '. implode(", ", $pilot_numbers->pluck('number')->toArray() ),
            'ordered_items' => json_encode($ordered_items),
    
            ]);

        $orders = $subscription->load(['billings' => function($query) {
            $query->where('status', 'processing')->where('payment_status', 'unpaid')->where('payment_transaction_id', null);
        }])->billings;

        foreach ($pilot_numbers as $key => $pilot) {
            $pilot->update(['status' => 'allocated', 'billing_id' => $billing->id]);
        }

        // $payment_no = $today->format('Y-m-d_').$tenant->code.sprintf("-%06X", $this->generate());

        if( count($orders) > 0 ){
            $amount = 0;
            foreach ($orders as $order) {
                $amount = $amount + (float) $order->charged;
            }

            $payment->tenant_id = $subscription->tenant_id;
            $payment->subscription_id = $subscription->id;
            $payment->transaction_method = $subscription->billing_method;
            $payment->transaction_type = 'invoice';
            $payment->transaction_no = Paystack::genTranxRef();
            $payment->currency = $subscription->currency;
            $payment->payment_method = 'invoice';
            $payment->payment_channel = 'invoice';
            $payment->status = 'processing';
            $payment->email = Auth::user()->email;
            $payment->generated_by = 'user';
            $payment->amount = (float) $amount ;
            $payment->description = " Onereach package signup and line fee."; 
            $payment->save();

            foreach ($orders as $order) {
                $order->update([
                    'payment_transaction_id' => $payment->id,
                    'payment_status' => 'processing',
                ]);
            }
            $tenant->update(['stage' => 'payment']);
            $subscription->update([
                'payment_status' => 'processing',
            ]);

        }

        return redirect()->route('tenant.registration.payment', [$tenant->domain]);
    }

    public function payment($domain, Request $request){

        $tenant = TenantManager::get();
        $today = Carbon::today();
        $subscription = Subscription::company()->where('status', 'processing')->whereDate('start_time', '<=', $today)->whereDate('end_time', '>=', $today)->first();
        $pilot_numbers = PilotNumber::where('tenant_id', $tenant->id)->where('purchased', 0)->where('available',0)->orderBy('release_time', 'desc')->get();  
        $payment = Payment::where('status', 'processing')->where('subscription_id', $subscription->id)->first();
        $payment->update(['email' => Auth::user()->email ]);
        
        return view('app.tenant.boarding.payment', compact('subscription', 'tenant', 'pilot_numbers', 'payment'));
    }

    public function generate(){
        $todays_payment = Payment::whereDate('created_at', Carbon::now()->format('Y-m-d').'%' )->get();
        $id = 1;
        if($todays_payment){
            $id = count($todays_payment) + 1;
        }

        return $id;
    }


}
