<?php

namespace App\Http\Controllers\Api\Tenant\Media;

use App\Http\Controllers\Controller;

use App\Models\AutoAttendant;
use App\Repos\AutoAttendantRepo;

use App\Services\Response\ApiResponse;

use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use Validator;

class AutoAttendantController extends Controller {

	public function __construct() {

		$this->middleware(['auth:api']);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index($number, Request $request) {

		$attendants = AutoAttendant::where('number', $number)->get();

		return response()->json(['auto_attendants' => $attendants]);

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store($pilot, Request $request, AutoAttendantRepo $autoAttendantRepo) {

		$validator = Validator::make($request->all(), [

				'name' => ['required', Rule::unique('auto_attendants', 'title')->where(function ($query) {
							return $query->where('tenant_id', request()->user()->tenant_id);
						}), 'min:3', 'max:150'],
				'number'             => 'required|exists:pilot_lines,number',
				'line_id'            => 'required|exists:pilot_lines,id',
				'period'             => 'required',
				'start_time'         => 'required',
				'end_time'           => 'required',
				'greeting_type'      => 'required',
				'sound_id'           => 'required_if:greeting_type,sound',
				'tts_msg'            => 'required_if:greeting_type,tts',
				'destination_id'     => 'required',
				'destination_type'   => 'required',
				'destination_label'  => 'required',
				'destination_number' => 'required',
				// 'configured' => 'required',

			]);

		$user = $request->user();

		if ($validator->fails()) {

			$response = (new ApiResponse)->error($validator->errors()->all());
			return response()->json($response, 422);
		}

		$autoAttendantRepo->store($user, $request->all());

		return response()->json(['success' => 'ok']);

	}

	public function show($number, $id) {
		$attendant = AutoAttendant::company(request()->user()->tenant_id)->where('id', $id)->first();

		if (!$attendant) {

			$response = (new ApiResponse)->error('Attendant does not exist');
			return response()->json($response, 403);
		}

		return response()->json(['attendant' => $attendant], 200);
	}

	public function reorder($pilot, Request $request, AutoAttendantRepo $autoAttendantRepo) {

		$user = $request->user();

		$autoAttendantRepo->reorder($user, $pilot, $request->all());

		return response()->json(['success' => 'ok']);

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function update($pilot, $id, Request $request, AutoAttendantRepo $autoAttendantRepo) {

		$validator = Validator::make($request->all(), [

				'name' => ['required', Rule::unique('auto_attendants', 'title')->where(function ($query) {
							return $query->where('tenant_id', request()->user()->tenant_id);
						})->ignore($id), 'min:3', 'max:150'],
				'number'             => 'required|exists:pilot_lines,number',
				'line_id'            => 'required|exists:pilot_lines,id',
				'period'             => 'required',
				'start_time'         => 'required',
				'end_time'           => 'required',
				'greeting_type'      => 'required',
				'sound_id'           => 'required_if:greeting_type,sound',
				'tts_msg'            => 'required_if:greeting_type,tts',
				'destination_id'     => 'required',
				'destination_type'   => 'required',
				'destination_label'  => 'required',
				'destination_number' => 'required',
				// 'configured' => 'required',

			]);

		$user = $request->user();
		if ($validator->fails()) {

			$response = (new ApiResponse)->error($validator->errors()->all());
			return response()->json($response, 422);
		}
		$attendant = AutoAttendant::company(request()->user()->tenant_id)->where('id', $id)->first();

		if (!$attendant) {

			$response = (new ApiResponse)->error('Attendant does not exist');
			return response()->json($response, 403);
		}

		$autoAttendantRepo->update($user, $attendant, $request->all());

		return response()->json(['success' => 'ok']);

	}

	public function delete($id, Request $request, AutoAttendantRepo $autoAttendantRepo) {

		$attendant = AutoAttendant::company(request()->user()->tenant_id)->where('id', $id)->first();

		if (!$attendant) {

			$response = (new ApiResponse)->error('Attendant does not exist');
			return response()->json($response, 403);
		}
		$number = $attendant->number;
		$autoAttendantRepo->delete($attendant);

		$attendants = AutoAttendant::where('number', $number)->get();

		return response()->json(['auto_attendants' => $attendants]);
	}

}
