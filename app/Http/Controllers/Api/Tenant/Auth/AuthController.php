<?php

namespace App\Http\Controllers\Api\Tenant\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Services\Sms\Sms;
use App\Services\Sip\Freeswitch\Extension as ExtensionGenerator;
use App\Services\Response\ApiResponse;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Country;
use Carbon\Carbon;
use Hash;
use DB;
use Validator;
use App\Services\Tenant\RegistrationService;
use App\Events\UserWasRegistered;

class AuthController extends Controller
{
	public function __construct()
	{

	}

	/**
	 * @OA\Post(
	 *      path="/api/app/auth/signup",
	 *      operationId="signup",
	 *      tags={"Auth"},
	 *      summary="Signup User",
	 *      description="Signup User",
	 *	  	@OA\Parameter(
	 *          name="corporation_name",
	 *          description="company name ",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *	  	@OA\Parameter(
	 *          name="corporation_short_name",
	 *          description="Company short or account name",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 * 		@OA\Parameter(
	 *          name="firstname",
	 *          description="user's firstname ",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 * 		@OA\Parameter(
	 *          name="lastname",
	 *          description="User's lastname",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 * 		@OA\Parameter(
	 *          name="email",
	 *          description="User's email",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 * 		@OA\Parameter(
	 *          name="plan",
	 *          description="selected plan name, Exceutive, Standard..",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 * 		@OA\Parameter(
	 *          name="password",
	 *          description="password",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 * 		@OA\Parameter(
	 *          name="password_confirmation",
	 *          description="re-type password to match the password field",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Response(
	 *          response=200,
	 *          description="User successfully created"
	 *       ),
	 *       @OA\Response(response=422, description="Unprocessable Entity"),
	 *     )
	 *
	 */
	public function signup(Request $request, RegistrationService $registration)
	{
	
		$validator = Validator::make($request->all(), [
			'corporation_name' => 'required|string|max:255',
            'corporation_short_name' => 'required|unique:tenants,domain|string|max:255',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            // 'mobile_no' => 'required|numeric|phone_number|digits:11',
            'email' => 'required|string|email|max:255',
            'plan' => 'required',
            'password' => 'required|string|min:6|confirmed',
		]);
		
		if($validator->fails()){
			$response = (new ApiResponse)->error($validator->errors());

			return response()->json($response, 422);	
		}


        try{

            DB::beginTransaction();

            $user = $registration->registerTenant($request->all());
            
            Auth::guard()->login($user);
            event(new UserWasRegistered($user, ''));

            DB::commit();

        }catch(\Exception $e){

            \Log::info($e );
            DB::rollback();
            $validator->errors()->add('plan', $e->getMessage() );

            $response = (new ApiResponse)->error($validator->errors());
            return response()->json($response, 422);

        }

		$token_result = $user->createToken('Personal Access Token');
		$token = $token_result->token;

		if ($request->remember_me) {
			$token->expires_at = Carbon::now()->addWeeks(1);
			$token_result->token = $token;
		}

		return response()->json([
            'access_token' => $token_result->accessToken,
            'token_type' => 'Bearer',
            'user' => new UserResource($request->user()),
            'expires_at' => Carbon::parse($token_result->token->expires_at)->toDateTimeString()
        ], 200);

	}

