<?php

namespace App\Http\Controllers\Api\Tenant\Media;

use App\Http\Controllers\Controller;

use App\Models\VirtualReceptionist as Receptionist;

use App\Repos\VirtualReceptionistRepo;
use App\Services\Response\ApiResponse;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;
use Validator;

class VirtualReceptionistController extends Controller {
	public function __construct() {

		$this->middleware(['auth']);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		$user   = $request->user();
		$tenant = $user->tenant;

		$receptionists = Receptionist::company($user->tenant_id)->with(['menus'])->get();

		return response()->json(['ivrs' => $receptionists], 200);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, VirtualReceptionistRepo $ivrRepo) {
		$validator = Validator::make($request->all(), [

				'name' => ['required', Rule::unique('virtual_receptionists', 'name')->where(function ($query) {
							return $query->where('tenant_id', request()->user()->tenant_id);
						}), 'min:3', 'max:125'],
				'extension' => ['required', 'numeric', Rule::unique('call_flows', 'dial_string')->where(function ($query) {
							return $query->where('tenant_id', request()->user()->tenant_id);
						}), 'digits_between:3,5'],
				'ivr_menu_type' => 'required',
				'sound_id'      => 'required_if:ivr_menu_type,sound',

			]);

		if ($validator    ->fails()) {
			return response()->json((new ApiResponse)->error($validator->errors()), 422);
		}

		$user   = $request->user();
		$tenant = $user->tenant;

		$ivrRepo->store($request->all());

		$receptionists = Receptionist::company($user->tenant_id)->with(['menus'])->get();

		return response()->json(['ivrs' => $receptionists], 200);

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id, Request $request) {
		$user   = $request->user();
		$tenant = $user->tenant;

		$receptionist = Receptionist::company($user->tenant_id)->where('id', $id)->with(['menus'])->first();

		if (!$receptionist) {

			$response = (new ApiResponse)->error('IVR have already been deleted');
			return response()->json($response, 403);
		}

		return response()->json(['ivr' => $receptionist], 200);

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id, VirtualReceptionistRepo $ivrRepo) {
		$validator = Validator::make($request->all(), [
				'name' => ['required', Rule::unique('virtual_receptionists', 'name')->where(function ($query) {
							return $query->where('tenant_id', request()->user()->tenant_id);
						})->ignore($id), 'min:3', 'max:125'],
				'extension' => ['required', 'numeric', Rule::unique('call_flows', 'dial_string')->where(function ($query) {
							return $query->where('tenant_id', request()->user()->tenant_id);
						})->ignore(request()->call_flow_id), 'digits_between:3,5'],
				'ivr_menu_type' => 'required',
				'sound_id'      => 'required_if:ivr_menu_type,sound',

			]);

		if ($validator    ->fails()) {
			return response()->json((new ApiResponse)->error($validator->errors()), 422);
		}

		$user   = $request->user();
		$tenant = $user->tenant;

		$receptionist = Receptionist::company($user->tenant_id)->where('id', $id)->with(['menus'])->first();

		if (!$receptionist) {

			$response = (new ApiResponse)->error('IVR have already been deleted');
			return response()->json($response, 403);
		}

		$ivrRepo->update($receptionist, $request->all());

		$receptionists = Receptionist::company($user->tenant_id)->with(['menus'])->get();

		return response()->json(['ivrs' => $receptionists], 200);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function delete($id, Request $request, VirtualReceptionistRepo $ivrRepo) {

		$user   = $request->user();
		$tenant = $user->tenant;

		$receptionist = Receptionist::company($user->tenant_id)->where('id', $id)->first();

		if (!$receptionist) {
			return response()->json((new ApiResponse)->error('IVR have already been deleted'), 403);
		}

		$destination = null;
		if (!$ivrRepo     ->deletable($receptionist, $destination)) {
			return response()->json((new ApiResponse)->error('Can not delete IVR used as destination in '.$destination), 403);
		}

		try {

			$ivrRepo->delete($receptionist);

			$receptionists = Receptionist::company($user->tenant_id)->get();

			return response()->json(['ivrs' => $receptionists], 200);

		} catch (\Exception $e) {

			return response()->json((new ApiResponse)->error('Unable to delete IVR'), 403);
		}

	}

}
