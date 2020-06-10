<?php

namespace App\Http\Controllers\Api\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Subscription;
use App\Models\Operator\PilotNumber;
use App\Events\UserWasRegistered;
use App\Models\Transaction;

use App\Http\Resources\UserResource;

use Illuminate\Support\MessageBag;

use App\Services\Tenant\RegistrationService;
use App\Services\PilotNumberPurchaseService;

use Carbon\Carbon;
use App\Services\Response\ApiResponse;

use Paystack;
use Validator;
use Auth;
use DB;

class BoardingController extends Controller
{



    /**
     * @OA\Post(
     *      path="/api/app/registion/save-order",
     *      operationId="saveOrder",
     *      tags={"Boarding"},
     *      summary="Save customer order",
     *      description="Returns generated Order and Transaction",
     *      @OA\Parameter(
     *          name="subscription_id",
     *          description="Subscription id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *     )
     *
     */
    public function saveOrder( Request $request, MessageBag $message_bag){

        $validator = Validator::make($request->all(), [
            'subscription_id' => 'required|exists:subscriptions,id',
        ]);

        try{

            DB::beginTransaction();

            $user = $request->user();
            $tenant = $user->tenant;

            $pilot_numbers = PilotNumber::where('tenant_id', $tenant->id)->where('purchased', 0)->where('available',0)->orderBy('release_time', 'desc')->where('status', 'cart')->get();

            $subscription = Subscription::find($request->subscription_id);

            if($pilot_numbers->count() < 1 || !$subscription ){
                $validator->errors()->add('pilot_number', 'No Pilot number have been added to cart.');

                $response = (new ApiResponse)->error($validator->errors());
                return response()->json($response, 422);
            }

            $pilot_price = 0;
            
            foreach ($pilot_numbers->groupBy('type_id') as $key => $pilot) {
                $pilot_price = $pilot->count() * $pilot->first()->line_type->price;
            

                OrderItem::create([
                    'tenant_id' => $subscription->tenant_id,
                    'order_id' => $subscription->order_id,
                    'quantity' => $pilot->count(),
                    'amount' => $pilot->first()->line_type->price,
                    'charged' => $pilot_price,
                    'currency' => 'NGN',
                    'type' => $pilot->first()->line_type->name.'_line',
                    'name' => $pilot->first()->line_type->label.' Line',
                    'item' => 'DID Numbers',
                    'description' =>'DID LINES: '. implode(", ", $pilot_numbers->pluck('number')->toArray() ),
            
                    ]); 

            }

            $order = $subscription->cart;
            $order->load(['items']);
            $amount = 0;
            if($order){
                foreach ($order->items as $item) {

                    $amount = $amount + (float) $item->charged;

                }
                $order->amount = (float) $amount ;
                $order->charged = (float) $amount ;
                $order->update();
            }

            $payment = $order->generateTransaction();
            $tenant->update(['stage' => 'payment']);
            $subscription->update([
                'payment_status' => 'processing',
            ]);
            $order->load(['items']);

            DB::commit();
            
            $user = new UserResource($request->user());    
            $response = (new ApiResponse)->success(['subscription' => $subscription, 'order' => $order, 'transaction' => $payment, 'user' => $user ]);
            return response()->json($response, 200);


        }catch(\Exception $e){

            \Log::info($e );
            DB::rollback();
            $validator->errors()->add('order', $e->getMessage() );

            $response = (new ApiResponse)->error($validator->errors()->all());
            return response()->json($response, 422);

        }


    }




    /**
     * @OA\Post(
     *      path="/api/app/registration/confirm-order",
     *      operationId="confirmOrderById",
     *      tags={"Boarding"},
     *      summary="Confirm Order",
     *      description="Returns confirmed order deta",
     *      @OA\Parameter(
     *          name="order_id",
     *          description="Order id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *     )
     *
     */
    public function confirmOrder(Request $request){

        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
        ]);

        $user = $request->user();
        $tenant = $user->tenant;

        $order = Order::find($request->order_id);
        if(!$order || $validator->fails()){
            $validator->errors()->add('order_id', 'Order id is required');

            $response = (new ApiResponse)->error($validator->errors());
            return response()->json($response, 422);
        }

        $order->status = 'success';
        $order->update();
        $payment = $order->generateTransaction();


        $user = new UserResource($request->user());
        $response = (new ApiResponse)->success(['order' => $order, 'user' => $user ]);
        return response()->json($response, 200);
    }


    /**
     * @OA\Get(
     *      path="/api/app/registration/confirm-payment",
     *      operationId="confirmPayment",
     *      tags={"Boarding"},
     *      summary="Confirm payment status from payment gateway",
     *      description="Return payment status and activte account on successful payment.",
     *      @OA\Parameter(
     *          name="trxref",
     *          description="Transaction reference no",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *     )
     *
     */
    public function handleGatewayCallback(Request $request, RegistrationService $registration)
    {

        $validator = Validator::make($request->all(), [
            'trxref' => 'required|exists:transactions,transaction_no',
        ]);


        $trxref = $request->trxref;
        $user = $request->user();
        try{


            $paymentDetails = Paystack::getPaymentData();

            $tranx = json_decode(json_encode($paymentDetails) );

            $payment = Transaction::where('transaction_no', $trxref )->first();
            if(!$payment){
                $validator->errors()->add('transaction_no', 'Invalid Transaction No.');

                $response = (new ApiResponse)->error($validator->errors());
                return response()->json($response, 422);
            }
            
            $tenant = $request->user()->tenant;
            if(!$tranx->status){
                $validator->errors()->add('payment', $tranx->message);
                
                $payment->update(['status' => $tranx->message]);

                $response = (new ApiResponse)->error($validator->errors());
                return response()->json($response, 422);
            }

            if('success' === $tranx->data->status){

                $payment->update(['status' => $tranx->data->status, 'payment_method' => 'Secure Card Payment']);
                $now = Carbon::now();

                $subscription = $payment->subscription;
                if($subscription->status !== 'success'){            
                    

                    $registration->activateTenant($payment, $user);

                    $user = new UserResource($request->user());
                    $response = (new ApiResponse)->success(['user' => $user, 'message' => 'Service successfully Activated']);

                    return response()->json($response, 200);
           
                }else{

                    $user = new UserResource($request->user());
                    $response = (new ApiResponse)->success(['user' => $user, 'message' => 'Service Is Already Active']);
                    return response()->json($response, 200);
                }

            }else{

                $response = (new ApiResponse)->error($tranx->data->status);
                return response()->json($response, 403);
            }

            \Log::log('info', $paymentDetails );


        }catch(\Exception $e) {

            \Log::log('info', $e->getMessage());
            $response = (new ApiResponse)->error($e->getMessage());
            return response()->json($response, 403);
            // \Log::log('info', $e->message());

        }
    }

    


}
