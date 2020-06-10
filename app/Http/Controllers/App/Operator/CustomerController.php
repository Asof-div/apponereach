<?php

namespace App\Http\Controllers\App\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Helpers\AccountHelper;
use App\Helpers\CountryHelper;

use App\Models\Operator\PilotNumber;
use App\Models\Operator;

use App\Models\Order;
use App\Models\Industry;
use App\Models\Transaction;
use App\Models\Package;
use App\Models\Tenant;
use App\Models\Number;
use App\Models\TenantInfo;
use App\Models\PostPaid;
use App\Models\User;
use App\Models\State;

use App\Services\Operator\CustomerRegistration;
use App\Services\Operator\CustomerUnboardingService;
use App\Services\Operator\ManageCUGNumber;

use Illuminate\Validation\Rule;
use Illuminate\Support\MessageBag;

use Validator;
use Auth;
use Carbon\Carbon;
use ReflectionClass;
class CustomerController extends Controller
{    

    public function __construct()
    {
        $this->middleware('auth:operator');
        $this->customerService = new CustomerRegistration;
        $this->cugManager = new ManageCUGNumber;
        $this->country = new CountryHelper();

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = Tenant::orderBy('name')->with(['info', 'package', 'subscription'])->paginate(50);
        
        return view('app.operator.customer.index', compact('customers') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        abort(404);
        
        $pilot_numbers = PilotNumber::where('available',1)->where('tenant_id', null)->where('purchased', 0)->where('type','Local')->inRandomOrder()->take(20)->get(); 

        $operators = Operator::get();
        $packages = Package::where('name', '<>', 'Free')->get();
        
        $industries = Industry::orderBy('name')->get();
        $countries = $this->country->getCountries();
        $states = State::orderBy('name')->get();

        return view('app.operator.customer.create', compact('packages', 'operators', 'industries', 'countries', 'states'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CustomerRegistration $customerReg)
    {
           
        $validator = Validator::make($request->all(), [
            
            'id_type' => 'required|max:250',
            'customer_category' => 'required|max:250',
            'customer_type' => 'required|max:250',
            'corporation_name' => 'required|max:250',
            'corporation_type' => 'required|max:250',
            'corporation_short_name' => 'required|unique:tenants,domain|max:250',
            'industry' => 'required|max:250',
            'register_date' => 'required|max:250',
            'language' => 'required|max:250',
            'issued_by' => 'required|exists:operators,id',
            'package' => 'required|exists:packages,id',
            'customer_sub_category' => 'required|max:250',
            'customer_grade' => 'required|max:250',
            'nationality' => 'required|max:250',
            'payment_method' => 'required|max:250',
            'email' => 'required|max:250|email',
            'firstname' => 'required|max:250',
            'lastname' => 'required|max:250',
            'middlename' => 'required|max:250',
            'title' => 'required|max:250',
            'address' => 'required|max:250',
            'state' => 'required|max:250',
            'home_no' => 'required_without_all:mobile_no,office_no,fax_no|max:250',
            'mobile_no' => 'required_without_all:home_no,office_no,fax_no|max:250',
            'office_no' => 'required_without_all:home_no,mobile_no,fax_no|max:250',
            'fax_no' => 'required_without_all:home_no,office_no,mobile_no|max:250',
        ]);

        if ($validator->passes()) {

            if($request->ajax()){
            
                $response = $customerReg->register($request->all());

                return response()->json($response['status'], $response['code'] );

            }

        }
     
        return response()->json(['error'=>$validator->errors()->all()], 422);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Tenant $customer)
    {
        $active = true; 
        $subscription = null;
        $billing = null;
        $customer->load(['pilot_lines', 'subscription', 'package', 'numbers', 'info', 'users']);
        $operators = Operator::get();
        $packages = Package::where('name', '<>', 'Free')->get();

        
        
        $transactions = Transaction::company($customer->id)->get();
        $user = $customer->users->where('manager', 1)->first();
        $users = User::company($customer->id)->get();
 
        $subscription = $customer->subscription;
        $orders = $subscription->orders;

        $msisdn_limit = (int)$customer->package->msisdn_limit;
        $extra_number = (int)$customer->info->extra_number;
        $pilot_lines = $customer->pilot_lines;
        $numbers = collect([]);
        $all_numbers = $customer->numbers;

        $all_numbers = $all_numbers->map(function ($number) {
            $number['empty'] = 0;
            return $number;
        });

        $empty_slots = collect();

        $remain = (int)$msisdn_limit - $all_numbers->where('slot',0)->count() ;

        for ($i=0; $i < $remain ; $i++) { 
            $all_numbers->push(new Number(['slot' => 0, 'empty' => 1]));
        }

        
        $remain = (int)$extra_number - $all_numbers->where('slot',1)->count() ;

        for ($i=0; $i < $remain ; $i++) { 
            $all_numbers->push(new Number(['slot' => 1, 'empty' => 1]));

        }
   
        $numbers = $all_numbers->sortBy('slot');

        return view('app.operator.customer.show', compact('customer', 'active', 'pilot_lines', 'numbers', 'operators', 'packages', 'pilot_number', 'user', 'subscription', 'orders', 'msisdn_limit', 'extra_number', 'users', 'transactions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $customer->id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Tenant $customer)
    {

        $operators = Operator::get();
        $packages = Package::where('name', '<>', 'Free')->get();
        
        $industries = Industry::orderBy('name')->get();
        $countries = $this->country->getCountries();
        $info = $customer->info;
        
        return view('app.operator.customer.edit', compact('customer', 'operators', 'packages', 'industries', 'countries', 'info'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $customer->id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerRegistration $customerReg)
    {
        $validator = Validator::make($request->all(), [
            
            'id_type' => 'required|max:250',
            'customer_category' => 'required|max:250',
            'customer_type' => 'required|max:250',
            'corporation_name' => 'required|max:250',
            'corporation_type' => 'required|max:250',
            'customer_id' => 'required|exists:tenants,id',
            'industry' => 'required|max:250',
            'language' => 'required|max:250',
            'customer_sub_category' => 'required|max:250',
            'customer_grade' => 'required|max:250',
            'nationality' => 'required|max:250',
            'payment_method' => 'required|max:250',
            'email' => 'required|max:250|email',
            'firstname' => 'required|max:250',
            'lastname' => 'required|max:250',
            'middlename' => 'required|max:250',
            'title' => 'required|max:250',
            'address' => 'required|max:250',
            'state' => 'required|max:250',
            'home_no' => 'required_without_all:mobile_no,office_no,fax_no|max:250',
            'mobile_no' => 'required_without_all:home_no,office_no,fax_no|max:250',
            'office_no' => 'required_without_all:home_no,mobile_no,fax_no|max:250',
            'fax_no' => 'required_without_all:home_no,office_no,mobile_no|max:250',
            
        ]);

        if ($validator->passes()) {

            if($request->ajax()){
            
                $response = $customerReg->update($request->all());

                return response()->json($response['status'], $response['code'] );

            }

        }
     
        return response()->json(['error'=>$validator->errors()->all()], 422);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, Tenant $customer, CustomerUnboardingService $unboardingService)
    {
        if ($customer) {

            if($request->ajax()){
                // if($customer->deleteable() == true){
                    
                    \Log::log('info', $customer);
                    $response = $unboardingService->delete($customer);

                    return response()->json($response['status'], $response['code'] );
                // }

                return response()->json(['error' => ['Customer can not be deleted. while subscription is still active.'] ], 422 );

            }

        }
     
        return response()->json(['error'=>['Customer not found. ']], 422);

    }

    public function activation(Request $request, $id){

        $active = true;
        $postpaid = null;
        $subscription = null;
        $customer = Tenant::find($id);
        $order =  Billing::where('billing_method', 'postpaid')->where('billing_type', 'registration')->where('status', 'processing')->where('tenant_id', $id)->first();
        if(!$order){
            return redirect()->route('operator.customer.index');
        }
        $pilot_number = PilotNumber::where('tenant_id', $customer->id)->where('purchased', 0)->where('available',0)->orderBy('release_time', 'desc')->get()->first();  
        if(strtolower($customer->billing_method) == 'postpaid'){
            
            $subscription = $billing->subscription;
            $postpaid = true;
            
        }
        if(strtolower($customer->billing_method) == 'postpaid' && strtolower($customer->billing_type) == 'registration'){
            $active = $postpaid->activated ? true : false ; 
            $subscription = $customer->subscription;

        }

        return view('app.operator.customer.postpaid.activation', compact('customer', 'order', 'pilot_number', 'active', 'subscription', 'postpaid'));
    }

    public function activate(Request $request){

        $validator = Validator::make($request->all(), [

            'billing' => 'required|exists:billings,id',
            'subscription' => 'required|exists:subscriptions,id',
            'pilot_line' => 'required|unique:pilot_lines,number',
            'pilot_number' => 'required|exists:pilot_numbers,id',
            'customer_id' => 'required|exists:tenants,id',

            ]);


        if ($validator->passes()) {

            if($request->ajax()){
            
                $response = $this->customerService->activate($request->all());

                return response()->json($response['status'], $response['code'] );

            }

        }
     
        return response()->json(['error'=>$validator->errors()->all()], 422);   
    }

    public function addNumber(Request $request, MessageBag $message_bag){

        

    }

    public function registration(Request $request, Tenant $customer){

        $order =  Billing::where('billing_method', 'postpaid')->where('billing_type', 'registration')->where('status', 'processing')->where('tenant_id', $customer->id)->get()->first();
        if(!$order){
            return redirect()->route('operator.customer.index');
        }
        $pilot_number = PilotNumber::where('tenant_id', $customer->id)->where('purchased', 0)->where('available',0)->orderBy('release_time', 'desc')->first();  
        if(strtolower($customer->billing_method) == 'postpaid'){
            
            $postpaid = true; 
            $subscription = $order->subscription;
            
        }
        if(strtolower($customer->billing_method) == 'postpaid' && strtolower($customer->billing_type) == 'registration'){
            $active = $postpaid->activated ? true : false ; 
            $subscription = $customer->subscription;

        }

        return view('app.operator.customer.registration', compact('customer', 'order', 'pilot_number', 'active', 'subscription', 'postpaid'));

    }

    public function suspend(Request $request, Tenant $customer){

        $validator = Validator::make($request->all(), [

            'customer_id' => 'required|exists:tenants,id',
        ]);

        if ($validator->passes()) {

            if($request->ajax()){
            
                $response = $this->customerService->suspend($request->all());

                return response()->json($response['status'], $response['code'] );

            }

        }
     
        return response()->json(['error'=>$validator->errors()->all()], 422);   

    }

    public function leave(Request $request, Tenant $customer){

    }

    public function renew(Request $request, Tenant $customer){
        $validator = Validator::make($request->all(), [

            'customer_id' => 'required|exists:tenants,id',
        ]);

        if ($validator->passes()) {

            if($request->ajax()){
            
                $response = $this->customerService->renew($request->all());

                return response()->json($response['status'], $response['code'] );

            }

        }
     
        return response()->json(['error'=>$validator->errors()->all()], 422); 
    }


}
