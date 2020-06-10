<?php

namespace App\Http\Controllers\App\Tenant\Billings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use App\Models\Package;
use App\Models\Addon;
use App\Models\Subscription;
use App\Models\Billing;
use App\Models\PaymentTransaction;

use Validator;
use Carbon\Carbon;
use Auth;
use Yabacon\Paystack;
use App\Services\RenewSubscriptionService;

class SubscriptionController extends Controller
{
    public function __construct(){

        $this->middleware(['auth:web', 'tenant']);
        // $this->renewSubscriptionService = new RenewSubscriptionService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($domain, Request $request)
    {

        $subscriptions = (new Subscription)->newQuery()->company();
        
        if ( $request->has('payment_status') && strtolower($request->payment_status) != 'all' ) {
            $payment_status = strtolower($request->payment_status);
            $subscriptions = $subscriptions->where('payment_status', $payment_status );
        }

        if ( $request->has('status') && strtolower($request->status) != 'all' ) {
            $status = strtolower($request->status);
            $subscriptions = $subscriptions->where('status', $status );
        }

        if($request->has('start_date') ){
            $start_date = $request->start_date ? (new \DateTime($request->start_date))->format('Y-m') : (new \DateTime)->modify('-6 month')->format('Y-m');

            $start = Carbon::parse($start_date );

            $end_date = $request->end_date ? (new \DateTime($request->end_date))->format('Y-m') : (new \DateTime)->format('Y-m');

            $end = Carbon::parse($end_date)->endOfMonth();

            $subscriptions = $subscriptions->whereDate('start_time', '>=', $start)->whereDate('start_time', '<=', $end); 
        }

        $subscriptions = $subscriptions->orderBy('end_time', 'desc')->with(['tenant', 'package', 'manager'])->paginate(50);

        return view('app.tenant.billings.subscription.index', compact('subscriptions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($domain, $subscription_id)
    {
        $subscription = subscription::company()->where('id', $subscription_id)->with(['billings', 'payments', 'tenant', 'package'])->get()->first() ?? abort(404);

        return view('app.tenant.billings.subscription.show', compact('subscription'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'price' => 'required',
            'currency' => 'required',
            'package' => 'required',
            'duration' => 'required',
            'addons' => 'required',
            'email' => 'required',

        ]);

        

        return response()->json(['error'=>$validator->errors()->all()], 422);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        
        // $validator = Validator::make($request->all(), [

        //     'reference' => 'required',
        //     'billing' => 'required',
            
        // ]);


        // $tenant = TenantManager::get();

        // if ($validator->passes()) {

        //     if($request->ajax()){

        //         $reference = $request->reference;
        //         if(!$reference){
        //             die('No reference supplied');
        //         }

        //         $paystack = new Paystack('sk_test_fc8eb69dd71f788a529e3a3afec0bf3e620b5046');

        //         $tranx = $paystack->transaction->verify(
        //             [
        //                 'reference'=>$request->reference
        //             ]
        //         );

        //         if(!$tranx->status){
        //             // there was an error from the API
        //             //die('API returned error: ' . $tranx->message);
        //             $billing = Billing::find($request->billing);
        //             $billing->update(['status' => $tranx->message]);

        //             return response()->json(['error'=> [$tranx->message] ]);
        //         }

        //         if('success' == $tranx->data->status){
        //             $billing = Billing::find($request->billing);
        //             $billing->update(['status' => 'Verified']);

        //             PaymentTransaction::Create([
        //                     'tenant_id' => $tenant->id,
        //                     'transaction_no' => $request->reference,
        //                     'transaction_method' => 'Online',
        //                     'billing_id' => $billing->id,
        //                     'amount' => $billing->amount
        //                 ]);

        //             return response()->json(['success'=>'Transaction Completed']);
        //         }

        //     }

        // }

    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function expiring($domain, Request $request){

        $tenant = TenantManager::get();
        $msisdn = 0;
        $packages = Package::where('name', '<>', 'Free')->get();
        $subscription = Subscription::company()->where('status', 'pending')->get()->first();
        if($subscription){
            $msisdn = isset(json_decode($subscription->extra_msisdn, true)['items']) ? json_decode($subscription->extra_msisdn, true)['items'] : 0;
        }


        return view('app.tenant.billings.expiring', compact('subscription', 'packages', 'msisdn'));   
    }

    public function expiringStore($domain, Request $request, RenewSubscriptionService $renewSubscriptionService){
        $validator = Validator::make($request->all(), [

            'package' => 'required|exists:packages,id',
            'subscription_id' => 'required|exists:subscriptions,id',
            'tenant_id' => 'required|exists:tenants,id',
            // 'billing_method' => 'required',
            
        ]);

        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all()], 422);
   
        if ($validator->passes()) {

            if($request->ajax()){
                
                $resolve = $renewSubscriptionService->update($request->all());

                return response()->json($resolve['msg'], $resolve['code']);
            }

        }    
   
    }

    public function confirmRenewal($domain, Request $request, RenewSubscriptionService $renewSubscriptionService){
        $validator = Validator::make($request->all(), [

            'subscription_id' => 'required|exists:subscriptions,id',
            'tenant_id' => 'required|exists:tenants,id',
            
        ]);

        if ($validator->fails()) return response()->json(['error' => $validator->errors()->all()], 422);
   
        if ($validator->passes()) {

            if($request->ajax()){
                
                $resolve = $renewSubscriptionService->renew($request->all());

                return response()->json($resolve['msg'], $resolve['code']);
            }

        }    
   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
