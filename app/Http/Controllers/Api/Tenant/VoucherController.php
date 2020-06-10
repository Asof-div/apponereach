<?php

namespace App\Http\Controllers\Api\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Voucher;
use App\Models\Order;
use Validator;
use App\Services\Response\ApiResponse;

class VoucherController extends Controller
{
    
    /**
	 * @OA\Post(
	 *      path="/api/app/voucher/claim",
	 *      tags={"Voucher"},
	 *      summary="User Can Claim Voucher To Get Discount ",
	 *      description="Voucher User",
	 *	  	@OA\Parameter(
	 *          name="voucher_code",
	 *          description="Voucher code with 12 characters",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Response(
	 *          response=200,
	 *          description="discount successfully applied"
	 *       ),
	 *       @OA\Response(response=442, description="Unprocessable Entity"),
	 *     )
	 *
	 */
    public function claimVoucher(Request $request){

    	$validator = Validator::make($request->all(), [
    		'voucher_code' => 'required|exists:vouchers,voucher_code'
    	]);

    	if($validator->fails()){

    		$response = (new ApiResponse)->error($validator->errors());
            return response()->json($response, 422);
    	}

    	$voucher = Voucher::where('voucher_code', $request->voucher_code)->where('used', false)->first();
    	if(!$voucher){
    		$validator->errors()->add('voucher_code', 'voucher have been used');
    		$response = (new ApiResponse)->error($validator->errors());
            return response()->json($response, 422);	
    	}

    	$user = $request->user();
    	$tenant = $user->tenant;
    	$subscription = $tenant->subscription;
    	$order = $subscription->cart;
    	$order->applyVoucherDiscount($voucher);
    	$order->load(['items', 'transaction', 'subscription']);
    	
		$response = (new ApiResponse)->success(['order' => $order]);
        return response()->json($response, 200);	   	
    	
    }

    /**
	 * @OA\Get(
	 *      path="/api/app/voucher/info",
	 *      tags={"Voucher"},
	 *      summary="Get Voucher Information to see applicable discount. ",
	 *      description="Voucher User",
	 *	  	@OA\Parameter(
	 *          name="voucher_code",
	 *          description="Voucher code with 12 characters",
	 *          required=false,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Response(
	 *          response=200,
	 *          description="Summary infomation about the voucher and amount of discount attached."
	 *       ),
	 *       @OA\Response(response=442, description="Unprocessable Entity"),
	 *     )
	 *
	 */

    public function getVoucherData(Request $request){

    	$voucher = Voucher::where('voucher_code', $request->voucher_code)->where('used', false)->first();

    	if(!$voucher){
			
			$response = (new ApiResponse)->error(['voucher' => 'Invalid voucher']);
	        return response()->json($response, 422);	   	
    	}
	
		$response = (new ApiResponse)->success(['voucher' => $voucher]);
        return response()->json($response, 200);	   	


    }


}
