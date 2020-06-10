<?php

namespace App\Http\Controllers\App\Tenant\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    protected $guard = 'web';

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/tenant/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:web', ['except' => ['logout']]);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.tenant.login');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
            'tenant_id' => 'required|exists:tenants,id',
        ]);
    }


    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password', 'tenant_id');
        
        $tenant = Tenant::find($request->tenant_id);
        $salt = is_object($tenant) ? $tenant->tenant_no : null;

        is_object($tenant) ? TenantManager::set($tenant) : '';
        is_object($tenant) ? Session::put('tenant', $tenant) : '';

        $old_password = $request->password;
        $credentials['password'] =  $salt . $old_password;
        
        return $credentials; //array_add($credentials, 'active', '1');
    }

/**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    public function logout($domain, Request $request)
    {
            
        // $tenant = Tenant::where('domain', $domain)->get()->first();

        // TenantManager::set($tenant);
        
        Auth::guard('web')->logout();
        Session::forget('tenant');
        // TenantManager::set($tenant);
        $request->session()->flush();
        $request->session()->regenerate();

        
        return redirect('login');
    }


    protected function redirectTo() {

        if (Auth::user()) {

            $this->tenant = Auth::user()->tenant;
            $tenant = $this->tenant;

             $url = "tenant/". $this->tenant->domain.'/dashboard';
             $url = route('tenant.account.profile', [$tenant->domain]);
            
            if(strtolower($tenant->status) == 'idle' ) {

                $url = route('tenant.registration.numbering', [$tenant->domain]);

            }elseif (Auth::user()->isActive) {
                
                $url = route('tenant.account.profile', [$tenant->domain]);
                
            }

            $this->redirectTo = $url;
            return $url;
        }

        
    }




}
