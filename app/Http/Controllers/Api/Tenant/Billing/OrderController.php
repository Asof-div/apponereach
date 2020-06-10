<?php

namespace App\Http\Controllers\Api\Tenant\Billing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Services\Response\ApiResponse;
use App\Http\Resources\UserResource;
use App\Services\Tenant\Order\CartService;

use Validator;
use Paystack;
use Carbon\Carbon;

class OrderController extends Controller
{
    

    /**
     * @OA\Get(
     *      path="/api/app/billing/order/cart",
     *      operationId="getCart",
     *      tags={"Billing Order"},
     *      summary="Get cart information",
     *      description="Return data in the cart",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *     )
     *
     */
    public function getCart(Request $request){

    	$user = $request->user();
    	$tenant = $user->tenant;
    	$subscription = $tenant->subscription;
    	$order = $subscription->cart;
        if($order){
            $order->load(['items', 'subscription', 'transaction', 'tenant']);
        }
    	
        $user = new UserResource($request->user());    
		$response = (new ApiResponse)->success(['order' => $order, 'user' => $user]);
        return response()->json($response, 200);	

    }


    public function addToCart(Request $request, CartService $cartService){

        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);

        if($validator->fails()){
            return response()->json( (new ApiResponse)->error($validator->errors()) , 422);
        }

        $user = $request->user();
        $tenant = $user->tenant;
        $subscription = $tenant->subscription;
        $order = $subscription->generateCart();

        if(strtolower($request->type) == 'topup'){
            $order = $cartService->addTopup($order, $request);
        }
  
        $response = (new ApiResponse)->success(['order' => $order]);
        return response()->json($response, 200);
    }


    public function removeFromCart(Request $request, CartService $cartService){


        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:order_items,id',
            'type' => 'required',
        ]);

        if($validator->fails()){
            return response()->json( (new ApiResponse)->error($validator->errors()) , 422);
        }

        $user = $request->user();
        $tenant = $user->tenant;
        $subscription = $tenant->subscription;
        $order = $subscription->cart;

        if(strtolower($request->type) == 'topup'){
            $order = $cartService->reduceTopup($order, $request);
        }

        $response = (new ApiResponse)->success(['order' => $order]);
        return response()->json($response, 200);
    }

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


    public function verifyCheckout(Request $request, CartService $cartService)
    {

        $validator = Validator::make($request->all(), [
            'trxref' => 'required|exists:transactions,transaction_no',
        ]);


        $trxref = $request->trxref;
        $user = $request->user();
        try{


            $paymentDetails = Paystack::getPaymentData();

            $tranx = json_decode(json_encode($paymentDetails) );

            $payment = Transaction::where('transaction_no', $trxref)->with(['order'])->first();
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

                
                $order = $payment->order;
                if($order->status == 'success' && $order->payment_status == false ){            
                    $payment->update(['status' => $tranx->data->status, 'payment_date' => carbon::now(), 'payment_method' => 'Secure Card Payment']);
                    

                    $cartService->verifiedCheckout($payment, $user);

                    $user = new UserResource($request->user()->load(['tenant']));
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



        }catch(\Exception $e) {

            $response = (new ApiResponse)->error($e->getMessage());
            return response()->json($response, 403);

        }
    }


}
