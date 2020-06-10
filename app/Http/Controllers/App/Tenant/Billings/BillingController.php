<?php

namespace App\Http\Controllers\App\Tenant\Billings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Billing;
use App\Models\PaymentTransaction;
use App\Models\Number;
use App\Models\PilotLine;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use App\Models\Subscription;
use App\Models\Tenant;

class BillingController extends Controller
{

    public function __construct(){

        $this->middleware(['auth:web', 'tenant']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($domain)
    {
        $billings = Billing::company()->get();
        $tenant = TenantManager::get();
        $subscription = $tenant->subscription;
        $invoices = PaymentTransaction::company()->where('status', '<>', 'paid')->get();

        return view('app.tenant.billings.index', compact('billings', 'tenant', 'subscription', 'invoices'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orders($domain)
    {
        $billings = Billing::company()->paginate();
        $invoices = PaymentTransaction::company()->where('status', '<>', 'paid')->get();
        

        return view('app.tenant.billings.index', compact('billings', 'invoices'));
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
    public function show($domain, Request $request, $billing_id)
    {
        $order = Billing::company()->where('id', $billing_id)->get()->first() ?? abort(404);
    
        return view('app.tenant.billings.order.show', compact('order'));
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

    public function invoice($domain, Request $request){

        $payments = PaymentTransaction::company()->where('status', '<>', 'paid')->get();

        return view('app.tenant.billings.invoice', compact('payments'));
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
