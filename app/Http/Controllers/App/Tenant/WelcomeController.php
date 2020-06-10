<?php

namespace App\Http\Controllers\App\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Operator\PilotNumber;
use App\Events\UserWasRegistered;

use App\Scopes\Facade\TenantManagerFacade as TenantManager;

use App\Services\Tenant\RegistrationService;
use App\Services\PilotNumberPurchaseService;

use Carbon\Carbon;

use Validator;


class WelcomeController extends Controller
{
   	/**
   	*	Landing Page For A Tenant
   	*
   	*/

   	public function __construct(){
   		
        $this->registration = new RegistrationService();
        $this->pilotNumberService = new PilotNumberPurchaseService;
   	}


	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   	public function index($domain, Request $request){

        $tenant = Tenant::where('domain', $domain)->first();

        if($tenant->active == 0 && $tenant->expired == 1 && strtolower( $tenant->billing_method ) == 'prepaid'  ){


          return redirect()->route('tenant.active-service', [$tenant->domain]);
        }
        TenantManager::set($tenant);

   	    return view('app.tenant.index');
   	}

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function notExist(){

        return view('app.errors.domain_not_found');
    }


    public function active($domain, Request $request){

        $tenant = Tenant::where('domain', $domain)->first();
        if(!$tenant){

            abort(404);

        }
        
        if($tenant->status == 1 ){
          return redirect()->route('login');
        }

        TenantManager::set($tenant);

        $pilot_number = PilotNumber::where('tenant_id', $tenant->id)->where('purchased', 0)->where('available',0)->orderBy('release_time', 'desc')->get()->first();  

        $subscription = $tenant->subscription;
        $package = $subscription->package;
        $billing = $subscription->billing;

        return view('app.tenant.complete_activation', compact('tenant', 'subscription', 'package', 'billing', 'pilot_number'));
    }


    public function completed($domain, Request $request){

      
        event(new UserWasRegistered($user, $this->password));

    }

        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $query
     */
    public function search($domain, Request $request){
        
        $tenant = Tenant::where('domain', $domain)->first();
        if($tenant){

           $resolve = $this->pilotNumberService->search($tenant, $request->all()); 
        
           return response()->json($resolve);
        }

        return response()->json(['status' => 'error', 'error' => ['Customer id not found'], 'numbers' => []], 422);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $query
     */
    public function addToCart($domain, Request $request){
        
        $tenant = Tenant::where('domain', $domain)->first();

        if($tenant){

           $resolve = $this->pilotNumberService->addToCart($tenant, $request->all()); 
        
           return response()->json($resolve);
        }

        return response()->json(['status' => 'error', 'error' => ['Customer id not found'], 'numbers' => []]);
       
    }

    public function removeFromCart($domain, Request $request){

        $tenant = TenantManager::get();
        if($tenant){

           $resolve = $this->pilotNumberService->removeFromCart($tenant, $request->all()); 
        
           return response()->json($resolve);
        }

        return response()->json(['status' => 'error', 'error' => ['Customer id not found'], 'numbers' => []]);
    }

    private function flushPilotNumbers($tenant){

        $pilot_numbers = PilotNumber::where('tenant_id', $tenant->id)->where('purchased', 0)->where('available',0)->orderBy('release_time', 'desc')->get();  

        foreach ($pilot_numbers as $pilot) {
            
            $pilot->update(['tenant_id' => null, 'available' => 1, 'release_time' => null, 'status' => 'unallocated']);

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function verify($domain, Request $request)
    {
        
        $validator = Validator::make($request->all(), [

            'reference' => 'required',
            'payment_id' => 'required|exists:payment_transactions,id',
            'subscription' => 'required|exists:subscriptions,id',
            
        ]);
        
        $tenant = TenantManager::get();

        if ($validator->passes()) {

            if($request->ajax()){

                $result = $this->registration->activeTenant($request->all());            

                return response()->json($result);

            }

        }

        return response()->json(['error'=>$validator->errors()->all()], 422);

    }

}
