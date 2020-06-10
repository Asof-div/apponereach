<?php

namespace App\Http\Controllers\Api\Tenant\Media;

use App\Http\Controllers\Controller;
use App\Models\CallFlow;
use App\Models\PilotLine;
use App\Repos\PilotLineRepo;
use App\Services\Response\ApiResponse;
use App\Traits\GetUserResourceTrait;

use Illuminate\Http\Request;
use Validator;

class PilotNumberController extends Controller {
	use GetUserResourceTrait;

	/**
	 * @OA\Get(
	 *      path="/api/app/media/pilot-number",
	 *      operationId="getPilotNumber",
	 *      tags={"Media Pilot Number"},
	 *      summary="Get Pilot Numbers",
	 *      description="Returns Pilot Number Attached To Current Tenant",
	 *      @OA\Response(
	 *          response=200,
	 *          description="successful operation"
	 *       ),
	 *       @OA\Response(response=401, description="Unauthorized"),
	 *       @OA\Response(response=422, description="Unprocessable Entity"),
	 *     )
	 *
	 */
	public function index(Request $request) {

		$tenant = $request->user()->tenant;

		$pilotNumbers = PilotLine::company($tenant->id)->get();

		return response()->json(['pilot_numbers' => $pilotNumbers, 'user' => $this->getUser(), 'tenant' => $tenant->load(['info'])], 200);
	}

	/**
	 * @OA\Get(
	 *      path="/api/app/media/pilot-number/{number}",
	 *      operationId="getPilotNumberByNumber",
	 *      tags={"Media Pilot Number"},
	 *      summary="Get Pilot Number",
	 *      description="Returns pilot number data",
	 *      @OA\Parameter(
	 *          name="number",
	 *          description="pilot number ",
	 *          required=true,
	 *          in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Response(
	 *          response=200,
	 *          description="completed"
	 *       ),
	 *       @OA\Response(response=401, description="Unauthorized"),
	 *       @OA\Response(response=422, description="Unprocessable Entity"),
	 *       @OA\Response(response=402, description="Can not Perform Action")
	 *     )
	 *
	 */
	public function show($number, Request $request) {

		$tenant = $request->user()->tenant;

		$pilot = PilotLine::company($tenant->id)->where('number', $number)->with(['welcome', 'module'])->first();

		// \Log::log('info', $pilot);

		return response()->json(['pilot_number' => $pilot], 200);
	}

	/**
	 * @OA\Put(
	 *      path="/api/app/media/pilot-number/announcement/{number}",
	 *      operationId="getPilotNumberByNumber",
	 *      tags={"Media Pilot Number"},
	 *      summary="Get Pilot Number",
	 *      description="Returns pilot number data",
	 *      @OA\Parameter(
	 *          name="number",
	 *          description="pilot number ",
	 *          required=true,
	 *          in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Response(
	 *          response=200,
	 *          description="completed"
	 *       ),
	 *       @OA\Response(response=401, description="Unauthorized"),
	 *       @OA\Response(response=422, description="Unprocessable Entity"),
	 *       @OA\Response(response=402, description="Can not Perform Action")
	 *     )
	 *
	 */

	public function defaultRoute($number, Request $request, PilotLineRepo $pilotLineRepo) {

		$validator = Validator::make($request->all(), [

				'greeting_type'      => 'required',
				'sound_id'           => 'required_if:greeting_type,sound',
				'tts_msg'            => 'required_if:greeting_type,tts',
				'destination_id'     => 'required',
				'destination_type'   => 'required',
				'destination_label'  => 'required',
				'destination_number' => 'required',

			]);

		if ($validator    ->fails()) {
			return response()->json((new ApiResponse())->error($validator->errors()), 422);
		}

		$tenant = $request->user()->tenant;

		$pilot = PilotLine::company($tenant->id)->where('number', $number)->first();

		try {

			$pilotLineRepo->setDefaultRoute($pilot, $request->all());

			$pilot = PilotLine::company($tenant->id)->where('number', $number)->with(['welcome', 'module'])->first();

			return response()->json(['pilot_number' => $pilot], 200);
		} catch (\Exception $e) {
			\Log::log('info', $e);
			return response()->json((new ApiResponse())->error('Unable to save default route'), 403);
		}
	}

	public function autoAttendants($number, Request $request) {

		$call_flows = CallFlow::company($request->user()->tenant_id)->where('dial_string', $number)->where('condition', 'custom')->orderBy('priority', 'asc')->get();
		return response()->json(['auto_attendant' => $call_flows], 200);
	}

}
