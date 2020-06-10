<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Scopes\Facade\TenantManagerFacade as TenantManager;
use App\Models\Tenant;
use Session;
use Auth;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    //protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:web')->except('logout');
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
            'account' => 'required|string|max:255|exists:tenants,domain'
        ]);
    }

    protected function credentials(Request $request)
    {
        $this->tenant = Tenant::whereDomain($request->account)->get()->first();

        $id = is_object($this->tenant) ? $this->tenant->id : null;

        $salt = is_object($this->tenant) ? $this->tenant->tenant_no : null;
        
        TenantManager::set($this->tenant);
        $old_password = $request->password;
        $credentials = $request->only($this->username(), 'password');
        $credentials['password'] =  $salt . $old_password;

        return array_add($credentials, 'tenant_id', $id);
    }

    protected function redirectTo() {

        if (Auth::user()) {

            $this->tenant = Auth::user()->tenant;
            $tenant = $this->tenant;

            $url = route('tenant.account.profile', [$tenant->domain]);
            
            if($this->tenant->status == 1 && $this->tenant->expired == 1 &&  Auth::user()->manager) {

                $url = route('tenant.billing.index', [$tenant->domain]);

            }elseif (Auth::user()->active) {
                
                $url = "tenant/". $this->tenant->domain.'/account/profile';
                $url = route('tenant.account.profile', [$tenant->domain]);
            }

            $this->redirectTo = $url;
            return $url;
        }

        
    }

    public function successSignUp(Request $request){

        $tenant = Tenant::where("domain", $request->domain)->get()->first();
        $message = 'Welcome to One Reach Cloud Service. Check your email for your login credentials';

        return view('auth.success_signup', compact('message', 'tenant'));
    }

    public function mail(Request $request){

        
        return view('mails.new_user');
    }
}
