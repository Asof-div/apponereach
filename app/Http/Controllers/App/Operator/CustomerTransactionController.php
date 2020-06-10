<?php

namespace App\Http\Controllers\App\Operator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction as Payment;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\Billing;

use Validator;
use Auth;
use DateTime;
use Carbon\Carbon;

class CustomerTransactionController extends Controller
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
        $payments = (new Payment)->newQuery();
    
        if ( $request->has('name') && !is_null($request->name) ) {

            $payments = $payments->whereHas('tenant', function($query) use ($request) {
                $query->where('name', 'ilike', '%' . $request->name . '%');
            });
        }
        
        if ( $request->has('status') && strtolower($request->status) != 'all' ) {
            $status = strtolower($request->status);
            $payments = $payments->where('status', $status );
        }

        if($request->has('start_date') ){
            $start_date = $request->start_date ? (new \DateTime($request->start_date))->format('Y-m') : (new \DateTime)->modify('-6 month')->format('Y-m');

            $start = Carbon::parse($start_date );

            $end_date = $request->end_date ? (new \DateTime($request->end_date))->format('Y-m') : (new \DateTime)->format('Y-m');

            $end = Carbon::parse($end_date)->endOfMonth();

            $payments = $payments->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end); 
        }

        $payments = $payments->orderBy('created_at', 'desc')->paginate(50);

        return view('app.operator.payment.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function create(Request $request, Subscription $subscription)
    {
        $customer = $subscription->tenant;
        $orders = Billing::where('subscription_id', $subscription->id)->where('payment_transaction_id', null)->get();

        return view('app.operator.payment.create', compact('customer', 'subscriptiion', 'orders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $payment = Payment::where('id', $id)->with(['tenant', 'subscription', 'order', 'order.items'])->get()->first() ?? abort(404);
    
        return view('app.operator.payment.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function status(Request $request)
    {
        $this->validate($request, ['payment_id' => 'required|exists:payment_transactions,id', 'status' => 'required', 'paid_date' => 'required']);
        
        $payment = Payment::find($request->payment_id);
        if(!$payment){ 
            return redirect()->back()->withInput($request->input())->withErrors(['Transaction not found.']);
        }

        if(strtolower($payment->status) == strtolower($request->status)){ return redirect()->route('operator.customer.transaction.show', [$payment->id])->withErrors(['Status is the same as the previous.']); }
        $paid_date = Carbon::parse($request->paid_date );
        if('success' == strtolower($request->status)){
            foreach ($payment->billings->where('status', 'success') as $order) {
                $order->update(['payment_status' => 'paid', 'paid_date' => $paid_date]);
            }
            $subscription = $payment->subscription;

            $orders = $subscription->billings->where('status', 'success')->where('payment_status', '<>', 'paid');
            $payment->update(['status' => 'success']);
            if(count($orders) < 1){
                $subscription->update(['payment_status' => 'paid']);
            }
        }

        return redirect()->route('operator.customer.transaction.show', [$payment->id])->with('flash_message', 'Payment Status Successfully Updated');
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
