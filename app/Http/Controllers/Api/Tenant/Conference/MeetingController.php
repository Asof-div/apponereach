<?php

namespace App\Http\Controllers\Api\Tenant\Conference;

use App\Http\Controllers\Controller;
use App\Models\Conference;
use App\Models\Meeting;

use App\Repos\MeetingRepo;
use App\Services\Response\ApiResponse;

use App\Traits\GetUserResourceTrait;
use Illuminate\Http\Request;

use Validator;

class MeetingController extends Controller {
	use GetUserResourceTrait;

	public function __construct() {

		$this->middleware(['auth']);

	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {

		$tenant = $request->user()->tenant;

		$meetings = Meeting::company($request->user()->tenant_id)->get();

		return response()->json(['meetings' => $meetings, 'user' => $this->getUser()], 200);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, MeetingRepo $meetingRepo) {
		$validator = Validator::make($request->all(), [
				'subject'       => ['required', 'string', 'min:3', 'max:150'],
				'start_date'    => ['required'],
				'start_time'    => ['required'],
				'duration'      => ['required'],
				'conference_id' => ['required', 'exists:conferences,id']

			]);

		$user = $request->user();

		if ($validator    ->fails()) {
			return response()->json((new ApiResponse)->error($validator->errors()), 422);
		}

		$conference = Conference::where('id', $request->conference_id)->first();
		if (!$conference) {
			return response()->json((new ApiResponse)->error('Meeting room does not exist.'), 403);
		}

		$meetingRepo->store($user, $conference, $request->all());

		return response()->json('ok', 200);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id, Request $request) {
		$meeting = Meeting::company($request->user()->tenant_id)->where('id', $id)->first();

		if (!$meeting) {

			$response = (new ApiResponse)->error('Meeting does not exist');
			return response()->json($response, 403);
		}

		return response()->json(['meeting' => $meeting], 200);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update($id, Request $request, MeetingRepo $confRepo) {

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function delete($id, Request $request, MeetingRepo $confRepo) {

	}

}
