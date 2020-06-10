<?php

namespace App\Http\Controllers\App\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Operator;

use App\Models\Subscription;
use App\Models\Billing;
use App\Models\Industry;
use App\Models\PaymentTransaction;
use App\Models\Package;
use App\Models\Tenant;

use App\Services\Operator\CustomerRegistration;
use App\Services\Operator\ManageCUGNumber;

use Illuminate\Validation\Rule;

use Validator;
use Auth;
use DateTime;
use Carbon\Carbon;

class SubscriptionController extends Controller
{    

    public function __construct()
    {
        $this->middleware('auth:operator');

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $subscriptions = (new Subscription)->newQuery();
        

        if ( $request->has('name') && !is_null($request->name) ) {

            $subscriptions = $subscriptions->whereHas('tenant', function($query) use ($request) {
                $query->where('name', 'ilike', '%' . $request->name . '%');
            });
        }
        
        if ( $request->has('billing_method') && $request->billing_method != 'All' ) {
            $billing_method = strtolower($request->billing_method);
            $subscriptions = $subscriptions->where('billing_method', $billing_method );
        }

        if ( $request->has('payment_status') && $request->payment_status != 'All' ) {
            $payment_status = $request->payment_status;
            $subscriptions = $subscriptions->where('payment_status', $payment_status );
        }

        if ( $request->has('status') && $request->status != 'All' ) {
            $status = $request->status;
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

        return view('app.operator.subscription.index', compact('subscriptions') );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

 
        return view('app.operator.customer.create', compact() );
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
            
                $response = null;

                return response()->json($response );

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
    public function show(Request $request, Subscription $subscription)
    {
        $subscription->load(['orders', 'transactions', 'tenant', 'package']);

        return view('app.operator.subscription.show', compact('subscription'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $customer->id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $customer->id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
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
