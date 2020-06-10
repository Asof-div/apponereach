<?php

namespace App\Http\Controllers\App\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Transaction;
use App\Models\Tenant;
use App\Models\Operator\PilotNumber;
use App\Models\PostPaid;
use App\Models\Package;
use App\Models\Subscription;
use Validator;

use App\Services\Operator\CustomerOrderService;


class CustomerOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:operator');
        $this->customerOrder = new CustomerOrderService;

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $orders = (new Order)->newQuery();
        
        
        if ( $request->has('name') && !is_null($request->name) ) {

            $orders = $orders->whereHas('tenant', function($query) use ($request) {
                $query->where('name', 'ilike', '%' . $request->name . '%');
            });
        }
        

        if ( $request->has('payment_status') && $request->payment_status != 'All' ) {
            $payment_status = $request->payment_status;
            $orders = $orders->where('payment_status', $payment_status );
        }

        if ( $request->has('status') && $request->status != 'All' ) {
            $status = $request->status;
            $orders = $orders->where('status', $status );
        }

        if($request->has('start_date') ){
            $start_date = $request->start_date ? (new \DateTime($request->start_date))->format('Y-m') : (new \DateTime)->modify('-6 month')->format('Y-m');

            $start = Carbon::parse($start_date );

            $end_date = $request->end_date ? (new \DateTime($request->end_date))->format('Y-m') : (new \DateTime)->format('Y-m');

            $end = Carbon::parse($end_date)->endOfMonth();

            $orders = $orders->whereDate('ordered_date', '>=', $start)->whereDate('ordered_date', '<=', $end); 
        }
   
        $orders = $orders->orderBy('ordered_date', 'desc')->with(['tenant'])->paginate(50);

        return view('app.operator.order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Tenant $customer, Request $request, $type=null )
    {   
        $subscription = New Subscription;
        if($customer->subscription){
            $subscription = $customer->subscription;
            $price = $customer->subscription->package ? $customer->subscription->package->price : 0;
            $packages = Package::where('price', '>', $price)->get();
        }else{
            $packages = Package::where('price', '>', $customer->package->price ?? 0 )->get();
        }

        return view('app.operator.order.create', compact('customer', 'type', 'packages', 'subscription'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $customer_id)
    {
        $validator = Validator::make($request->all(), [
            'order_type' => 'required',
            'products' => 'required_if:order_type,msisdn',
            'product.*' => 'required',
            'qty.*' => 'required',
            'customer_id' => 'required|exists:tenants,id',
            'subscription_id' => 'required|exists:subscriptions,id',
            'plan' => 'required_if:order_type,upgrade|max:250',

        ]);

        if ($validator->passes()) {

            if($request->ajax()){
                
                if(strtolower($request->order_type) == 'msisdn'){

                    $resolve = $this->customerOrder->saveMSISDNOrder($request->all());

                    return response()->json($resolve);

                }elseif(strtolower($request->order_type) == 'upgrade'){

                    $resolve = $this->customerOrder->saveUpgradeOrder($request->all());

                    return response()->json($resolve);

                }
             

                return response()->json(['error'=>['Invalid Order'] ], 442);
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
    public function show($id, $order_id)
    {

        $active = true;
        $postpaid = null;
        $subscription = null;
        $order =  Order::where('id', $order_id)->where('tenant_id', $id)->with(['transaction', 'subscription', 'tenant'])->first();
        if(!$order){
            abort(404);
        }
        $customer = $order->tenant;

        return view('app.operator.order.show', compact('customer', 'order'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_type' => 'required',
            'order_id' => 'required|exists:billings,id',
            'customer_id' => 'required|exists:tenants,id',

        ]);

        if ($validator->passes()) {

            if($request->ajax()){
                
 
                $resolve = $this->customerOrder->confirm($request->all());

                return response()->json($resolve);

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
    public function cancel(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'order_type' => 'required',
            'order_id' => 'required|exists:billings,id',
            'customer_id' => 'required|exists:tenants,id',

        ]);

        if ($validator->passes()) {

            if($request->ajax()){
                
                $resolve = $this->customerOrder->cancel($request->all());

                return response()->json($resolve);

            }

        }

        return response()->json(['error'=>$validator->errors()->all()], 422);    
    }

}
