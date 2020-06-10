<?php

namespace App\Http\Controllers\App\Tenant\Billings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\App\Tenant\TenantController;
use App\Models\Package;
use App\Models\Addon;
use App\Models\Operator\PilotNumber;
use App\Services\TenantService;

class CartController extends TenantController
{
    public function __construct(){

        $this->middleware(['auth:web', 'tenant']);
        $this->tenantService = new TenantService;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($domain, Request $request)
    {
        $this->setDomain($domain);
        $tenant = $this->tenant;
        $cart = $tenant->cart;

        $packages = Package::where('name', '<>', 'Free')->get();
        $addons = Addon::get();

        $pilot_numbers = PilotNumber::where('available',1)->where('tenant_id', null)->where('purchased', 0)->where('type','Local')->inRandomOrder()->take(10)->get(); 


        return view('app.tenant.billings.cart.index', compact('packages', 'pilot_numbers', 'addons'));   
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
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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

    public function addItem($domain, Request $request){

        $this->setDomain($domain);
        $tenant = $this->tenant;
        $cart = $tenant->cart;



    }


}
