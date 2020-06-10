<?php

namespace App\Http\Controllers\Api\Tenant\Media;

use App\Http\Controllers\Controller;
use App\Models\Conference;

use App\Repos\ConferenceRepo;

use App\Services\Response\ApiResponse;
use App\Traits\GetUserResourceTrait;
use Gate;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use Validator;

class ConferenceController extends Controller {
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

		$conferences = Conference::company($request->user()->tenant_id)->with(['call_flow'])->get();

		return response()->json(['conferences' => $conferences, 'user' => $this->getUser()], 200);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, ConferenceRepo $confRepo) {
		$validator = Validator::make($request->all(), [
				'name' => ['required', 'string', Rule::unique('conferences', 'name')->where(function ($query) {
							return $query->where('tenant_id', request()->user()->tenant_id);
						}), 'min:3', 'max:150'],
				'extension' => ['required', 'numeric', Rule::unique('conferences', 'number')->where(function ($query) {
							return $query->where('tenant_id', request()->user()->tenant_id);
						}), 'digits_between:3:5'],
				'extension' => ['required', 'numeric', Rule::unique('call_flows', 'dial_string')->where(function ($query) {
							return $query->where('tenant_id', request()->user()->tenant_id);
						}), 'digits_between:3,5'],
				'type'       => 'required',
				'expiration' => 'required',
				'guest_pin'  => 'required|digits_between:3,5',

			]);

		$user = $request->user();

		if ($validator    ->fails()) {
			return response()->json((new ApiResponse)->error($validator->errors()), 422);
		}

		$conferences = Conference::company($user->tenant_id)->get();
		if ($conferences  ->count() >= 3 && !Gate::check('private_meeting_room')) {
			return response()->json((new ApiResponse)->error('Sorry you have exceed the maximum number of conference room for your package.'), 403);
		}

		$conference = $confRepo->store($user, $request->all());

		$conferences = Conference::company($user->tenant_id)->get();

		return response()->json(['conferences' => $conferences, 'user' => $this->getUser()], 200);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id, Request $request) {
		$conference = Conference::company($request->user()->tenant_id)->where('id', $id)->with(['call_flow', 'meetings'])->first();

		if (!$conference) {

			$response = (new ApiResponse)->error('Conference does not exist');
			return response()->json($response, 403);
		}

		return response()->json(['conference' => $conference], 200);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update($id, Request $request, ConferenceRepo $confRepo) {
		$validator = Validator::make($request->all(), [

				'name' => ['required', 'string', Rule::unique('conferences', 'name')->where(function ($query) {
							return $query->where('tenant_id', request()->user()->tenant_id);
						})->ignore($id), 'min:3', 'max:150'],
				'extension' => ['required', 'numeric', Rule::unique('conferences', 'number')->where(function ($query) {
							return $query->where('tenant_id', request()->user()->tenant_id);
						})->ignore($id), 'digits_between:3:5'],
				'extension' => ['required', 'numeric', Rule::unique('call_flows', 'dial_string')->where(function ($query) {
							return $query->where('tenant_id', request()->user()->tenant_id);
						})->ignore($request->call_flow_id), 'digits_between:3,5'],
				'type'       => 'required',
				'expiration' => 'required',
				'guest_pin'  => 'required|digits_between:3,5',

			]);

		$user = $request->user();
		if ($validator    ->fails()) {
			return response()->json((new ApiResponse)->error($validator->errors()), 422);
		}

		$conference = Conference::find($id);

		if (!$conference) {
			return response()->json((new ApiResponse)->error('Conference have already been deleted'), 403);
		}

		$destination = null;
		if (!$confRepo    ->deletable($conference, $destination)) {
			return response()->json((new ApiResponse)->error('Can not edit conference used as destination in '.$destination), 403);
		}

		$tenant = $request->user()->tenant;

		$conference = $confRepo->update($user, $conference, $request->all());

		$conferences = Conference::company($tenant->id)->get();

		return response()->json(['conference' => $conference, 'conferences' => $conferences], 200);

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function delete($id, Request $request, ConferenceRepo $confRepo) {

		$tenant = request()->user()->tenant;

		$conference = Conference::company($tenant->id)->where('id', $id)->first();

		if (!$conference) {
			return response()->json((new ApiResponse)->error('Conference have already been deleted'), 403);
		}
		$destination = null;
		if (!$confRepo->deletable($conference, $destination)) {

			return response()->json((new ApiResponse)->error('Can not delete any conference used as destination in '.$destination), 403);
		}

		try {

			$confRepo->delete($conference);
			$conferences = Conference::company($tenant->id)->get();

			return response()->json(['conferences' => $conferences, 'user' => $this->getUser()], 200);
		} catch (\Exception $e) {

			return response()->json((new ApiResponse)->error('Unable to delete conference'), 403);
		}
	}

}
