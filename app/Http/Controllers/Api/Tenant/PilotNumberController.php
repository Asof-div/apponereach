<?php

namespace App\Http\Controllers\Api\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Response\ApiResponse;
use App\Services\PilotNumberPurchaseService;
use App\Services\Tenant\Order\CartService;
use App\Models\Operator\PilotNumber;
use Auth;

class PilotNumberController extends Controller
{


    /**
     * @OA\Get(
     *      path="/api/app/pilot/search",
     *      operationId="getPilotNumber",
     *      tags={"Pilot Number"},
     *      summary="Search pilot number",
     *      description="Returns list of available pilot numbers",
     *      @OA\Parameter(
     *          name="number",
     *          description="phone number",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
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
    public function search(Request $request, PilotNumberPurchaseService $pilotNumberService){
        
        $tenant = $request->user()->tenant;

        if($tenant){

           $resolve = $pilotNumberService->search($tenant, $request->all()); 
        
           return response()->json($resolve);
        }

        return response()->json(['numbers' => [] ]);
    }



    /**
     * @OA\Get(
     *      path="/api/app/pilot/reserve",
     *      operationId="reservePilotNumberById",
     *      tags={"Pilot Number"},
     *      summary="Add pilot number to cart ",
     *      description="Returns pilot number for few hours until cart expires",
     *      @OA\Parameter(
     *          name="number_id",
     *          description="pilot number id",
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
    public function addToCart(Request $request, PilotNumberPurchaseService $pilotNumberService, CartService $cartService){
        
        $tenant = $request->user()->tenant;

        if($tenant){

            $subscription = $tenant->subscription;
            $order = $subscription->generateCart();

            $resolve = $pilotNumberService->addToCart($tenant, $request->all()); 
            $order = $cartService->addNumber($order, $resolve['numbers'], $request);
            $resolve['order'] = $order;
           return response()->json($resolve);
        }

        $response = (new ApiResponse)->error(['customer' => ['Customer not found'] ]);
        return response()->json($response, 422);
       
    }


    /**
     * @OA\Get(
     *      path="/api/app/pilot/remove",
     *      operationId="removePilotNumberById",
     *      tags={"Pilot Number"},
     *      summary="Remove pilot number from cart",
     *      description="Return remaining list of numbers in the cart",
     *      @OA\Parameter(
     *          name="number_id",
     *          description="Pilot Number id",
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
    public function removeFromCart(Request $request, PilotNumberPurchaseService $pilotNumberService, CartService $cartService){

        $tenant = $request->user()->tenant;

        if($tenant){
            
            $subscription = $tenant->subscription;
            $order = $subscription->generateCart();

            $resolve = $pilotNumberService->removeFromCart($tenant, $request->all()); 
            $order = $cartService->removeNumber($order, $resolve['numbers'], $request);
            $resolve['order'] = $order;
        
           return response()->json($resolve);
        }

       $response = (new ApiResponse)->error(['customer' => ['Customer not found'] ]);
        return response()->json($response, 422);
    }



    /**
     * @OA\Get(
     *      path="/api/app/pilot/reserved",
     *      operationId="getReservedNumbers",
     *      tags={"Pilot Number"},
     *      summary="Get Number is the cart",
     *      description="Returns list of pilot numbers in the cart.",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *     )
     *
     */
    public function getReserved(Request $request){

        $tenant = $request->user()->tenant;

        $pilot_numbers = PilotNumber::where('tenant_id', $tenant->id)->where('purchased', 0)->where('available',0)->where('status', 'cart')->orderBy('release_time', 'desc')->with('line_type')->get();  
        $pilot_numbers->map(function ($number){
                $number['amount'] = $number->line_type->price;
                $number['type'] = $number->line_type->label;
                $number['currency'] = 'NGN';
                return $number;
            });

       return response()->json(['numbers' => $pilot_numbers]);

    }

}
