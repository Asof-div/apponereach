<?php

namespace App\Http\Controllers\App\Tenant\Billings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction as Payment;

use Validator;
use Auth;
use DateTime;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function __construct(){

        $this->middleware(['auth:web', 'tenant']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($domain, Request $request)
    {

        $payments = (new Payment)->newQuery()->company();
    

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

        return view('app.tenant.billings.payment.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    public function show($domain, $id, Request $request)
    {
        $payment = Payment::company()->where('id', $id)->with(['tenant', 'subscription', 'billings'])->get()->first() ?? abort(404);
    
        return view('app.tenant.billings.payment.show', compact('payment'));
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
