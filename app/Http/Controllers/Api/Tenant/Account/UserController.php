<?php

namespace App\Http\Controllers\Api\Tenant\Account;

use App\Http\Controllers\Controller;

use App\Models\User;
use App\Repos\ExtensionRepo;
use App\Services\Response\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class UserController extends Controller {

	/**
	 * @OA\Get(
	 *      path="/api/app/account/users",
	 *      operationId="getUsers",
	 *      tags={"Account User"},
	 *      summary="Get Users",
	 *      description="Return Users Attached To Tenant",
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
		$users = User::where('tenant_id', request()->user()->tenant_id)->orderBy('firstname')->with(['profile'])->get();

		return response()->json($users, 200);
	}

	/**
	 * @OA\Post(
	 *      path="/api/app/account/users",
	 *      operationId="createNewUser",
	 *      tags={"Account User"},
	 *      summary="Create new user",
	 *      description="Return user data",
	 *      @OA\Parameter(
	 *          name="firstname",
	 *          description="Firstname ",
	 *          required=true,
	 *          in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Parameter(
	 *          name="lastname",
	 *          description="Lastname ",
	 *          required=true,
	 *          in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Parameter(
	 *          name="email",
	 *          description="Email",
	 *          required=true,
	 *          in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Parameter(
	 *          name="job_role",
	 *          description="Job role",
	 *          required=false,
	 *          in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Parameter(
	 *          name="password",
	 *          description="Password ",
	 *          required=true,
	 *          in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Parameter(
	 *          name="password_confirmation",
	 *          description="Retry password",
	 *          required=false,
	 *          in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Parameter(
	 *          name="credit_limit",
	 *          description="Type of limits, non-blocking, quota",
	 *          required=true,
	 *          in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Parameter(
	 *          name="credits",
	 *          description="Credit ",
	 *          required=true,
	 *          in="path",
	 *          @OA\Schema(
	 *              type="decimal"
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
	public function store(Request $request) {
		$validator = Validator::make($request->all(), [
				'firstname' => 'required|string|min:3|max:200',
				'lastname'  => 'required|string|min:3|max:200',
				'email'     => ['required', 'email', Rule::unique('users')->where(function ($query) {
							return $query->where('tenant_id', request()->user()->tenant_id);
						})],
				'job_title'   => 'required|string|min:2|max:200',
				'password'    => 'required|string|min:6|max:25|confirmed',
				'quota'       => 'required_if:credit_limit,quota|numeric',
				'primary_did' => 'required',
				'fallback'    => 'required',
				'followme'    => 'required_if:fallback,followme',

			]);

		$tenant = $request->user()->tenant;

		$salt = $tenant->tenant_no;

		if ($validator    ->fails()) {
			return response()->json((new ApiResponse())->error($validator->errors()), 422);
		}

		$data = $request->all();

		$user = User::create([
				'lastname'        => ucfirst($data['lastname']),
				'firstname'       => ucfirst($data['firstname']),
				'tenant_id'       => $tenant->id,
				'email'           => strtolower($data['email']),
				'manager'         => isset($data['manager'])?1:0,
				'password'        => bcrypt($salt.$data['password']),
				'primary_did'     => isset($data['primary_did'])?$data['primary_did']:'',
				'credit_limit'    => isset($data['credit_limit'])?$data['credit_limit']:'unlimited',
				'quota'           => isset($data['quota'])?(int) $data['quota']:0.00,
				'external'        => isset($data['external'])?true:false,
				'international'   => isset($data['international'])?true:false,
				'fallback_type'   => isset($data['fallback'])?$data['fallback']:'hangup',
				'fallback_action' => isset($data['fallback']) && $data['fallback'] == 'followme'?$data['followme']:'',
			]);

		$user->profile->update(['role' => $data['job_title']]);

		if ($request->has('roles')) {
			foreach ($request->roles as $roleId) {
				$user->addRole((int) $roleId);

			}
		}

		return response()->json($user, 200);

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, $id) {

		$user = User::company($request->user()->tenant_id)->where('id', $id)->with(['extensions', 'profile', 'trackings'])->first();

		if (!$user) {
			return response()->json((new ApiResponse())->error("User doesn't exist "), 403);
		}

		return response()->json($user, 200);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id, ExtensionRepo $extensionRepo) {
		$validator = Validator::make($request->all(), [
				'firstname' => 'required|string|min:3|max:200',
				'lastname'  => 'required|string|min:3|max:200',
				'email'     => ['required', 'email', Rule::unique('users')->where(function ($query) {
							return $query->where('tenant_id', request()->user()->tenant_id);
						})->ignore($id)],
				'job_title'   => 'required|string|min:2|max:200',
				'quota'       => 'required_if:credit_limit,quota|numeric',
				'primary_did' => 'required',
				'fallback'    => 'required',
				'followme'    => 'required_if:fallback,followme',
			]);

		$tenant = $request->user()->tenant;

		$salt = $tenant->tenant_no;

		$user = User::find($id);

		if ($validator    ->fails()) {
			return response()->json((new ApiResponse)->error($validator->errors()), 422);
		}

		if (!$user) {
			return response()->json((new ApiResponse)->error('Extension have already been deleted'), 402);
		}

		$data = $request->all();
		$user->update([
				'lastname'        => ucfirst($data['lastname']),
				'firstname'       => ucfirst($data['firstname']),
				'tenant_id'       => $tenant->id,
				'email'           => strtolower($data['email']),
				'primary_did'     => isset($data['primary_did'])?$data['primary_did']:'',
				'credit_limit'    => isset($data['credit_limit'])?$data['credit_limit']:'unlimited',
				'quota'           => isset($data['quota'])?(int) $data['quota']:0.00,
				'external'        => isset($data['external'])?true:false,
				'international'   => isset($data['international'])?true:false,
				'fallback_type'   => isset($data['fallback'])?$data['fallback']:'hangup',
				'fallback_action' => isset($data['fallback']) && $data['fallback'] == 'followme'?$data['followme']:'',
			]);

		$user->profile->update(['role' => $data['job_title']]);
		$extensionRepo->updateFallback($user);

		if ($request->has('roles')) {
			$user->roles()->detach();
			foreach ($request->roles as $roleId) {
				$user->addRole((int) $roleId);

			}
		}

		return response()->json($user, 200);

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function delete($id, Request $request) {
		$tenant = request()->user()->tenant;

		$user = User::company($tenant->id)->where('id', $id)->first();

		if (!$user) {
			return response()->json((new ApiResponse)->error('User have already been deleted'), 403);
		}

		try {

			$user->delete();

			$users = User::company($tenant->id)->get();

			return response()->json($users, 200);
		} catch (\Exception $e) {

			return response()->json((new ApiResponse)->error('Unable to delete extension'), 402);
		}

	}
}
