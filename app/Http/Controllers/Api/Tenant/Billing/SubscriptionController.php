<?php

namespace App\Http\Controllers\Api\Tenant\Billing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Subscription;
use App\Models\Billing;
use App\Services\Response\ApiResponse;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $tenant = $user->tenant;

        $subscriptions = Subscription::company($tenant->id)->with(['tenant', 'package', 'manager'])->latest()->get();
        
        return response()->json(['subscriptions' =>  $subscriptions ], 200);
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
    public function show(Request $request, $id)
    {
        $user =  $request->user();
        $tenant =  $request->user()->tenant;

        $subscription = Subscription::company($tenant->id)->where('id', $id)->with(['cart', 'cart.items', 'cart.transaction', 'package', 'orders', 'orders.items', 'orders.transaction'])->first();

        return response()->json(['subscription' =>  $subscription ], 200);
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
    public function delete($id)
    {
        //
    }


    /**
     * @OA\Get(
     *      path="/api/app/billing/subscription/current",
     *      tags={"Billing Subscription"},
     *      summary="Get Current Subscription ",
     *      description="Current Subscription Details",
     *      @OA\Response(
     *          response=200,
     *          description="Successful"
     *       ),
     *       @OA\Response(response=401, description="Unautorized"),
     *     )
     *
     */
    public function currentSubscription(Request $request){

        $user =  $request->user();
        $today = Carbon::today();
        $tenant = $user->tenant;

        $subscription = $tenant->subscription;

        return response()->json(['data' =>  $subscription ], 200);
    }


    /**
     * @OA\Get(
     *      path="/api/app/billing/subscription/current/procesing",
     *      tags={"Billing Subscription"},
     *      summary="Get Current Incomplete Subscription ",
     *      description="Processing Subscription ",
     *  
     *      @OA\Response(
     *          response=200,
     *          description="successfully "
     *       ),
     *       @OA\Response(response=401, description="Unautorized"),
     *     )
     *
     */
    public function currentProcessingSubscription(Request $request){

        $user =  $request->user();
        $tenant =  $request->user()->tenant;
        $today = Carbon::today();
        $subscription = Subscription::where('tenant_id', $tenant->id)->where('status', 'processing')->whereDate('start_time', '<=', $today)->whereDate('end_time', '>=', $today)->with(['cart', 'cart.items', 'cart.transaction', 'package'])->first();

        return response()->json(['data' =>  $subscription ], 200);
    }



}
