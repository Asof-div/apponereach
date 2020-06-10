<?php

namespace App\Http\Controllers\Api\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tracker;
use App\Traits\GetUserResourceTrait;
use Carbon\Carbon;
use App\Services\Response\ApiResponse;
use Illuminate\Validation\Rule;
use Validator;

class TrackerController extends Controller
{
	use GetUserResourceTrait;


    /**
     * @OA\Get(
     *      path="/api/app/trackers",
     *      operationId="getTracker",
     *      tags={"User Tracker"},
     *      summary="Get Tracker",
     *      description="Return Tracker.",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *     )
     *
     */
    public function index(Request $request){

    	$long = '3.4278371';
    	$lat = '6.433845';

  //   	$address=file_get_contents("http://maps.googleapis.com/maps/api/geocode/json?latlng=$lat,$long&sensor=false");
		// $json_data=json_decode($address);
		// $full_address=$json_data->results[0]->formatted_address;
		// dd($json_data)
    	$tenant = $request->user()->tenant;

    	$trackers = Tracker::company($tenant->id)->with(['user'])->get();

    	return response()->json(['trackers' => $trackers, 'user' => $this->getUser()], 200);
    }


    
	/**
	 * @OA\Post(
	 *      path="/api/app/trackers/check-in",
	 *      operationId="checkInLocation",
	 *      tags={"User Tracker"},
	 *      summary="Log user longitude and latitude",
	 *      description="Check-in user longitude and lat",
	 *	  	@OA\Parameter(
	 *          name="long",
	 *          description="Longitude",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 * 		@OA\Parameter(
	 *          name="lat",
	 *          description="Latitude",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Response(
	 *          response=201,
	 *          description="Action have been logged"
	 *       ),
	 *       @OA\Response(response=422, description="Unprocessable Entity"),
	 *     )
	 *
	 */
	public function checkIn(Request $request){

		$validator = Validator::make($request->all(), [
			'long' => 'required',
            'lat' => 'required',
		]);

		if($validator->fails()){
			$response = (new ApiResponse)->error($validator->errors());

			return response()->json($response, 422);	
		}
    	$tenant = $request->user()->tenant;

		Tracker::create([
			'user_id' => $request->user()->id,
			'tenant_id' => $tenant->id,
			'longitude' => $request->long,
			'latitude' => $request->lat,
			'log_time' => Carbon::now(),
		]);
		
		return response()->json('Ok', 200);	

	}

    /**
     * @OA\Get(
     *      path="/api/app/trackers/user",
     *      operationId="getUserTracker",
     *      tags={"User Tracker"},
     *      summary="Get User Tracker",
     *      description="Return Tracker.",
     *      @OA\Response(
     *          response=200,
     *          description="successful operation"
     *       ),
     *       @OA\Response(response=401, description="Unauthorized"),
     *       @OA\Response(response=422, description="Unprocessable Entity"),
     *     )
     *
     */
    public function user(Request $request, $id){
	
	  	$tenant = $request->user()->tenant;

    	$trackers = Tracker::company($tenant->id)->where('user_id', $id)->get();

    	return response()->json(['trackers' => $trackers, 'user' => $this->getUser()], 200);
    }


}
