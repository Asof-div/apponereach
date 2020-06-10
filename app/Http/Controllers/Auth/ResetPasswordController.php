<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

use App\Models\Tenant;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/login';
    protected $tenant;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'account' => 'required|string|max:255|exists:tenants,domain',
            'password' => 'required|confirmed|min:6',
        ];
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($response)
                    : $this->sendResetFailedResponse($request, $response);
    }


    protected function credentials(Request $request)
    {
        $tenant = Tenant::whereDomain($request->account)->get()->first();
        $this->tenant = $tenant;
        $id = is_object($tenant) ? $tenant->id : null;

        $salt = is_object($tenant) ? $tenant->tenant_no : null;
        
        TenantManager::set($tenant);
        $old_password = $request->password;

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );
        
        $credentials['password'] =  $salt . $old_password;
        $credentials['password_confirmation'] =  $salt . $old_password;
    
        return array_add($credentials, 'tenant_id', $id);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);

        $user->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        $this->guard()->login($user);
    }

    protected function redirectTo() {

        if (Auth::user()) {

            $this->tenant = Auth::user()->tenant;
            $tenant = $this->tenant;

            $url = route('tenant.account.profile', [$tenant->domain]);
            
            if($this->tenant->status == 1 && $this->tenant->expired == 1 &&  Auth::user()->manager) {

                $url = route('tenant.billing.index', [$tenant->domain]);

            }   

            $this->redirectTo = $url;
            return $url;
        }
    
    }

}
