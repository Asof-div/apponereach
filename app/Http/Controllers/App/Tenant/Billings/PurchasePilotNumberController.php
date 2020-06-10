<?php

namespace App\Http\Controllers\App\Tenant\Billings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PilotLine;
use App\Models\Operator\PilotNumber;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use App\Models\Billing;
use App\Models\Number;
use App\Models\PaymentTransaction;
use App\Services\PilotDefaultDestination as PilotDefault;

use Validator;
use Carbon\Carbon;
use Auth;
use Yabacon\Paystack;


class PurchasePilotNumberController extends Controller
{
    
    public function __construct(){

    	$this->middleware('auth');
    	// $this->tenant = TenantManager::get();
    }
    /**
     * Display Pilot Line and purchase
     * 
     * @return \Illuminate\Http\Response
     */
    public function index($domain, Request $request){

        $tenant = TenantManager::get();
        $pilot_lines = PilotLine::get();
        $pilot_numbers = PilotNumber::where('tenant_id', $tenant->id)->where('purchased', 0)->where('available',0)->orderBy('release_time', 'desc')->get();  
        
        $billing = Billing::where('billing_type', 'Online')->where('status', 'Processing')->company()->get()->first();

        $numbers = Number::company()->where('status_msg', 'Success')->where('status', 1)->get();

        return view('app.tenant.billings.number_manager.index', compact('pilot_lines', 'pilot_numbers', 'billing', 'numbers'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $query
     */
	public function search($domain, Request $request){
        
        $number = $request->number ? $request->number : '';
        $type = $request->type ? $request->type : 'Local';

        if ($type) {
            $pilot_numbers = PilotNumber::where('number', 'like', '%' . $number . '%')->where('available',1)->where('tenant_id', null)->where('purchased', 0)->where('type',$type)->inRandomOrder()->take(20)->get(); 

            return response()->json($pilot_numbers);
        }

        return collect([]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $query
     */
	public function addToCart($domain, Request $request){
        
        $tenant = TenantManager::get();
        $pilot = PilotNumber::find($request->number_id);
        $time = Carbon::now();
        $pilot->update(['tenant_id' => $tenant->id, 'available' => 0, 'release_time' => $time->addMinutes('10')]);	

        $pilot_numbers = PilotNumber::where('tenant_id', $tenant->id)->where('purchased', 0)->orderBy('release_time', 'desc')->where('available',0)->get();  
        $pilot_numbers->map(function ($number){
        	$number['countdown'] = $number->countdown;
		    return $number;
        });
        return response()->json($pilot_numbers);
    }

    public function removeFromCart($domain, Request $request){

    	$tenant = TenantManager::get();
        $pilot = PilotNumber::find($request->number_id);
        $pilot->update(['tenant_id' => null, 'available' => 1, 'release_time' => null]);	

        $pilot_numbers = PilotNumber::where('tenant_id', $tenant->id)->where('purchased', 0)->orderBy('release_time', 'desc')->where('available',0)->get();  
        $pilot_numbers->map(function ($number){
        	$number['countdown'] = $number->countdown;
		    return $number;
        });
        return response()->json($pilot_numbers);
    }



        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function purchase(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'price' => 'required',
            'email' => 'required',

        ]);

        $tenant = TenantManager::get();
		$pilot_numbers = PilotNumber::where('tenant_id', $tenant->id)->where('purchased', 0)->orderBy('release_time', 'desc')->where('available',0)->get();  


        if ($validator->passes()) {

            if($request->ajax()){
                

                $start = Carbon::now();
                $billing = Billing::create([
                    'tenant_id' => $tenant->id,
                    'billing_period' => 0,
                    'email' => $request->email,
                    'firstname' => Auth::user()->firstname,
                    'lastname' => Auth::user()->lastname,
                    'start_date' => $start,
                    'end_date' => $start,
                    'amount' => (float) $pilot_numbers->count() * $request->price ,
                    'currency' => "NGN",
                    'billing_type' => 'Online',
                    'status' => 'Processing',
                    'description' => "Purchased Pilot Numubers \n". implode(', ', $pilot_numbers->pluck('number')->toArray()),
                    ]);

                foreach ($pilot_numbers as $pilot) {
                	$pilot->update(['billing_id' => $billing->id, 'purchased' => 1]);
                }
            }



            return response()->json(['success'=>'Transaction Created', 'billing' => $billing, 'pilot_numbers' => $pilot_numbers]);

        }


        return response()->json(['error'=>$validator->errors()->all()]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verify(Request $request)
    {
        
        $validator = Validator::make($request->all(), [

            'reference' => 'required',
            'billing' => 'required',
            
        ]);
        
        $pilotDefault = new PilotDefault();

        $tenant = TenantManager::get();

        if ($validator->passes()) {

            if($request->ajax()){

                $reference = $request->reference;
                if(!$reference){
                    die('No reference supplied');
                }

                $paystack = new Paystack('sk_test_fc8eb69dd71f788a529e3a3afec0bf3e620b5046');

                $tranx = $paystack->transaction->verify(
                    [
                        'reference'=>$request->reference
                    ]
                );

                if(!$tranx->status){
                    // there was an error from the API
                    //die('API returned error: ' . $tranx->message);
                    $billing = Billing::find($request->billing);
                    $billing->update(['status' => $tranx->message]);

                    return response()->json(['error'=> [$tranx->message] ]);
                }

                if('success' == $tranx->data->status){
                    $billing = Billing::find($request->billing);
                    $billing->update(['status' => 'Verified']);

                    PaymentTransaction::Create([
                            'tenant_id' => $tenant->id,
                            'transaction_no' => $request->reference,
                            'transaction_method' => 'Online',
                            'billing_id' => $billing->id,
                            'amount' => $billing->amount
                        ]);

                    $pilot_numbers = $billing->pilotNumbers;
                    foreach ($pilot_numbers as $pilot) {
	                	$pilot->update(['billing_id' => $billing->id, 'purchased' => 1, 'available' => 0]);

	                	PilotLine::create([
	                            'tenant_id' => $pilot->tenant_id,
                                'caller_id_name' => $tenant->name,
	                			'number' => $pilot->number,
	                		]);
	                }



                    return response()->json(['success'=>'Transaction Completed']);
                }

            }

        }

    }

}