	/**
	 * @OA\Post(
	 *      path="/api/app/auth/login",
	 *      operationId="login",
	 *      tags={"Auth"},
	 *      summary="Login User",
	 *      description="Login User",
	 *	  	@OA\Parameter(
	 *          name="email",
	 *          description="User email",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *	  	@OA\Parameter(
	 *          name="password",
	 *          description="User Password",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 * 		@OA\Parameter(
	 *          name="account",
	 *          description="Company short or account name",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Response(
	 *          response=200,
	 *          description="User successfully autheticated"
	 *       ),
	 *       @OA\Response(response=401, description="Unauthorized"),
	 *       @OA\Response(response=422, description="Unprocessable Entity"),
	 *     )
	 *
	 */
	public function login(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'email' => 'required|string|email',
            'password' => 'required|string',
            'account' => 'required|string|max:255|exists:tenants,domain'
		]);

		if($validator->fails()){
			$response = (new ApiResponse)->error($validator->errors());

			return response()->json($response, 422);	
		}

		$tenant = Tenant::whereDomain(strtolower($request->account))->get()->first();

        $id = is_object($tenant) ? $tenant->id : null;

        $salt = is_object($tenant) ? $tenant->tenant_no : null;
        
        $old_password = $request->password;
        $credentials = $request->only('email', 'password');
        $credentials['password'] =  $salt . $old_password;

		// $credentials['isActive'] = 1;
		$credentials['tenant_id'] = $id;
		if (!Auth::attempt($credentials)) {
			$response = (new ApiResponse)->error('Invalid Login Credentials!');
			return response()->json($response, 401);
		}

		$user = $request->user();

		$token_result = $user->createToken('Personal Access Token');
		$token = $token_result->token;

		if ($request->remember_me) {
			$token->expires_at = Carbon::now()->addWeeks(1);
			$token_result->token = $token;
		}

		return response()->json([
            'access_token' => $token_result->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token_result->token->expires_at)->toDateTimeString(),
            'user' => new UserResource($user)
        ], 200);
	}


	public function logout(Request $request)
	{
		$request->user()->token()->revoke();

        return response()->json(['message' => 'Successfully logged out']);
	}


	/**
	 * @OA\Get(
	 *      path="/api/app/find-domain",
	 *      operationId="findAvailableDomain",
	 *      tags={"Domain"},
	 *      summary="Get search available domain",
	 *      description="Get available domain",
	 *	  	@OA\Parameter(
	 *          name="domain",
	 *          description="customer unique identifier",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Response(
	 *          response=200,
	 *          description="Available domain"
	 *       ),
	 *       @OA\Response(response=402, description="Invalid"),
	 *     )
	 *
	 */
	public function getDomain($domain, Request $request)
	{
		
		$validator = Validator::make(['domain' => $domain], [
            'domain' => 'required|unique:tenants,domain|string|min:2|max:255',
		]);
		
		if($validator->fails()){
			$response = (new ApiResponse)->error($validator->errors());

			return response()->json($response, 422);	
		}

		return response()->json([
            'domain' => $domain
        ], 200);
	}


	/**
	 * @OA\Get(
	 *      path="/api/app/user",
	 *      operationId="getUser",
	 *      tags={"User"},
	 *      summary="Get Current Authenticated User",
	 *      description="Get Current Authenticated User",
	 *      @OA\Response(
	 *          response=200,
	 *          description="User details"
	 *       ),
	 *       @OA\Response(response=401, description="Unauthenticated"),
	 *     )
	 *
	 */
	public function user(Request $request)
	{
		$user = $request->user();
		if(strtolower($user->tenant->status) == 'registration'){

			return response()->json(['data' =>  new UserResource($request->user()), ], 200);
		}

		return new UserResource($request->user());
	}

	/**
	 * @OA\Put(
	 *      path="/api/app/auth/password",
	 *      operationId="changePassword",
	 *      tags={"Auth"},
	 *      summary="Change Password",
	 *      description="Change Password",
	 *	  	@OA\Parameter(
	 *          name="current_password",
	 *          description="Current User Password",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *	  	@OA\Parameter(
	 *          name="password",
	 *          description="New User Password",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *	  	@OA\Parameter(
	 *          name="password_confirmation",
	 *          description="New User Password Confirmation",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Response(
	 *          response=201,
	 *          description="User password is has been changed"
	 *       ),
	 *       @OA\Response(response=422, description="Unprocessable Entity"),
	 *     )
	 *
	 */
	public function changePassword(Request $request)
	{
		$user = User::find($request->user()->id);

		$this->validate($request, [
            'current_password' => 'required|validpassword:'.$user->password,
            'password' => 'required|alphanum|min:4|max:8|confirmed',
        ]);

        $user->password = Hash::make($request->password);
        $user->save();
        
        return response()->json(['message' => 'Your password is has been changed'], 201);
	}

	/**
	 * @OA\Put(
	 *      path="/api/app/auth/password/reset",
	 *      operationId="resetPassword",
	 *      tags={"Auth"},
	 *      summary="Reset Password",
	 *      description="Reset Password",
	 *	  	@OA\Parameter(
	 *          name="email",
	 *          description="User email",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 * 		@OA\Parameter(
	 *          name="account",
	 *          description="Company short or account name",
	 *          required=true,
	 *			in="path",
	 *          @OA\Schema(
	 *              type="string"
	 *          )
	 *      ),
	 *      @OA\Response(
	 *          response=201,
	 *          description="User password is has been changed"
	 *       ),
	 *      @OA\Response(
	 *          response=200,
	 *          description="No user exists for this phonenumber"
	 *       ),
	 *       @OA\Response(response=422, description="Unprocessable Entity"),
	 *     )
	 *
	 */
	public function resetPassword(Request $request)
	{
		$this->validate($request, [
			'account' => 'required|exists:tenants,domain',
			'email' => 'required|email',
		]);

		$user = User::where('phonenumber', $request->phonenumber)->first();

		if (is_null($user)) {
			$response = (new ApiResponse)->error('No user exists for this phonenumber');
			
			return response()->json($response, 200);
		}

		$password = $this->generatePassword();

		$user->update([
			'password' => bcrypt($password),
		]);

		(new Sms)->send($request->phonenumber, "Your new password is: {$password}");

		return response()->json(['message' => 'Password resetted!'], 201);
	}

	public function generatePassword()
	{
		return rand(1000, 9999);
	}



	public function checkIn(Request $request){

		$validator = Validator::make($request->all(), [
			'long' => 'required',
            'lat' => 'required',
		]);

		if($validator->fails()){
			$response = (new ApiResponse)->error($validator->errors());

			return response()->json($response, 422);	
		}

		\Log::log('info', $request->all());

		return response()->json('Ok', 200);	

	}


}
